/**
 * Gestion des boutons avec état de chargement
 */

export function initLoadingButtons() {
    handleLoadingButtons();
    handleFormSubmissions();
}

function handleLoadingButtons() {
    document.addEventListener('click', (e) => {
        const button = e.target.closest('[data-loading]');
        if (!button) return;

        const loadingText = button.dataset.loading || 'Chargement...';
        const loadingIcon = button.dataset.loadingIcon || 'fas fa-spinner fa-spin';
        
        setButtonLoading(button, loadingText, loadingIcon);
    });
}

function handleFormSubmissions() {
    document.addEventListener('submit', (e) => {
        const form = e.target;
        const submitButton = form.querySelector('[type="submit"]');
        
        if (submitButton && !submitButton.hasAttribute('data-no-loading')) {
            const loadingText = submitButton.dataset.loading || 'Envoi en cours...';
            const loadingIcon = submitButton.dataset.loadingIcon || 'fas fa-spinner fa-spin';
            
            setButtonLoading(submitButton, loadingText, loadingIcon);
        }
    });
}

export function setButtonLoading(button, loadingText = 'Chargement...', loadingIcon = 'fas fa-spinner fa-spin') {
    if (button.dataset.originalContent) return; // Déjà en état de chargement

    // Sauvegarder le contenu original
    button.dataset.originalContent = button.innerHTML;
    button.dataset.originalDisabled = button.disabled;
    
    // Appliquer l'état de chargement
    button.innerHTML = `<i class="${loadingIcon} me-1"></i>${loadingText}`;
    button.disabled = true;
    button.classList.add('loading');

    // Auto-restore après 30 secondes (sécurité)
    setTimeout(() => {
        restoreButton(button);
    }, 30000);
}

export function restoreButton(button) {
    if (!button.dataset.originalContent) return;

    button.innerHTML = button.dataset.originalContent;
    button.disabled = button.dataset.originalDisabled === 'true';
    button.classList.remove('loading');
    
    delete button.dataset.originalContent;
    delete button.dataset.originalDisabled;
}

export function setButtonSuccess(button, successText = 'Terminé', duration = 2000) {
    if (button.dataset.originalContent) return;

    button.dataset.originalContent = button.innerHTML;
    button.innerHTML = `<i class="fas fa-check me-1"></i>${successText}`;
    button.classList.add('btn-success');
    button.disabled = true;

    setTimeout(() => {
        restoreButton(button);
        button.classList.remove('btn-success');
    }, duration);
}

export function setButtonError(button, errorText = 'Erreur', duration = 3000) {
    if (button.dataset.originalContent) return;

    button.dataset.originalContent = button.innerHTML;
    button.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i>${errorText}`;
    button.classList.add('btn-danger');
    button.disabled = true;

    setTimeout(() => {
        restoreButton(button);
        button.classList.remove('btn-danger');
    }, duration);
}

// Utilitaires pour AJAX
export function handleAjaxButton(button, promise) {
    setButtonLoading(button);

    promise
        .then((response) => {
            setButtonSuccess(button);
            return response;
        })
        .catch((error) => {
            setButtonError(button);
            throw error;
        });

    return promise;
}