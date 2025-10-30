/**
 * Système de notifications temps réel
 */

let notificationBadges = [];
let pollingInterval = null;
let lastNotificationCount = 0;

export function initRealTimeNotifications() {
    findNotificationBadges();
    startPolling();
    handleNotificationDropdowns();
}

function findNotificationBadges() {
    notificationBadges = Array.from(document.querySelectorAll('[data-notification-badge]'));
}

function startPolling() {
    if (pollingInterval) return;
    
    // Poll toutes les 30 secondes
    pollingInterval = setInterval(fetchNotifications, 30000);
    
    // Fetch initial
    fetchNotifications();
}

async function fetchNotifications() {
    try {
        const response = await fetch('/api/notifications/unread-count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        if (!response.ok) return;
        
        const data = await response.json();
        updateNotificationBadges(data.count, data.notifications);
        
    } catch (error) {
        console.warn('Erreur lors de la récupération des notifications:', error);
    }
}

function updateNotificationBadges(count, notifications = []) {
    notificationBadges.forEach(badge => {
        const currentCount = parseInt(badge.textContent) || 0;
        
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('d-none');
            // N'ajoute pas de classes si badge personnalisé
            if (!badge.classList.contains('notification-badge')) {
                badge.classList.add('badge', 'bg-danger', 'rounded-pill');
            }
            
            // Animation si nouveau
            if (count > lastNotificationCount) {
                badge.classList.add('notification-pulse');
                setTimeout(() => {
                    badge.classList.remove('notification-pulse');
                }, 1000);
            }
        } else {
            badge.textContent = '';
            badge.classList.add('d-none');
        }
    });
    
    // Mettre à jour le titre de la page si nouveau
    if (count > lastNotificationCount && count > 0) {
        updatePageTitle(count);
        showNewNotificationToast(notifications);
    }
    
    lastNotificationCount = count;
}

function updatePageTitle(count) {
    const originalTitle = document.title.replace(/^\(\d+\)\s*/, '');
    document.title = count > 0 ? `(${count}) ${originalTitle}` : originalTitle;
}

function showNewNotificationToast(notifications) {
    if (!notifications || notifications.length === 0) return;
    
    const latest = notifications[0];
    
    import('./toast').then(({ showToast }) => {
        showToast(latest.message, 'info', {
            title: latest.title,
            duration: 7000,
            action: `<a href="/adherent/notifications" class="btn btn-sm btn-outline-light mt-1">Voir toutes</a>`
        });
    });
}

function handleNotificationDropdowns() {
    document.addEventListener('click', async (e) => {
        const dropdown = e.target.closest('[data-notification-dropdown]');
        if (!dropdown) return;
        
        await loadNotificationDropdown(dropdown);
        // Marquer toutes comme lues à l'ouverture de la cloche
        try {
            await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            updateNotificationBadges(0, []);
        } catch (err) {
            // silencieux
        }
    });
}

async function loadNotificationDropdown(dropdown) {
    const content = dropdown.querySelector('.notification-dropdown-content');
    if (!content) return;
    
    // Afficher un loading
    content.innerHTML = '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
    
    try {
        const response = await fetch('/api/notifications/recent', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error('Erreur de chargement');
        
        const data = await response.json();
        renderNotificationDropdown(content, data.notifications);
        
    } catch (error) {
        content.innerHTML = '<div class="text-center p-3 text-danger">Erreur de chargement</div>';
    }
}

function renderNotificationDropdown(container, notifications) {
    if (!notifications || notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center p-4 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">Aucune notification</p>
            </div>
        `;
        return;
    }
    
    const notificationItems = notifications.map(notification => `
        <div class="dropdown-item ${notification.read_at ? '' : 'notification-unread'}" 
             data-notification-id="${notification.id}">
            <div class="d-flex">
                <div class="flex-shrink-0 me-2">
                    <i class="${getNotificationIcon(notification.type)} text-${getNotificationColor(notification.type)}"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${notification.title}</h6>
                    <p class="mb-1 small text-muted">${notification.message}</p>
                    <small class="text-muted">${formatDate(notification.created_at)}</small>
                </div>
                ${!notification.read_at ? '<div class="flex-shrink-0"><span class="badge bg-primary rounded-pill">•</span></div>' : ''}
            </div>
        </div>
    `).join('');
    
    container.innerHTML = `
        ${notificationItems}
        <div class="dropdown-divider"></div>
        <div class="dropdown-item-text text-center">
            <a href="/adherent/notifications" class="btn btn-sm btn-outline-primary">Voir toutes les notifications</a>
        </div>
    `;
    
    // Gérer les clics sur les notifications
    container.addEventListener('click', handleNotificationClick);
}

async function handleNotificationClick(e) {
    const item = e.target.closest('[data-notification-id]');
    if (!item) return;
    
    const notificationId = item.dataset.notificationId;
    
    // Marquer comme lue
    try {
        await fetch(`/api/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        item.classList.remove('notification-unread');
        const badge = item.querySelector('.badge');
        if (badge) badge.remove();
        
        // Mettre à jour le compteur
        updateNotificationBadges(lastNotificationCount - 1);
        
    } catch (error) {
        console.warn('Erreur lors du marquage de la notification:', error);
    }
}

function getNotificationIcon(type) {
    const icons = {
        'credit_approved': 'fas fa-check-circle',
        'payment_due': 'fas fa-clock',
        'document_validated': 'fas fa-file-check',
        'account_activated': 'fas fa-user-check',
        'interest_calculated': 'fas fa-coins',
        'default': 'fas fa-bell'
    };
    
    return icons[type] || icons.default;
}

function getNotificationColor(type) {
    const colors = {
        'credit_approved': 'success',
        'payment_due': 'warning',
        'document_validated': 'info',
        'account_activated': 'success',
        'interest_calculated': 'primary',
        'default': 'secondary'
    };
    
    return colors[type] || colors.default;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'À l\'instant';
    if (diff < 3600000) return `${Math.floor(diff / 60000)} min`;
    if (diff < 86400000) return `${Math.floor(diff / 3600000)}h`;
    
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short'
    });
}

// API publique
export function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

export function resumePolling() {
    startPolling();
}

export function forceRefresh() {
    fetchNotifications();
}