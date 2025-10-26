/**
 * Améliorations pour les dropdowns
 */

export function initDropdowns() {
    handleSearchableDropdowns();
    handleMultiSelectDropdowns();
}

function handleSearchableDropdowns() {
    const searchableDropdowns = document.querySelectorAll('[data-searchable-dropdown]');
    
    searchableDropdowns.forEach(dropdown => {
        const input = dropdown.querySelector('input[type="search"]');
        const items = dropdown.querySelectorAll('.dropdown-item');
        
        if (!input || !items.length) return;
        
        input.addEventListener('input', () => {
            const searchTerm = input.value.toLowerCase();
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                
                item.style.display = shouldShow ? 'block' : 'none';
            });
        });
    });
}

function handleMultiSelectDropdowns() {
    const multiSelects = document.querySelectorAll('[data-multi-select]');
    
    multiSelects.forEach(dropdown => {
        const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
        const button = dropdown.querySelector('.dropdown-toggle');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateMultiSelectButton(dropdown, button, checkboxes);
            });
        });
        
        // Initial update
        updateMultiSelectButton(dropdown, button, checkboxes);
    });
}

function updateMultiSelectButton(dropdown, button, checkboxes) {
    const selected = Array.from(checkboxes).filter(cb => cb.checked);
    const placeholder = dropdown.dataset.placeholder || 'Sélectionner...';
    
    if (selected.length === 0) {
        button.textContent = placeholder;
    } else if (selected.length === 1) {
        button.textContent = selected[0].labels[0]?.textContent || selected[0].value;
    } else {
        button.textContent = `${selected.length} sélectionné(s)`;
    }
}