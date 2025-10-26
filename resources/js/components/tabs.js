/**
 * Améliorations pour les tabs avec état persistant
 */

export function initTabs() {
    handlePersistentTabs();
    handleLazyLoadTabs();
}

function handlePersistentTabs() {
    const persistentTabs = document.querySelectorAll('[data-persistent-tabs]');
    
    persistentTabs.forEach(tabContainer => {
        const storageKey = `tab-state-${tabContainer.id || 'default'}`;
        const tabs = tabContainer.querySelectorAll('[data-bs-toggle="tab"]');
        
        // Restaurer l'état sauvegardé
        const savedTab = localStorage.getItem(storageKey);
        if (savedTab) {
            const tabToActivate = tabContainer.querySelector(`[data-bs-target="${savedTab}"]`);
            if (tabToActivate) {
                const bsTab = new bootstrap.Tab(tabToActivate);
                bsTab.show();
            }
        }
        
        // Sauvegarder lors du changement
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                const targetPane = e.target.getAttribute('data-bs-target');
                localStorage.setItem(storageKey, targetPane);
            });
        });
    });
}

function handleLazyLoadTabs() {
    const lazyTabs = document.querySelectorAll('[data-lazy-load]');
    
    lazyTabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', async (e) => {
            const pane = document.querySelector(e.target.getAttribute('data-bs-target'));
            if (!pane || pane.dataset.loaded) return;
            
            const url = tab.dataset.lazyLoad;
            if (!url) return;
            
            // Afficher un loader
            const originalContent = pane.innerHTML;
            pane.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement du contenu...</p>
                </div>
            `;
            
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Erreur de chargement');
                
                const content = await response.text();
                pane.innerHTML = content;
                pane.dataset.loaded = 'true';
                
            } catch (error) {
                pane.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur lors du chargement du contenu.
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="this.closest('.tab-pane').removeAttribute('data-loaded'); this.closest('[data-bs-toggle=tab]').click();">
                            Réessayer
                        </button>
                    </div>
                `;
            }
        });
    });
}