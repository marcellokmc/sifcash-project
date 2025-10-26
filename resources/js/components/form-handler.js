/**
 * Form Handler avec support offline
 * Gestion avancée des formulaires avec validation et soumission hors ligne
 */

class FormHandler {
    constructor() {
        this.forms = new Map();
        this.validationRules = new Map();
        this.init();
    }

    init() {
        this.setupFormListeners();
        this.setupValidationStyles();
    }

    /**
     * Configuration des écouteurs de formulaires
     */
    setupFormListeners() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.hasAttribute('data-ajax') || form.hasAttribute('data-offline')) {
                e.preventDefault();
                this.handleFormSubmit(form, e);
            }
        });

        // Validation en temps réel
        document.addEventListener('input', (e) => {
            const input = e.target;
            if (input.form && input.hasAttribute('data-validate')) {
                this.validateField(input);
            }
        });

        // Auto-sauvegarde des brouillons
        document.addEventListener('input', this.debounce((e) => {
            const input = e.target;
            if (input.form && input.form.hasAttribute('data-auto-save')) {
                this.saveDraft(input.form);
            }
        }, 1000));
    }

    /**
     * Gestion de la soumission de formulaire
     */
    async handleFormSubmit(form, event) {
        const formId = form.id || this.generateFormId();
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        
        try {
            // Afficher l'état de chargement
            this.setFormLoading(form, true);
            
            // Validation complète
            if (!this.validateForm(form)) {
                this.setFormLoading(form, false);
                return;
            }

            const formData = new FormData(form);
            const data = this.formDataToObject(formData);
            
            // Déterminer si en ligne ou hors ligne
            if (navigator.onLine && !form.hasAttribute('data-offline-only')) {
                await this.submitOnline(form, data);
            } else {
                await this.submitOffline(form, data);
            }

            // Nettoyer le brouillon
            this.clearDraft(formId);
            
        } catch (error) {
            console.error('Erreur soumission formulaire:', error);
            this.showFormError(form, 'Erreur lors de la soumission du formulaire');
        } finally {
            this.setFormLoading(form, false);
        }
    }

    /**
     * Soumission en ligne
     */
    async submitOnline(form, data) {
        const url = form.action || window.location.href;
        const method = form.method || 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const result = await response.json();
            this.handleSubmitSuccess(form, result);
        } else {
            const error = await response.json();
            this.handleSubmitError(form, error);
        }
    }

    /**
     * Soumission hors ligne
     */
    async submitOffline(form, data) {
        const formType = form.dataset.type || 'form';
        const offlineId = await window.OfflineStorage?.saveOfflineData(formType, {
            ...data,
            action: 'save',
            formId: form.id,
            url: form.action,
            method: form.method || 'POST'
        });

        if (offlineId) {
            this.showFormSuccess(form, 'Données sauvegardées hors ligne. Elles seront synchronisées automatiquement.');
            
            // Déclencher l'événement personnalisé
            form.dispatchEvent(new CustomEvent('offlineSubmit', {
                detail: { offlineId, data }
            }));
        } else {
            throw new Error('Impossible de sauvegarder hors ligne');
        }
    }

    /**
     * Validation de formulaire
     */
    validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input, select, textarea');
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Validation de champ
     */
    validateField(field) {
        const rules = this.getValidationRules(field);
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Règles de base
        if (field.required && !value) {
            isValid = false;
            errorMessage = 'Ce champ est requis';
        } else if (value && field.type === 'email' && !this.isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Format email invalide';
        } else if (value && field.type === 'tel' && !this.isValidPhone(value)) {
            isValid = false;
            errorMessage = 'Format téléphone invalide';
        } else if (field.minLength && value.length < field.minLength) {
            isValid = false;
            errorMessage = `Minimum ${field.minLength} caractères`;
        } else if (field.maxLength && value.length > field.maxLength) {
            isValid = false;
            errorMessage = `Maximum ${field.maxLength} caractères`;
        }

        // Règles personnalisées
        if (isValid && rules.length > 0) {
            for (const rule of rules) {
                const result = rule.validate(value, field);
                if (!result.valid) {
                    isValid = false;
                    errorMessage = result.message;
                    break;
                }
            }
        }

        // Mettre à jour l'interface
        this.updateFieldValidation(field, isValid, errorMessage);
        
        return isValid;
    }

    /**
     * Obtenir les règles de validation
     */
    getValidationRules(field) {
        const fieldName = field.name || field.id;
        return this.validationRules.get(fieldName) || [];
    }

    /**
     * Ajouter une règle de validation personnalisée
     */
    addValidationRule(fieldName, validator, message) {
        if (!this.validationRules.has(fieldName)) {
            this.validationRules.set(fieldName, []);
        }
        
        this.validationRules.get(fieldName).push({
            validate: validator,
            message: message
        });
    }

    /**
     * Mettre à jour l'affichage de validation
     */
    updateFieldValidation(field, isValid, errorMessage) {
        const formGroup = field.closest('.form-group, .mb-3, .form-floating');
        const existingError = formGroup?.querySelector('.invalid-feedback, .form-error');
        
        // Supprimer les anciens messages d'erreur
        if (existingError) {
            existingError.remove();
        }
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            
            // Ajouter le message d'erreur
            if (formGroup && errorMessage) {
                const errorEl = document.createElement('div');
                errorEl.className = 'invalid-feedback';
                errorEl.textContent = errorMessage;
                formGroup.appendChild(errorEl);
            }
        }
    }

    /**
     * Auto-sauvegarde des brouillons
     */
    saveDraft(form) {
        const formId = form.id || this.generateFormId();
        const data = this.formDataToObject(new FormData(form));
        
        localStorage.setItem(`draft_${formId}`, JSON.stringify({
            data: data,
            timestamp: Date.now(),
            url: window.location.pathname
        }));
    }

    /**
     * Charger un brouillon
     */
    loadDraft(formId) {
        const draft = localStorage.getItem(`draft_${formId}`);
        if (draft) {
            try {
                return JSON.parse(draft);
            } catch (error) {
                console.error('Erreur chargement brouillon:', error);
                localStorage.removeItem(`draft_${formId}`);
            }
        }
        return null;
    }

    /**
     * Supprimer un brouillon
     */
    clearDraft(formId) {
        localStorage.removeItem(`draft_${formId}`);
    }

    /**
     * Restaurer un brouillon dans le formulaire
     */
    restoreDraft(form) {
        const formId = form.id || this.generateFormId();
        const draft = this.loadDraft(formId);
        
        if (draft && draft.url === window.location.pathname) {
            const timeDiff = Date.now() - draft.timestamp;
            const maxAge = 24 * 60 * 60 * 1000; // 24 heures
            
            if (timeDiff < maxAge) {
                // Demander confirmation
                const restore = confirm('Un brouillon a été trouvé. Voulez-vous le restaurer ?');
                if (restore) {
                    this.populateForm(form, draft.data);
                    this.showFormInfo(form, 'Brouillon restauré');
                }
            } else {
                this.clearDraft(formId);
            }
        }
    }

    /**
     * Remplir le formulaire avec des données
     */
    populateForm(form, data) {
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = data[key] === field.value;
                } else {
                    field.value = data[key];
                }
            }
        });
    }

    /**
     * États de formulaire
     */
    setFormLoading(form, loading) {
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        
        if (loading) {
            form.classList.add('form-loading');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.textContent;
                submitBtn.dataset.originalText = originalText;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';
            }
        } else {
            form.classList.remove('form-loading');
            if (submitBtn) {
                submitBtn.disabled = false;
                const originalText = submitBtn.dataset.originalText;
                if (originalText) {
                    submitBtn.textContent = originalText;
                }
            }
        }
    }

    /**
     * Gestion des réponses
     */
    handleSubmitSuccess(form, result) {
        this.showFormSuccess(form, result.message || 'Formulaire soumis avec succès');
        
        // Redirection si spécifiée
        if (result.redirect) {
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        } else {
            // Réinitialiser le formulaire
            form.reset();
            this.clearFormValidation(form);
        }
        
        // Événement personnalisé
        form.dispatchEvent(new CustomEvent('submitSuccess', { detail: result }));
    }

    handleSubmitError(form, error) {
        let message = 'Erreur lors de la soumission';
        
        if (error.errors) {
            // Erreurs de validation Laravel
            Object.keys(error.errors).forEach(field => {
                const fieldEl = form.querySelector(`[name="${field}"]`);
                if (fieldEl) {
                    this.updateFieldValidation(fieldEl, false, error.errors[field][0]);
                }
            });
            message = 'Veuillez corriger les erreurs ci-dessous';
        } else if (error.message) {
            message = error.message;
        }
        
        this.showFormError(form, message);
        form.dispatchEvent(new CustomEvent('submitError', { detail: error }));
    }

    /**
     * Messages de formulaire
     */
    showFormMessage(form, message, type = 'info') {
        const existingAlert = form.querySelector('.form-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show form-alert`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        form.insertBefore(alert, form.firstChild);
        
        // Auto-masquer après 5 secondes
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    showFormSuccess(form, message) {
        this.showFormMessage(form, message, 'success');
    }

    showFormError(form, message) {
        this.showFormMessage(form, message, 'danger');
    }

    showFormInfo(form, message) {
        this.showFormMessage(form, message, 'info');
    }

    /**
     * Utilitaires
     */
    clearFormValidation(form) {
        const fields = form.querySelectorAll('.is-valid, .is-invalid');
        fields.forEach(field => {
            field.classList.remove('is-valid', 'is-invalid');
        });
        
        const errors = form.querySelectorAll('.invalid-feedback, .form-error');
        errors.forEach(error => error.remove());
    }

    formDataToObject(formData) {
        const obj = {};
        formData.forEach((value, key) => {
            if (obj[key]) {
                if (Array.isArray(obj[key])) {
                    obj[key].push(value);
                } else {
                    obj[key] = [obj[key], value];
                }
            } else {
                obj[key] = value;
            }
        });
        return obj;
    }

    generateFormId() {
        return 'form_' + Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    setupValidationStyles() {
        if (!document.getElementById('form-validation-styles')) {
            const style = document.createElement('style');
            style.id = 'form-validation-styles';
            style.textContent = `
                .form-loading {
                    pointer-events: none;
                    opacity: 0.7;
                }
                
                .form-alert {
                    margin-bottom: 1rem;
                }
                
                .is-valid {
                    border-color: #198754 !important;
                }
                
                .is-invalid {
                    border-color: #dc3545 !important;
                }
                
                .invalid-feedback {
                    display: block !important;
                    color: #dc3545;
                    font-size: 0.875em;
                    margin-top: 0.25rem;
                }
            `;
            document.head.appendChild(style);
        }
    }

    // Validateurs utilitaires
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\-\(\)\s]{8,}$/;
        return phoneRegex.test(phone);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialiser automatiquement
const formHandler = new FormHandler();

// API publique
window.FormHandler = {
    addValidationRule: (fieldName, validator, message) => {
        formHandler.addValidationRule(fieldName, validator, message);
    },
    
    restoreDraft: (form) => {
        formHandler.restoreDraft(form);
    },
    
    validateForm: (form) => {
        return formHandler.validateForm(form);
    },
    
    populateForm: (form, data) => {
        formHandler.populateForm(form, data);
    }
};

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    // Restaurer automatiquement les brouillons
    document.querySelectorAll('form[data-auto-save]').forEach(form => {
        formHandler.restoreDraft(form);
    });
});

export default FormHandler;