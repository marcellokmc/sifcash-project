/**
 * Composants spécialisés pour l'administration
 */

import { showToast } from './toast';

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    initDataTables();
    initBulkActions();
    initInlineEdit();
    initStatusToggles();
    initSearchFilters();
    initGlobalSearch();
});

/**
 * DataTables avec configuration optimisée
 */
function initDataTables() {
    const tables = document.querySelectorAll('[data-table="advanced"]');
    
    tables.forEach(table => {
        const config = {
            pageLength: 25,
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm'
                }
            ]
        };
        
        // Configuration spécifique par table
        const tableType = table.dataset.tableType;
        if (tableType === 'financial') {
            config.columnDefs = [
                {
                    targets: [-1, -2], // Dernières colonnes (montants)
                    render: function(data, type, row) {
                        if (type === 'display' && data) {
                            return window.SIF.format.money(data);
                        }
                        return data;
                    }
                }
            ];
        }
        
        // Initialiser DataTable
        const dt = $(table).DataTable(config);
        
        // Export buttons
        dt.buttons().container()
            .appendTo(`${table.id}_wrapper .col-md-6:eq(0)`);
    });
}

/**
 * Actions en lot
 */
function initBulkActions() {
    const bulkContainers = document.querySelectorAll('[data-bulk-actions]');
    
    bulkContainers.forEach(container => {
        const selectAllCheckbox = container.querySelector('[data-select-all]');
        const itemCheckboxes = container.querySelectorAll('[data-bulk-item]');
        const actionButtons = container.querySelectorAll('[data-bulk-action]');
        
        // Select all/none
        selectAllCheckbox?.addEventListener('change', () => {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkActionState(actionButtons, getSelectedItems(itemCheckboxes));
        });
        
        // Individual selection
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateBulkActionState(actionButtons, getSelectedItems(itemCheckboxes));
                updateSelectAllState(selectAllCheckbox, itemCheckboxes);
            });
        });
        
        // Bulk actions
        actionButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const selectedIds = getSelectedItems(itemCheckboxes);
                if (selectedIds.length === 0) {
                    showToast('Aucun élément sélectionné', 'warning');
                    return;
                }
                
                const action = button.dataset.bulkAction;
                const confirmMessage = button.dataset.confirmMessage || 
                                    `Êtes-vous sûr de vouloir ${action} ${selectedIds.length} élément(s) ?`;
                
                const confirmed = await window.SIF.confirm(confirmMessage);
                if (!confirmed) return;
                
                await executeBulkAction(action, selectedIds, button);
            });
        });
    });
}

function getSelectedItems(checkboxes) {
    return Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
}

function updateBulkActionState(buttons, selectedItems) {
    buttons.forEach(button => {
        button.disabled = selectedItems.length === 0;
        const countSpan = button.querySelector('[data-count]');
        if (countSpan) {
            countSpan.textContent = selectedItems.length;
        }
    });
}

function updateSelectAllState(selectAllCheckbox, itemCheckboxes) {
    if (!selectAllCheckbox) return;
    
    const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
    const totalCount = itemCheckboxes.length;
    
    selectAllCheckbox.checked = checkedCount === totalCount;
    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
}

async function executeBulkAction(action, selectedIds, button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
    button.disabled = true;
    
    try {
        const response = await fetch(button.dataset.url || `/admin/bulk/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ids: selectedIds, action })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showToast(data.message || `${action} effectué avec succès`, 'success');
            // Recharger la page ou mettre à jour la table
            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Erreur lors de l\'action');
        }
        
    } catch (error) {
        showToast(`Erreur: ${error.message}`, 'error');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

/**
 * Édition en ligne
 */
function initInlineEdit() {
    const editableElements = document.querySelectorAll('[data-inline-edit]');
    
    editableElements.forEach(element => {
        element.addEventListener('dblclick', () => {
            makeEditable(element);
        });
    });
}

function makeEditable(element) {
    const originalValue = element.textContent;
    const inputType = element.dataset.inputType || 'text';
    
    // Créer l'input
    const input = document.createElement(inputType === 'textarea' ? 'textarea' : 'input');
    input.type = inputType;
    input.value = originalValue;
    input.className = 'form-control form-control-sm';
    
    if (inputType === 'textarea') {
        input.rows = 2;
    }
    
    // Remplacer l'élément
    element.parentNode.insertBefore(input, element);
    element.style.display = 'none';
    input.focus();
    
    // Fonction de sauvegarde
    const saveEdit = async () => {
        if (input.value === originalValue) {
            cancelEdit();
            return;
        }
        
        try {
            const response = await fetch(element.dataset.updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    field: element.dataset.field,
                    value: input.value
                })
            });
            
            if (response.ok) {
                element.textContent = input.value;
                showToast('Modifié avec succès', 'success');
            } else {
                throw new Error('Erreur de sauvegarde');
            }
        } catch (error) {
            showToast('Erreur lors de la sauvegarde', 'error');
        }
        
        cancelEdit();
    };
    
    const cancelEdit = () => {
        element.style.display = '';
        input.remove();
    };
    
    // Events
    input.addEventListener('blur', saveEdit);
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && inputType !== 'textarea') {
            saveEdit();
        } else if (e.key === 'Escape') {
            cancelEdit();
        }
    });
}

/**
 * Toggle de statut
 */
function initStatusToggles() {
    const toggles = document.querySelectorAll('[data-status-toggle]');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('change', async () => {
            const originalState = !toggle.checked;
            
            try {
                const response = await fetch(toggle.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        active: toggle.checked,
                        field: toggle.dataset.field || 'active'
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Erreur de mise à jour');
                }
                
                const statusText = toggle.closest('tr')?.querySelector('[data-status-text]');
                if (statusText) {
                    statusText.textContent = toggle.checked ? 'Actif' : 'Inactif';
                    statusText.className = toggle.checked ? 'badge bg-success' : 'badge bg-secondary';
                }
                
                showToast('Statut mis à jour', 'success');
                
            } catch (error) {
                toggle.checked = originalState;
                showToast('Erreur lors de la mise à jour', 'error');
            }
        });
    });
}

/**
 * Filtres de recherche avancés
 */
function initSearchFilters() {
    const filterForms = document.querySelectorAll('[data-search-filter]');
    
    filterForms.forEach(form => {
        const inputs = form.querySelectorAll('input, select');
        const clearButton = form.querySelector('[data-clear-filters]');
        
        // Auto-submit avec debounce
        let debounceTimer;
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
        
        // Clear filters
        clearButton?.addEventListener('click', (e) => {
            e.preventDefault();
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            form.submit();
        });
    });
}

/**
 * Recherche globale dans le header
 */
function initGlobalSearch() {
    const searchInput = document.getElementById('globalSearch');
    if (!searchInput) return;
    
    const searchResults = createSearchResultsDropdown(searchInput);
    let debounceTimer;
    
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            searchResults.hide();
            return;
        }
        
        debounceTimer = setTimeout(() => {
            performGlobalSearch(query, searchResults);
        }, 300);
    });
    
    // Fermer les résultats si on clique ailleurs
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.element.contains(e.target)) {
            searchResults.hide();
        }
    });
    
    // Navigation au clavier
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            searchResults.hide();
        }
    });
}

function createSearchResultsDropdown(inputElement) {
    const dropdown = document.createElement('div');
    dropdown.className = 'global-search-results';
    dropdown.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;
        margin-top: 4px;
        display: none;
    `;
    
    inputElement.parentElement.style.position = 'relative';
    inputElement.parentElement.appendChild(dropdown);
    
    return {
        element: dropdown,
        show() {
            dropdown.style.display = 'block';
        },
        hide() {
            dropdown.style.display = 'none';
        },
        setContent(html) {
            dropdown.innerHTML = html;
            this.show();
        },
        setLoading() {
            dropdown.innerHTML = `
                <div style="padding: 1rem; text-align: center;">
                    <i class="fas fa-spinner fa-spin"></i> Recherche en cours...
                </div>
            `;
            this.show();
        }
    };
}

async function performGlobalSearch(query, resultsDropdown) {
    resultsDropdown.setLoading();
    
    try {
        const response = await fetch(`/admin/search?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Erreur de recherche');
        }
        
        const data = await response.json();
        displaySearchResults(data, resultsDropdown);
        
    } catch (error) {
        resultsDropdown.setContent(`
            <div style="padding: 1rem; text-align: center; color: #e53e3e;">
                <i class="fas fa-exclamation-triangle"></i> Erreur lors de la recherche
            </div>
        `);
    }
}

function displaySearchResults(data, resultsDropdown) {
    if (!data.adherents?.length && !data.credits?.length && !data.users?.length) {
        resultsDropdown.setContent(`
            <div style="padding: 1rem; text-align: center; color: #718096;">
                <i class="fas fa-search"></i> Aucun résultat trouvé
            </div>
        `);
        return;
    }
    
    let html = '';
    
    // Adhérents
    if (data.adherents?.length) {
        html += `
            <div style="padding: 0.5rem 1rem; background: #f7fafc; font-weight: 600; font-size: 0.75rem; color: #4a5568;">
                <i class="fas fa-users"></i> ADHÉRENTS
            </div>
        `;
        data.adherents.forEach(adherent => {
            html += `
                <a href="/admin/adherents/${adherent.id}" style="display: block; padding: 0.75rem 1rem; text-decoration: none; color: inherit; border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <div style="font-weight: 500;">${adherent.nom} ${adherent.prenom}</div>
                    <div style="font-size: 0.75rem; color: #718096;">${adherent.numero_adherent || ''}</div>
                </a>
            `;
        });
    }
    
    // Crédits
    if (data.credits?.length) {
        html += `
            <div style="padding: 0.5rem 1rem; background: #f7fafc; font-weight: 600; font-size: 0.75rem; color: #4a5568;">
                <i class="fas fa-file-invoice-dollar"></i> CRÉDITS
            </div>
        `;
        data.credits.forEach(credit => {
            html += `
                <a href="/admin/credits/${credit.id}" style="display: block; padding: 0.75rem 1rem; text-decoration: none; color: inherit; border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <div style="font-weight: 500;">${credit.numero_credit || 'Crédit #' + credit.id}</div>
                    <div style="font-size: 0.75rem; color: #718096;">${credit.adherent_name || ''} - ${credit.montant || ''} FCFA</div>
                </a>
            `;
        });
    }
    
    // Utilisateurs
    if (data.users?.length) {
        html += `
            <div style="padding: 0.5rem 1rem; background: #f7fafc; font-weight: 600; font-size: 0.75rem; color: #4a5568;">
                <i class="fas fa-user-shield"></i> UTILISATEURS
            </div>
        `;
        data.users.forEach(user => {
            html += `
                <a href="/admin/users/${user.id}" style="display: block; padding: 0.75rem 1rem; text-decoration: none; color: inherit; border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <div style="font-weight: 500;">${user.name}</div>
                    <div style="font-size: 0.75rem; color: #718096;">${user.email}</div>
                </a>
            `;
        });
    }
    
    resultsDropdown.setContent(html);
}

// Export des fonctions utilitaires
export {
    initDataTables,
    initBulkActions,
    initInlineEdit,
    initStatusToggles,
    initSearchFilters,
    initGlobalSearch
};
