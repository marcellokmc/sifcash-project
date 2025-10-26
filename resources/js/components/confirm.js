/**
 * Système de confirmation moderne avec modals Bootstrap
 */

let confirmModal = null;
let currentResolve = null;

export function initConfirm() {
    createConfirmModal();
    handleConfirmTriggers();
}

function createConfirmModal() {
    if (confirmModal) return;

    const modalHtml = `
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-2">
                        <h5 class="modal-title d-flex align-items-center" id="confirmModalLabel">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            <span>Confirmation</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <p class="mb-0" id="confirmModalMessage">Êtes-vous sûr de vouloir effectuer cette action ?</p>
                    </div>
                    <div class="modal-footer border-0 pt-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-primary" id="confirmModalConfirm">
                            <i class="fas fa-check me-1"></i>Confirmer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    // Gérer les événements
    document.getElementById('confirmModalConfirm').addEventListener('click', () => {
        if (currentResolve) {
            currentResolve(true);
            currentResolve = null;
        }
        confirmModal.hide();
    });

    document.getElementById('confirmModal').addEventListener('hidden.bs.modal', () => {
        if (currentResolve) {
            currentResolve(false);
            currentResolve = null;
        }
    });
}

export function showConfirm(message, title = 'Confirmation', options = {}) {
    const {
        confirmText = 'Confirmer',
        cancelText = 'Annuler',
        confirmClass = 'btn-primary',
        icon = 'fas fa-exclamation-triangle text-warning'
    } = options;

    return new Promise((resolve) => {
        currentResolve = resolve;

        // Mettre à jour le contenu
        document.getElementById('confirmModalLabel').innerHTML = `
            <i class="${icon} me-2"></i>
            <span>${title}</span>
        `;
        document.getElementById('confirmModalMessage').textContent = message;

        const confirmBtn = document.getElementById('confirmModalConfirm');
        confirmBtn.innerHTML = `<i class="fas fa-check me-1"></i>${confirmText}`;
        confirmBtn.className = `btn ${confirmClass}`;

        confirmModal.show();
    });
}

function handleConfirmTriggers() {
    // Gérer les éléments avec data-confirm
    document.addEventListener('click', async (e) => {
        const element = e.target.closest('[data-confirm]');
        if (!element) return;

        e.preventDefault();
        
        const message = element.dataset.confirm;
        const title = element.dataset.confirmTitle || 'Confirmation';
        const type = element.dataset.confirmType || 'default';

        const typeConfig = getConfirmTypeConfig(type);
        
        const confirmed = await showConfirm(message, title, {
            confirmClass: typeConfig.confirmClass,
            icon: typeConfig.icon
        });

        if (confirmed) {
            // Si c'est un lien
            if (element.tagName === 'A') {
                window.location.href = element.href;
            }
            // Si c'est un bouton de formulaire
            else if (element.type === 'submit' || element.closest('form')) {
                const form = element.closest('form');
                if (form) {
                    // Ajouter un loading state
                    addLoadingState(element);
                    form.submit();
                }
            }
            // Si c'est un bouton avec une fonction onclick
            else if (element.onclick) {
                addLoadingState(element);
                element.onclick();
            }
        }
    });
}

function getConfirmTypeConfig(type) {
    const configs = {
        danger: {
            confirmClass: 'btn-danger',
            icon: 'fas fa-exclamation-triangle text-danger'
        },
        warning: {
            confirmClass: 'btn-warning',
            icon: 'fas fa-exclamation-triangle text-warning'
        },
        info: {
            confirmClass: 'btn-info',
            icon: 'fas fa-info-circle text-info'
        },
        success: {
            confirmClass: 'btn-success',
            icon: 'fas fa-check-circle text-success'
        },
        default: {
            confirmClass: 'btn-primary',
            icon: 'fas fa-exclamation-triangle text-warning'
        }
    };

    return configs[type] || configs.default;
}

function addLoadingState(element) {
    const originalText = element.innerHTML;
    element.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Traitement...';
    element.disabled = true;

    // Restaurer après 5 secondes (sécurité)
    setTimeout(() => {
        element.innerHTML = originalText;
        element.disabled = false;
    }, 5000);
}

// Fonctions utilitaires spécialisées
export function confirmDelete(message = 'Cette action est irréversible.') {
    return showConfirm(message, 'Confirmer la suppression', {
        confirmText: 'Supprimer',
        confirmClass: 'btn-danger',
        icon: 'fas fa-trash text-danger'
    });
}

export function confirmSubmit(message = 'Voulez-vous soumettre ce formulaire ?') {
    return showConfirm(message, 'Confirmer la soumission', {
        confirmText: 'Soumettre',
        confirmClass: 'btn-success',
        icon: 'fas fa-paper-plane text-success'
    });
}

export function confirmCancel(message = 'Voulez-vous annuler cette opération ?') {
    return showConfirm(message, 'Confirmer l\'annulation', {
        confirmText: 'Annuler l\'opération',
        confirmClass: 'btn-warning',
        icon: 'fas fa-times-circle text-warning'
    });
}