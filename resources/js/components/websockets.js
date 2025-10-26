/**
 * WebSockets avec Pusher pour notifications temps réel
 */

import { showToast } from './toast';

let pusher = null;
let channels = new Map();
let connectionStatus = 'disconnected';

export function initWebSockets() {
    if (typeof window.Pusher === 'undefined') {
        console.warn('Pusher not loaded. Real-time features disabled.');
        return;
    }

    pusher = new Pusher(window.pusherConfig.key, {
        cluster: window.pusherConfig.cluster,
        encrypted: true,
        enabledTransports: ['ws', 'wss'],
        disabledTransports: ['xhr_polling', 'xhr_streaming'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }
    });

    setupConnectionEvents();
    subscribeToUserChannels();
    setupGlobalListeners();
}

/**
 * Configuration des événements de connexion
 */
function setupConnectionEvents() {
    pusher.connection.bind('connected', () => {
        connectionStatus = 'connected';
        console.log('WebSocket connected');
        updateConnectionStatus(true);
    });

    pusher.connection.bind('disconnected', () => {
        connectionStatus = 'disconnected';
        console.log('WebSocket disconnected');
        updateConnectionStatus(false);
    });

    pusher.connection.bind('error', (error) => {
        console.error('WebSocket error:', error);
        connectionStatus = 'error';
        updateConnectionStatus(false);
    });
}

/**
 * Souscription aux canaux utilisateur
 */
function subscribeToUserChannels() {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    if (!userId) return;

    // Canal personnel de l'utilisateur
    const personalChannel = pusher.subscribe(`private-user.${userId}`);
    channels.set('personal', personalChannel);

    personalChannel.bind('notification', (data) => {
        handleNotification(data);
    });

    personalChannel.bind('account-update', (data) => {
        handleAccountUpdate(data);
    });

    // Canal des mises à jour générales
    const generalChannel = pusher.subscribe('general-updates');
    channels.set('general', generalChannel);

    generalChannel.bind('system-announcement', (data) => {
        handleSystemAnnouncement(data);
    });

    // Canal spécifique au rôle
    const userRole = document.querySelector('meta[name="user-role"]')?.content;
    if (userRole) {
        const roleChannel = pusher.subscribe(`private-role.${userRole}`);
        channels.set('role', roleChannel);

        roleChannel.bind('role-notification', (data) => {
            handleRoleNotification(data);
        });
    }
}

/**
 * Configuration des listeners globaux
 */
function setupGlobalListeners() {
    // Écouter les changements de page pour réabonner si nécessaire
    window.addEventListener('beforeunload', () => {
        if (pusher) {
            pusher.disconnect();
        }
    });

    // Gérer la visibilité de la page
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            // Réduire la fréquence des événements quand la page n'est pas visible
            if (pusher && connectionStatus === 'connected') {
                pusher.connection.strategy.timeline.debug = false;
            }
        } else {
            // Forcer la reconnexion si nécessaire
            if (connectionStatus === 'disconnected') {
                reconnect();
            }
        }
    });
}

/**
 * Gestionnaires d'événements
 */
function handleNotification(data) {
    // Mettre à jour les badges de notification
    const badges = document.querySelectorAll('[data-notification-badge]');
    badges.forEach(badge => {
        const currentCount = parseInt(badge.textContent) || 0;
        badge.textContent = currentCount + 1;
        badge.classList.remove('d-none');
        badge.classList.add('notification-pulse');
    });

    // Afficher le toast
    showToast(data.message, data.type || 'info', {
        title: data.title,
        duration: 8000
    });

    // Mettre à jour le titre de la page
    updatePageTitle();

    // Son de notification (optionnel)
    playNotificationSound();
}

function handleAccountUpdate(data) {
    switch (data.type) {
        case 'profile_updated':
            showToast('Votre profil a été mis à jour', 'success');
            break;
        case 'account_status_changed':
            showToast(`Statut du compte: ${data.new_status}`, 'info');
            // Recharger la page si le statut change significativement
            if (['suspended', 'activated'].includes(data.new_status)) {
                setTimeout(() => location.reload(), 3000);
            }
            break;
        case 'security_alert':
            showToast('Alerte de sécurité: ' + data.message, 'error', {
                persistent: true
            });
            break;
    }
}

function handleSystemAnnouncement(data) {
    showToast(data.message, 'info', {
        title: 'Annonce système',
        duration: 10000,
        persistent: data.critical || false
    });
}

function handleRoleNotification(data) {
    // Notifications spécifiques au rôle (admin, agent, etc.)
    showToast(data.message, data.type || 'info', {
        title: data.title || 'Notification',
        duration: 7000
    });
}

/**
 * Utilitaires
 */
function updateConnectionStatus(isConnected) {
    const statusIndicator = document.querySelector('[data-connection-status]');
    if (statusIndicator) {
        statusIndicator.className = isConnected ? 
            'badge bg-success' : 'badge bg-danger';
        statusIndicator.textContent = isConnected ? 'En ligne' : 'Hors ligne';
    }
}

function updatePageTitle() {
    const badges = document.querySelectorAll('[data-notification-badge]');
    let totalNotifications = 0;
    
    badges.forEach(badge => {
        totalNotifications += parseInt(badge.textContent) || 0;
    });

    const baseTitle = document.title.replace(/^\(\d+\)\s*/, '');
    document.title = totalNotifications > 0 ? 
        `(${totalNotifications}) ${baseTitle}` : baseTitle;
}

function playNotificationSound() {
    try {
        const audio = new Audio('/sounds/notification.mp3');
        audio.volume = 0.3;
        audio.play().catch(() => {
            // Ignore les erreurs de lecture audio (autoplay policy)
        });
    } catch (error) {
        // Son de notification non disponible
    }
}

function reconnect() {
    if (pusher && connectionStatus !== 'connected') {
        console.log('Attempting to reconnect WebSocket...');
        pusher.connect();
    }
}

/**
 * API publique
 */
export function subscribeToChannel(channelName, eventHandlers = {}) {
    if (!pusher) {
        console.warn('Pusher not initialized');
        return null;
    }

    const channel = pusher.subscribe(channelName);
    channels.set(channelName, channel);

    // Lier les gestionnaires d'événements
    Object.entries(eventHandlers).forEach(([eventName, handler]) => {
        channel.bind(eventName, handler);
    });

    return channel;
}

export function unsubscribeFromChannel(channelName) {
    const channel = channels.get(channelName);
    if (channel) {
        pusher.unsubscribe(channelName);
        channels.delete(channelName);
    }
}

export function sendChannelEvent(channelName, eventName, data) {
    // Pour envoyer des événements client-à-client (si activé côté serveur)
    const channel = channels.get(channelName);
    if (channel) {
        channel.trigger(`client-${eventName}`, data);
    }
}

export function getConnectionStatus() {
    return connectionStatus;
}

export function forceReconnect() {
    reconnect();
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', () => {
    // Vérifier si les WebSockets sont configurés
    if (window.pusherConfig && window.pusherConfig.enabled) {
        initWebSockets();
    }
});

// Export pour usage global
window.WebSockets = {
    subscribe: subscribeToChannel,
    unsubscribe: unsubscribeFromChannel,
    reconnect: forceReconnect,
    status: getConnectionStatus
};