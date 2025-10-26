/**
 * Système de notifications toast moderne
 */

let toastContainer = null;

export function initToasts() {
    createToastContainer();
    handleLaravelFlashMessages();
}

function createToastContainer() {
    if (toastContainer) return;
    
    toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.className = 'position-fixed top-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';
    document.body.appendChild(toastContainer);
}

function handleLaravelFlashMessages() {
    // Gérer les messages flash de Laravel
    const flashSuccess = document.querySelector('[data-flash="success"]');
    const flashError = document.querySelector('[data-flash="error"]');
    const flashWarning = document.querySelector('[data-flash="warning"]');
    const flashInfo = document.querySelector('[data-flash="info"]');

    if (flashSuccess) {
        showToast(flashSuccess.textContent, 'success');
        flashSuccess.remove();
    }
    if (flashError) {
        showToast(flashError.textContent, 'error');
        flashError.remove();
    }
    if (flashWarning) {
        showToast(flashWarning.textContent, 'warning');
        flashWarning.remove();
    }
    if (flashInfo) {
        showToast(flashInfo.textContent, 'info');
        flashInfo.remove();
    }
}

export function showToast(message, type = 'success', options = {}) {
    const {
        duration = 5000,
        title = null,
        action = null,
        persistent = false
    } = options;

    const toastId = 'toast-' + Date.now();
    const typeConfig = getToastTypeConfig(type);
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-bg-${typeConfig.bootstrap} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${typeConfig.icon ? `<i class="${typeConfig.icon} me-2"></i>` : ''}
                ${title ? `<strong class="me-2">${title}</strong>` : ''}
                ${message}
                ${action ? `<div class="mt-2">${action}</div>` : ''}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    `;

    // Animation d'entrée
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'transform 0.3s ease-in-out';
    
    toastContainer.appendChild(toast);
    
    // Animation d'apparition
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);

    // Initialiser le toast Bootstrap
    const bsToast = new bootstrap.Toast(toast, {
        autohide: !persistent,
        delay: duration
    });

    bsToast.show();

    // Gérer la suppression
    toast.addEventListener('hidden.bs.toast', () => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    });

    // Auto-dismiss si non persistant
    if (!persistent) {
        setTimeout(() => {
            if (document.getElementById(toastId)) {
                bsToast.hide();
            }
        }, duration);
    }

    return toastId;
}

function getToastTypeConfig(type) {
    const configs = {
        success: {
            bootstrap: 'success',
            icon: 'fas fa-check-circle'
        },
        error: {
            bootstrap: 'danger',
            icon: 'fas fa-exclamation-triangle'
        },
        warning: {
            bootstrap: 'warning',
            icon: 'fas fa-exclamation-circle'
        },
        info: {
            bootstrap: 'info',
            icon: 'fas fa-info-circle'
        }
    };

    return configs[type] || configs.info;
}

// Fonctions utilitaires spécialisées
export function showSuccessToast(message, options = {}) {
    return showToast(message, 'success', options);
}

export function showErrorToast(message, options = {}) {
    return showToast(message, 'error', { ...options, persistent: true });
}

export function showWarningToast(message, options = {}) {
    return showToast(message, 'warning', options);
}

export function showInfoToast(message, options = {}) {
    return showToast(message, 'info', options);
}

// Toast avec action
export function showActionToast(message, actionText, actionCallback, options = {}) {
    const actionHtml = `<button type="button" class="btn btn-sm btn-outline-light mt-1" onclick="this.parentElement.style.display='none'; (${actionCallback})();">${actionText}</button>`;
    
    return showToast(message, options.type || 'info', {
        ...options,
        action: actionHtml,
        persistent: true
    });
}