import './bootstrap';

// Components
import { initToasts, showToast } from './components/toast';
import { initConfirm, showConfirm } from './components/confirm';
import { initDropdowns } from './components/dropdown';
import { initTabs } from './components/tabs';
import { initLoadingButtons } from './components/loading-button';
import { initAutoDismissAlerts } from './components/alerts';
import { initClipboard } from './components/clipboard';
import { initRealTimeNotifications } from './components/notifications';
import './components/sw-registration';
import './components/form-handler';
import './components/performance-optimizer';
import './components/pwa-manager';
import './components/admin';

// Initialize on DOM ready
window.addEventListener('DOMContentLoaded', () => {
    initAutoDismissAlerts();
    initToasts();
    initConfirm();
    initDropdowns();
    initTabs();
    initLoadingButtons();
    initClipboard();
    initRealTimeNotifications();
});

// Global utilities
window.SIF = {
    showToast: (message, type = 'success') => {
        showToast(message, type);
    },
    confirm: (message, title = 'Confirmation') => {
        return showConfirm(message, title);
    },
    format: {
        money: (amount) => {
            return new Intl.NumberFormat('fr-FR', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount) + ' FCFA';
        },
        date: (date) => {
            return new Date(date).toLocaleDateString('fr-FR');
        }
    }
};
