/**
 * PWA Manager
 * Gestion de l'installation PWA et des fonctionnalités avancées
 */

class PWAManager {
    constructor() {
        this.installPrompt = null;
        this.isInstalled = false;
        this.isSupported = false;
        this.updateAvailable = false;
        this.registration = null;
        
        this.init();
    }

    /**
     * Initialisation du PWA Manager
     */
    init() {
        this.checkSupport();
        this.setupInstallPrompt();
        this.setupAppInstalled();
        this.setupBeforeInstallPrompt();
        this.createInstallButton();
        this.setupUrlHandling();
        this.checkIfInstalled();
    }

    /**
     * Vérifier le support PWA
     */
    checkSupport() {
        this.isSupported = 'serviceWorker' in navigator && 'beforeinstallprompt' in window;
        
        if (!this.isSupported) {
            console.warn('PWA non supporté sur ce navigateur');
        } else {
            document.documentElement.classList.add('pwa-supported');
        }
    }

    /**
     * Gestion de l'événement beforeinstallprompt
     */
    setupBeforeInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            // Empêcher l'affichage automatique
            e.preventDefault();
            
            // Stocker l'événement pour utilisation ultérieure
            this.installPrompt = e;
            
            // Afficher le bouton d'installation personnalisé
            this.showInstallButton();
            
            console.log('PWA installable détectée');
        });
    }

    /**
     * Gestion de l'installation de l'app
     */
    setupAppInstalled() {
        window.addEventListener('appinstalled', (e) => {
            console.log('PWA installée avec succès');
            this.isInstalled = true;
            this.hideInstallButton();
            
            // Afficher un message de succès
            this.showInstallSuccess();
            
            // Analytics
            this.trackInstallation();
            
            // Événement personnalisé
            document.dispatchEvent(new CustomEvent('pwaInstalled'));
        });
    }

    /**
     * Configuration du prompt d'installation
     */
    setupInstallPrompt() {
        // Écouter les clics sur les boutons d'installation
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-pwa-install]')) {
                e.preventDefault();
                this.showInstallPrompt();
            }
        });
    }

    /**
     * Créer le bouton d'installation
     */
    createInstallButton() {
        if (!this.isSupported) return;

        const installButton = document.createElement('div');
        installButton.id = 'pwa-install-banner';
        installButton.className = 'pwa-install-banner d-none';
        installButton.innerHTML = `
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="/images/icons/icon-72x72.png" alt="SIF Burkina" class="pwa-icon">
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Installer SIF Burkina</h6>
                        <p class="mb-0 small text-muted">Accès rapide et utilisation hors ligne</p>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm me-2" data-pwa-install>
                            <i class="fas fa-download me-1"></i>
                            Installer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" data-pwa-dismiss>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Ajouter les écouteurs
        const dismissBtn = installButton.querySelector('[data-pwa-dismiss]');
        dismissBtn.addEventListener('click', () => {
            this.dismissInstallBanner();
        });

        // Insérer dans le DOM
        document.body.appendChild(installButton);

        // Styles CSS
        this.injectStyles();
    }

    /**
     * Afficher le prompt d'installation
     */
    async showInstallPrompt() {
        if (!this.installPrompt) {
            this.showManualInstallInstructions();
            return;
        }

        try {
            // Afficher le prompt natif
            const result = await this.installPrompt.prompt();
            
            console.log('Résultat installation:', result.outcome);
            
            if (result.outcome === 'accepted') {
                this.hideInstallButton();
                this.trackInstallation();
            } else {
                this.trackInstallationDismissed();
            }
            
            // Réinitialiser le prompt
            this.installPrompt = null;
            
        } catch (error) {
            console.error('Erreur prompt installation:', error);
        }
    }

    /**
     * Afficher le bouton d'installation
     */
    showInstallButton() {
        // Vérifier si déjà installé ou dismissed
        if (this.isInstalled || localStorage.getItem('pwa-install-dismissed')) {
            return;
        }

        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.classList.remove('d-none');
            
            // Animation d'entrée
            setTimeout(() => {
                banner.classList.add('show');
            }, 100);
        }
    }

    /**
     * Masquer le bouton d'installation
     */
    hideInstallButton() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.classList.remove('show');
            setTimeout(() => {
                banner.classList.add('d-none');
            }, 300);
        }
    }

    /**
     * Dismiss du banner d'installation
     */
    dismissInstallBanner() {
        this.hideInstallButton();
        localStorage.setItem('pwa-install-dismissed', 'true');
        
        // Auto-réaffichage après 7 jours
        setTimeout(() => {
            localStorage.removeItem('pwa-install-dismissed');
        }, 7 * 24 * 60 * 60 * 1000);
    }

    /**
     * Instructions d'installation manuelle
     */
    showManualInstallInstructions() {
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        
        let instructions = '';
        
        if (isIOS && isSafari) {
            instructions = `
                <strong>Installation sur iOS Safari :</strong>
                <ol class="mt-2 mb-0">
                    <li>Appuyez sur le bouton <i class="fas fa-share"></i> (Partager)</li>
                    <li>Sélectionnez "Sur l'écran d'accueil"</li>
                    <li>Appuyez sur "Ajouter"</li>
                </ol>
            `;
        } else if (navigator.userAgent.includes('Chrome')) {
            instructions = `
                <strong>Installation sur Chrome :</strong>
                <ol class="mt-2 mb-0">
                    <li>Cliquez sur le menu <i class="fas fa-ellipsis-v"></i> (trois points)</li>
                    <li>Sélectionnez "Installer SIF Burkina"</li>
                    <li>Confirmez l'installation</li>
                </ol>
            `;
        } else {
            instructions = `
                <strong>Installation :</strong>
                <p class="mt-2 mb-0">Consultez les paramètres de votre navigateur pour installer cette application.</p>
            `;
        }

        // Afficher modal avec instructions
        this.showModal('Installation PWA', instructions);
    }

    /**
     * Afficher le message de succès d'installation
     */
    showInstallSuccess() {
        if (window.Toast) {
            window.Toast.show('Application installée avec succès ! 🎉', 'success');
        }
        
        // Afficher les fonctionnalités disponibles
        setTimeout(() => {
            this.showInstalledFeatures();
        }, 2000);
    }

    /**
     * Afficher les fonctionnalités de l'app installée
     */
    showInstalledFeatures() {
        const features = `
            <div class="text-center mb-3">
                <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                <h5>Application installée !</h5>
            </div>
            <div class="row">
                <div class="col-6 text-center mb-3">
                    <i class="fas fa-wifi-slash text-success"></i>
                    <small class="d-block mt-1">Hors ligne</small>
                </div>
                <div class="col-6 text-center mb-3">
                    <i class="fas fa-rocket text-success"></i>
                    <small class="d-block mt-1">Plus rapide</small>
                </div>
                <div class="col-6 text-center">
                    <i class="fas fa-bell text-success"></i>
                    <small class="d-block mt-1">Notifications</small>
                </div>
                <div class="col-6 text-center">
                    <i class="fas fa-home text-success"></i>
                    <small class="d-block mt-1">Écran d'accueil</small>
                </div>
            </div>
        `;
        
        this.showModal('Fonctionnalités disponibles', features, false);
    }

    /**
     * Vérifier si l'app est installée
     */
    checkIfInstalled() {
        // Méthode 1: Vérifier le display mode
        if (window.matchMedia('(display-mode: standalone)').matches) {
            this.isInstalled = true;
            document.documentElement.classList.add('pwa-installed');
        }

        // Méthode 2: Vérifier navigator.standalone (iOS)
        if (navigator.standalone === true) {
            this.isInstalled = true;
            document.documentElement.classList.add('pwa-installed');
        }

        // Méthode 3: Vérifier le user agent
        if (window.matchMedia('(display-mode: minimal-ui)').matches) {
            document.documentElement.classList.add('pwa-minimal-ui');
        }
    }

    /**
     * Gestion des URLs et deep linking
     */
    setupUrlHandling() {
        // Gérer les protocoles personnalisés
        window.addEventListener('message', (e) => {
            if (e.data.type === 'PWA_URL_HANDLER') {
                this.handleCustomUrl(e.data.url);
            }
        });
    }

    /**
     * Gérer les URLs personnalisées
     */
    handleCustomUrl(url) {
        console.log('URL personnalisée reçue:', url);
        
        // Parser l'URL et rediriger
        try {
            const urlObj = new URL(url);
            const action = urlObj.searchParams.get('action');
            
            switch (action) {
                case 'cotisation':
                    window.location.href = '/adherent/cotisations';
                    break;
                case 'notification':
                    window.location.href = '/adherent/notifications';
                    break;
                default:
                    window.location.href = '/adherent/dashboard';
            }
        } catch (error) {
            console.error('URL invalide:', error);
        }
    }

    /**
     * Configuration des raccourcis
     */
    setupShortcuts() {
        // Écouter les raccourcis clavier PWA
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case '1':
                        e.preventDefault();
                        window.location.href = '/adherent/dashboard';
                        break;
                    case '2':
                        e.preventDefault();
                        window.location.href = '/adherent/cotisations';
                        break;
                    case '3':
                        e.preventDefault();
                        window.location.href = '/adherent/profil';
                        break;
                }
            }
        });
    }

    /**
     * Analytics et tracking
     */
    trackInstallation() {
        // Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_install', {
                event_category: 'PWA',
                event_label: 'Installation réussie'
            });
        }

        // Analytics personnalisée
        fetch('/api/v1/analytics/pwa-install', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                event: 'pwa_installed',
                timestamp: Date.now(),
                user_agent: navigator.userAgent,
                viewport: {
                    width: window.innerWidth,
                    height: window.innerHeight
                }
            })
        }).catch(error => console.error('Erreur analytics:', error));
    }

    /**
     * Track installation dismissed
     */
    trackInstallationDismissed() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_install_dismissed', {
                event_category: 'PWA',
                event_label: 'Installation refusée'
            });
        }
    }

    /**
     * Afficher une modal
     */
    showModal(title, content, showCloseButton = true) {
        // Créer la modal si elle n'existe pas
        let modal = document.getElementById('pwa-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'pwa-modal';
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            ${showCloseButton ? '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' : ''}
                        </div>
                        <div class="modal-body"></div>
                        ${showCloseButton ? '' : '<div class="modal-footer"><button type="button" class="btn btn-primary" data-bs-dismiss="modal">Compris</button></div>'}
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Mettre à jour le contenu
        modal.querySelector('.modal-title').textContent = title;
        modal.querySelector('.modal-body').innerHTML = content;

        // Afficher
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    /**
     * Injecter les styles CSS
     */
    injectStyles() {
        if (document.getElementById('pwa-styles')) return;

        const style = document.createElement('style');
        style.id = 'pwa-styles';
        style.textContent = `
            .pwa-install-banner {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                z-index: 1050;
                transform: translateY(-100%);
                transition: transform 0.3s ease;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .pwa-install-banner.show {
                transform: translateY(0);
            }
            
            .pwa-install-banner .pwa-icon {
                width: 40px;
                height: 40px;
                border-radius: 8px;
            }
            
            .pwa-install-banner .btn {
                border: 2px solid rgba(255,255,255,0.2);
            }
            
            .pwa-install-banner .btn-primary {
                background: rgba(255,255,255,0.2);
                border-color: rgba(255,255,255,0.3);
            }
            
            .pwa-install-banner .btn-primary:hover {
                background: rgba(255,255,255,0.3);
                border-color: rgba(255,255,255,0.4);
            }
            
            /* Ajustements pour PWA installée */
            .pwa-installed body {
                padding-top: 0 !important;
            }
            
            .pwa-installed .navbar {
                border-radius: 0;
            }
            
            /* Masquer certains éléments en mode PWA */
            .pwa-installed .browser-only {
                display: none !important;
            }
            
            /* Styles spécifiques iOS */
            @supports (-webkit-touch-callout: none) {
                .pwa-installed {
                    padding-top: env(safe-area-inset-top);
                    padding-bottom: env(safe-area-inset-bottom);
                }
            }
        `;

        document.head.appendChild(style);
    }

    /**
     * Obtenir les informations PWA
     */
    getInfo() {
        return {
            isSupported: this.isSupported,
            isInstalled: this.isInstalled,
            canInstall: !!this.installPrompt,
            displayMode: this.getDisplayMode(),
            standalone: navigator.standalone,
            registration: !!this.registration
        };
    }

    /**
     * Obtenir le mode d'affichage
     */
    getDisplayMode() {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            return 'standalone';
        }
        if (window.matchMedia('(display-mode: minimal-ui)').matches) {
            return 'minimal-ui';
        }
        if (window.matchMedia('(display-mode: fullscreen)').matches) {
            return 'fullscreen';
        }
        return 'browser';
    }

    /**
     * Forcer la mise à jour de l'app
     */
    async updateApp() {
        if (this.registration && this.registration.waiting) {
            // Envoyer un message au SW pour qu'il prenne le contrôle
            this.registration.waiting.postMessage({ action: 'SKIP_WAITING' });
            
            // Recharger après un court délai
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    }
}

// Initialiser automatiquement
const pwaManager = new PWAManager();

// API globale
window.PWA = {
    install: () => pwaManager.showInstallPrompt(),
    getInfo: () => pwaManager.getInfo(),
    update: () => pwaManager.updateApp(),
    isInstalled: () => pwaManager.isInstalled,
    isSupported: () => pwaManager.isSupported
};

// Initialiser les raccourcis après chargement
document.addEventListener('DOMContentLoaded', () => {
    pwaManager.setupShortcuts();
});

export default PWAManager;