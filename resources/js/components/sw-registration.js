/**
 * Service Worker Registration
 * Enregistrement et gestion du Service Worker côté client
 */

class ServiceWorkerManager {
    constructor() {
        this.swPath = '/sw.js';
        this.registration = null;
        this.isOnline = navigator.onLine;
        
        this.init();
        this.setupEventListeners();
    }

    /**
     * Initialisation du Service Worker
     */
    async init() {
        // Vérifier si les Service Workers sont supportés
        if (!('serviceWorker' in navigator)) {
            console.warn('Service Workers non supportés par ce navigateur');
            return;
        }

        try {
            await this.registerServiceWorker();
            this.setupUpdateListener();
            this.setupNotifications();
        } catch (error) {
            console.error('Erreur lors de l\'initialisation du Service Worker:', error);
        }
    }

    /**
     * Enregistrer le Service Worker
     */
    async registerServiceWorker() {
        try {
            this.registration = await navigator.serviceWorker.register(this.swPath, {
                scope: '/',
                updateViaCache: 'none'
            });

            console.log('Service Worker enregistré avec succès:', this.registration.scope);

            // Vérifier les mises à jour
            this.registration.addEventListener('updatefound', () => {
                this.handleUpdateFound();
            });

            // Vérifier immédiatement les mises à jour
            this.registration.update();

            return this.registration;
        } catch (error) {
            console.error('Échec de l\'enregistrement du Service Worker:', error);
            throw error;
        }
    }

    /**
     * Configurer les écouteurs d'événements
     */
    setupEventListeners() {
        // Événements de connectivité
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.handleOnlineStatusChange();
            this.showToast('Connexion rétablie', 'success');
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.handleOnlineStatusChange();
            this.showToast('Mode hors ligne activé', 'warning');
        });

        // Événements du Service Worker
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            window.location.reload();
        });

        navigator.serviceWorker.addEventListener('message', (event) => {
            this.handleSWMessage(event.data);
        });
    }

    /**
     * Gérer les nouvelles versions du Service Worker
     */
    handleUpdateFound() {
        const newWorker = this.registration.installing;
        
        if (!newWorker) return;

        console.log('Nouvelle version du Service Worker trouvée');

        newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed') {
                if (navigator.serviceWorker.controller) {
                    // Une nouvelle version est disponible
                    this.showUpdateAvailableNotification();
                } else {
                    // Service Worker installé pour la première fois
                    console.log('Service Worker installé pour la première fois');
                    this.showToast('Application prête pour utilisation hors ligne', 'success');
                }
            }
        });
    }

    /**
     * Afficher notification de mise à jour disponible
     */
    showUpdateAvailableNotification() {
        const message = 'Une nouvelle version de l\'application est disponible. Actualiser maintenant ?';
        
        if (window.confirm && confirm(message)) {
            this.skipWaiting();
        } else {
            // Afficher une notification persistante
            this.showUpdateBanner();
        }
    }

    /**
     * Afficher bannière de mise à jour
     */
    showUpdateBanner() {
        // Créer une bannière de mise à jour
        const banner = document.createElement('div');
        banner.id = 'sw-update-banner';
        banner.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-2';
        banner.style.cssText = 'z-index: 1060; max-width: 400px;';
        banner.innerHTML = `
            <strong>Mise à jour disponible</strong>
            <p class="mb-2 small">Une nouvelle version de l'application est prête.</p>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary" onclick="window.swManager?.skipWaiting()">
                    Actualiser
                </button>
                <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="alert">
                    Plus tard
                </button>
            </div>
        `;

        document.body.appendChild(banner);

        // Auto-masquer après 10 secondes
        setTimeout(() => {
            if (banner.parentNode) {
                banner.remove();
            }
        }, 10000);
    }

    /**
     * Passer à la nouvelle version
     */
    skipWaiting() {
        if (this.registration && this.registration.waiting) {
            this.registration.waiting.postMessage({ action: 'SKIP_WAITING' });
        }
    }

    /**
     * Configurer les notifications push
     */
    async setupNotifications() {
        if (!('Notification' in window) || !('PushManager' in window)) {
            console.warn('Notifications push non supportées');
            return;
        }

        // Demander la permission pour les notifications
        if (Notification.permission === 'default') {
            const permission = await Notification.requestPermission();
            console.log('Permission notifications:', permission);
        }
    }

    /**
     * Gérer les changements de statut en ligne/hors ligne
     */
    handleOnlineStatusChange() {
        // Mettre à jour l'interface utilisateur
        const statusIndicator = document.getElementById('connection-status');
        if (statusIndicator) {
            statusIndicator.textContent = this.isOnline ? 'En ligne' : 'Hors ligne';
            statusIndicator.className = this.isOnline ? 'text-success' : 'text-warning';
        }

        // Synchroniser les données si reconnecté
        if (this.isOnline && this.registration) {
            this.syncOfflineData();
        }
    }

    /**
     * Synchroniser les données hors ligne
     */
    async syncOfflineData() {
        if (!this.registration || !this.registration.active) return;

        try {
            // Déclencher la synchronisation en arrière-plan
            if ('sync' in this.registration) {
                await this.registration.sync.register('background-sync');
            }
            
            // Ou envoyer un message au SW
            this.postMessage({ action: 'SYNC_DATA' });
        } catch (error) {
            console.error('Erreur lors de la synchronisation:', error);
        }
    }

    /**
     * Envoyer un message au Service Worker
     */
    async postMessage(message) {
        if (!this.registration || !this.registration.active) {
            console.warn('Service Worker non actif');
            return;
        }

        return new Promise((resolve, reject) => {
            const messageChannel = new MessageChannel();
            
            messageChannel.port1.onmessage = (event) => {
                resolve(event.data);
            };

            this.registration.active.postMessage(message, [messageChannel.port2]);
            
            // Timeout après 5 secondes
            setTimeout(() => reject(new Error('Message timeout')), 5000);
        });
    }

    /**
     * Gérer les messages du Service Worker
     */
    handleSWMessage(data) {
        switch (data.type) {
            case 'CACHE_UPDATED':
                console.log('Cache mis à jour:', data.url);
                break;
                
            case 'OFFLINE_READY':
                this.showToast('Application prête hors ligne', 'success');
                break;
                
            case 'SYNC_COMPLETE':
                this.showToast('Données synchronisées', 'success');
                break;
                
            default:
                console.log('Message SW:', data);
        }
    }

    /**
     * Afficher un toast
     */
    showToast(message, type = 'info') {
        if (window.Toast) {
            window.Toast.show(message, type);
        } else {
            console.log(`[${type.toUpperCase()}] ${message}`);
        }
    }

    /**
     * Obtenir la version du Service Worker
     */
    async getVersion() {
        try {
            const response = await this.postMessage({ action: 'GET_VERSION' });
            return response.version;
        } catch (error) {
            console.error('Erreur lors de la récupération de la version:', error);
            return null;
        }
    }

    /**
     * Vider le cache
     */
    async clearCache() {
        try {
            await this.postMessage({ action: 'CLEAR_CACHE' });
            this.showToast('Cache vidé avec succès', 'success');
            return true;
        } catch (error) {
            console.error('Erreur lors du vidage du cache:', error);
            this.showToast('Erreur lors du vidage du cache', 'error');
            return false;
        }
    }

    /**
     * Mettre en cache des URLs spécifiques
     */
    async cacheUrls(urls) {
        try {
            await this.postMessage({ action: 'CACHE_URLS', urls });
            console.log('URLs mises en cache:', urls);
            return true;
        } catch (error) {
            console.error('Erreur lors de la mise en cache:', error);
            return false;
        }
    }

    /**
     * Vérifier l'état du Service Worker
     */
    getStatus() {
        return {
            isSupported: 'serviceWorker' in navigator,
            isRegistered: !!this.registration,
            isActive: !!(this.registration && this.registration.active),
            isOnline: this.isOnline,
            scope: this.registration ? this.registration.scope : null
        };
    }
}

// Initialiser automatiquement
let swManager = null;

// Attendre que le DOM soit chargé
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSW);
} else {
    initSW();
}

function initSW() {
    swManager = new ServiceWorkerManager();
    
    // Rendre disponible globalement pour les actions utilisateur
    window.swManager = swManager;
    
    // Ajouter un indicateur de statut de connexion si pas présent
    addConnectionStatus();
}

function addConnectionStatus() {
    if (document.getElementById('connection-status')) return;
    
    const statusEl = document.createElement('span');
    statusEl.id = 'connection-status';
    statusEl.className = navigator.onLine ? 'text-success' : 'text-warning';
    statusEl.textContent = navigator.onLine ? 'En ligne' : 'Hors ligne';
    statusEl.style.fontSize = '0.8rem';
    
    // Ajouter dans la navbar si elle existe
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        const statusContainer = document.createElement('div');
        statusContainer.className = 'navbar-text me-3';
        statusContainer.appendChild(statusEl);
        navbar.appendChild(statusContainer);
    }
}

export default ServiceWorkerManager;