/**
 * Real-time Data Manager
 * Gestion des données en temps réel avec polling, WebSocket et mise à jour automatique
 */

class RealTimeDataManager {
    constructor() {
        this.pollers = new Map();
        this.subscriptions = new Map();
        this.websocket = null;
        this.reconnectInterval = null;
        this.isConnected = false;
        this.maxReconnectAttempts = 5;
        this.reconnectAttempts = 0;
        
        this.init();
    }

    init() {
        this.setupWebSocket();
        this.setupPolling();
        this.setupEventListeners();
    }

    /**
     * Configuration WebSocket
     */
    setupWebSocket() {
        // Vérifier si Laravel Echo est disponible
        if (window.Echo) {
            this.setupEchoChannels();
        } else {
            // Fallback sur WebSocket natif
            this.connectWebSocket();
        }
    }

    /**
     * Configuration des canaux Laravel Echo
     */
    setupEchoChannels() {
        try {
            // Canal pour les notifications utilisateur
            window.Echo.private(`user.${window.Laravel?.user?.id}`)
                .notification((notification) => {
                    this.handleNotification(notification);
                });

            // Canal pour les mises à jour système
            window.Echo.channel('system-updates')
                .listen('SystemUpdate', (e) => {
                    this.handleSystemUpdate(e);
                });

            // Canal pour les statistiques en temps réel
            window.Echo.channel('statistics')
                .listen('StatisticsUpdate', (e) => {
                    this.updateStatistics(e.data);
                });

            this.isConnected = true;
            console.log('Laravel Echo connecté');
        } catch (error) {
            console.error('Erreur connexion Laravel Echo:', error);
            this.connectWebSocket();
        }
    }

    /**
     * WebSocket natif (fallback)
     */
    connectWebSocket() {
        if (!window.location.protocol.includes('https') && window.location.hostname !== 'localhost') {
            console.warn('WebSocket non disponible sans HTTPS');
            return;
        }

        const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const wsUrl = `${wsProtocol}//${window.location.host}/ws`;

        try {
            this.websocket = new WebSocket(wsUrl);
            
            this.websocket.onopen = () => {
                console.log('WebSocket connecté');
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.clearReconnectInterval();
            };
            
            this.websocket.onmessage = (event) => {
                this.handleWebSocketMessage(JSON.parse(event.data));
            };
            
            this.websocket.onclose = () => {
                console.log('WebSocket déconnecté');
                this.isConnected = false;
                this.scheduleReconnect();
            };
            
            this.websocket.onerror = (error) => {
                console.error('Erreur WebSocket:', error);
                this.isConnected = false;
            };
        } catch (error) {
            console.error('Impossible de créer WebSocket:', error);
        }
    }

    /**
     * Planifier la reconnexion WebSocket
     */
    scheduleReconnect() {
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.warn('Nombre maximum de tentatives de reconnexion atteint');
            return;
        }

        if (this.reconnectInterval) return;

        const delay = Math.pow(2, this.reconnectAttempts) * 1000; // Backoff exponentiel
        
        this.reconnectInterval = setTimeout(() => {
            this.reconnectAttempts++;
            console.log(`Tentative de reconnexion ${this.reconnectAttempts}/${this.maxReconnectAttempts}`);
            this.connectWebSocket();
            this.reconnectInterval = null;
        }, delay);
    }

    clearReconnectInterval() {
        if (this.reconnectInterval) {
            clearTimeout(this.reconnectInterval);
            this.reconnectInterval = null;
        }
    }

    /**
     * Configuration du polling pour les données
     */
    setupPolling() {
        // Polling automatique pour les éléments avec data-poll
        document.querySelectorAll('[data-poll]').forEach(element => {
            const url = element.dataset.poll;
            const interval = parseInt(element.dataset.pollInterval) || 30000; // 30s par défaut
            const target = element.dataset.pollTarget || element;
            
            this.startPolling(url, interval, (data) => {
                this.updateElement(target, data);
            });
        });
    }

    /**
     * Démarrer le polling d'une URL
     */
    startPolling(url, interval, callback, options = {}) {
        const pollerId = this.generateId();
        
        const poller = {
            url: url,
            interval: interval,
            callback: callback,
            options: options,
            timer: null,
            isActive: true
        };

        const poll = async () => {
            if (!poller.isActive) return;

            try {
                // Utiliser le cache si hors ligne
                let data;
                if (navigator.onLine) {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        }
                    });
                    
                    if (response.ok) {
                        data = await response.json();
                        
                        // Mettre en cache si OfflineStorage disponible
                        if (window.OfflineStorage) {
                            await window.OfflineStorage.cacheAPIResponse(url, data, interval);
                        }
                    } else {
                        throw new Error(`HTTP ${response.status}`);
                    }
                } else if (window.OfflineStorage) {
                    // Récupérer du cache hors ligne
                    data = await window.OfflineStorage.getCachedAPIResponse(url);
                    if (!data) return; // Pas de données en cache
                }

                if (data) {
                    callback(data);
                }
            } catch (error) {
                console.error('Erreur polling:', url, error);
                
                // Essayer le cache en cas d'erreur
                if (window.OfflineStorage) {
                    const cachedData = await window.OfflineStorage.getCachedAPIResponse(url);
                    if (cachedData) {
                        callback(cachedData);
                    }
                }
            }

            // Programmer le prochain poll
            if (poller.isActive) {
                poller.timer = setTimeout(poll, interval);
            }
        };

        // Démarrer immédiatement
        poll();
        
        this.pollers.set(pollerId, poller);
        return pollerId;
    }

    /**
     * Arrêter le polling
     */
    stopPolling(pollerId) {
        const poller = this.pollers.get(pollerId);
        if (poller) {
            poller.isActive = false;
            if (poller.timer) {
                clearTimeout(poller.timer);
            }
            this.pollers.delete(pollerId);
        }
    }

    /**
     * S'abonner à des mises à jour de données
     */
    subscribe(channel, callback, options = {}) {
        const subscriptionId = this.generateId();
        
        this.subscriptions.set(subscriptionId, {
            channel: channel,
            callback: callback,
            options: options
        });

        // Si WebSocket/Echo disponible, s'abonner
        if (window.Echo) {
            try {
                window.Echo.channel(channel).listen('.update', callback);
            } catch (error) {
                console.error('Erreur abonnement Echo:', error);
            }
        }

        return subscriptionId;
    }

    /**
     * Se désabonner
     */
    unsubscribe(subscriptionId) {
        const subscription = this.subscriptions.get(subscriptionId);
        if (subscription && window.Echo) {
            try {
                window.Echo.leave(subscription.channel);
            } catch (error) {
                console.error('Erreur désabonnement:', error);
            }
        }
        
        this.subscriptions.delete(subscriptionId);
    }

    /**
     * Mettre à jour un élément du DOM
     */
    updateElement(target, data) {
        let element;
        
        if (typeof target === 'string') {
            element = document.querySelector(target);
        } else if (target instanceof Element) {
            element = target;
        } else {
            return;
        }

        if (!element) return;

        const updateType = element.dataset.updateType || 'content';
        
        switch (updateType) {
            case 'content':
                if (data.html) {
                    element.innerHTML = data.html;
                } else if (data.text) {
                    element.textContent = data.text;
                }
                break;
                
            case 'attribute':
                const attr = element.dataset.updateAttribute;
                if (attr && data[attr]) {
                    element.setAttribute(attr, data[attr]);
                }
                break;
                
            case 'class':
                if (data.addClass) {
                    element.classList.add(...data.addClass.split(' '));
                }
                if (data.removeClass) {
                    element.classList.remove(...data.removeClass.split(' '));
                }
                break;
                
            case 'style':
                if (data.style) {
                    Object.assign(element.style, data.style);
                }
                break;
                
            case 'value':
                if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                    element.value = data.value || '';
                }
                break;
                
            case 'replace':
                if (data.html) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    const newElement = tempDiv.firstElementChild;
                    if (newElement) {
                        element.parentNode.replaceChild(newElement, element);
                    }
                }
                break;
                
            default:
                console.warn('Type de mise à jour non supporté:', updateType);
        }

        // Animation de mise à jour
        this.animateUpdate(element);
        
        // Événement personnalisé
        element.dispatchEvent(new CustomEvent('dataUpdated', {
            detail: { data, updateType }
        }));
    }

    /**
     * Animation de mise à jour
     */
    animateUpdate(element) {
        element.classList.add('data-updated');
        
        setTimeout(() => {
            element.classList.remove('data-updated');
        }, 1000);
    }

    /**
     * Gestion des notifications
     */
    handleNotification(notification) {
        console.log('Notification reçue:', notification);
        
        // Afficher avec le système de toast
        if (window.Toast) {
            window.Toast.show(notification.message, notification.type || 'info');
        }
        
        // Mettre à jour les compteurs de notifications
        this.updateNotificationBadge();
        
        // Événement global
        document.dispatchEvent(new CustomEvent('realtimeNotification', {
            detail: notification
        }));
    }

    /**
     * Gestion des mises à jour système
     */
    handleSystemUpdate(update) {
        console.log('Mise à jour système:', update);
        
        switch (update.type) {
            case 'maintenance':
                this.showMaintenanceNotice(update.data);
                break;
                
            case 'version':
                this.handleVersionUpdate(update.data);
                break;
                
            case 'alert':
                this.showSystemAlert(update.data);
                break;
                
            default:
                console.log('Type de mise à jour système non géré:', update.type);
        }
    }

    /**
     * Mettre à jour les statistiques
     */
    updateStatistics(stats) {
        Object.keys(stats).forEach(key => {
            const elements = document.querySelectorAll(`[data-stat="${key}"]`);
            elements.forEach(element => {
                this.updateElement(element, { text: stats[key] });
            });
        });
    }

    /**
     * Gestion des messages WebSocket
     */
    handleWebSocketMessage(message) {
        switch (message.type) {
            case 'notification':
                this.handleNotification(message.data);
                break;
                
            case 'update':
                if (message.target) {
                    this.updateElement(message.target, message.data);
                }
                break;
                
            case 'statistics':
                this.updateStatistics(message.data);
                break;
                
            case 'reload':
                if (confirm('L\'application a été mise à jour. Recharger maintenant ?')) {
                    window.location.reload();
                }
                break;
                
            default:
                console.log('Message WebSocket non géré:', message);
        }
    }

    /**
     * Mettre à jour le badge de notifications
     */
    updateNotificationBadge() {
        const badges = document.querySelectorAll('.notification-badge');
        badges.forEach(badge => {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
            badge.classList.remove('d-none');
        });
    }

    /**
     * Afficher notice de maintenance
     */
    showMaintenanceNotice(data) {
        const notice = document.createElement('div');
        notice.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-2';
        notice.style.cssText = 'z-index: 1070; max-width: 500px;';
        notice.innerHTML = `
            <strong><i class="fas fa-tools me-2"></i>Maintenance programmée</strong>
            <p class="mb-2 small">${data.message}</p>
            <small class="text-muted">Prévue le ${new Date(data.scheduled_at).toLocaleString()}</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notice);
    }

    /**
     * Gestion mise à jour version
     */
    handleVersionUpdate(data) {
        if (window.swManager) {
            // Utiliser le système de Service Worker pour la mise à jour
            window.swManager.skipWaiting();
        } else if (data.force) {
            window.location.reload();
        }
    }

    /**
     * Afficher alerte système
     */
    showSystemAlert(data) {
        const alertType = data.level || 'info';
        const alert = document.createElement('div');
        alert.className = `alert alert-${alertType} alert-dismissible fade show`;
        alert.innerHTML = `
            <strong>${data.title || 'Information système'}</strong>
            <p class="mb-0">${data.message}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insérer en haut de la page
        const container = document.querySelector('.container-fluid, .container, main, body');
        if (container) {
            container.insertBefore(alert, container.firstChild);
        }
    }

    /**
     * Configuration des événements
     */
    setupEventListeners() {
        // Reprendre le polling quand on revient en ligne
        window.addEventListener('online', () => {
            console.log('Reprise des mises à jour en temps réel');
            this.pollers.forEach((poller, id) => {
                if (!poller.isActive) {
                    poller.isActive = true;
                    this.startPolling(poller.url, poller.interval, poller.callback, poller.options);
                }
            });
        });

        // Pause le polling hors ligne pour économiser la batterie
        window.addEventListener('offline', () => {
            console.log('Pause des mises à jour temps réel (hors ligne)');
        });

        // Cleanup avant fermeture
        window.addEventListener('beforeunload', () => {
            this.cleanup();
        });
    }

    /**
     * Nettoyage des ressources
     */
    cleanup() {
        this.pollers.forEach((poller, id) => {
            this.stopPolling(id);
        });
        
        this.subscriptions.forEach((subscription, id) => {
            this.unsubscribe(id);
        });
        
        if (this.websocket) {
            this.websocket.close();
        }
        
        this.clearReconnectInterval();
    }

    /**
     * Utilitaires
     */
    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    /**
     * API publique
     */
    getConnectionStatus() {
        return {
            isConnected: this.isConnected,
            activePollers: this.pollers.size,
            subscriptions: this.subscriptions.size,
            reconnectAttempts: this.reconnectAttempts
        };
    }
}

// Styles CSS pour les animations
const styles = `
    .data-updated {
        animation: dataFlash 1s ease-in-out;
    }
    
    @keyframes dataFlash {
        0% { background-color: rgba(40, 167, 69, 0.2); }
        50% { background-color: rgba(40, 167, 69, 0.4); }
        100% { background-color: transparent; }
    }
    
    .notification-badge {
        animation: pulse 0.5s ease-in-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
`;

// Injecter les styles
if (!document.getElementById('realtime-styles')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'realtime-styles';
    styleSheet.textContent = styles;
    document.head.appendChild(styleSheet);
}

// Initialiser automatiquement
const realTimeManager = new RealTimeDataManager();

// API globale
window.RealTimeData = {
    startPolling: (url, interval, callback, options) => {
        return realTimeManager.startPolling(url, interval, callback, options);
    },
    
    stopPolling: (pollerId) => {
        realTimeManager.stopPolling(pollerId);
    },
    
    subscribe: (channel, callback, options) => {
        return realTimeManager.subscribe(channel, callback, options);
    },
    
    unsubscribe: (subscriptionId) => {
        realTimeManager.unsubscribe(subscriptionId);
    },
    
    updateElement: (target, data) => {
        realTimeManager.updateElement(target, data);
    },
    
    getStatus: () => {
        return realTimeManager.getConnectionStatus();
    }
};

export default RealTimeDataManager;