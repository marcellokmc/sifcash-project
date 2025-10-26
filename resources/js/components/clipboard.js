/**
 * Fonctionnalités de copier/coller
 */

export function initClipboard() {
    handleClipboardButtons();
}

function handleClipboardButtons() {
    document.addEventListener('click', async (e) => {
        const button = e.target.closest('[data-clipboard]');
        if (!button) return;

        const text = button.dataset.clipboard || button.dataset.clipboardTarget && 
                     document.querySelector(button.dataset.clipboardTarget)?.textContent;
        
        if (!text) return;

        try {
            await navigator.clipboard.writeText(text);
            showCopySuccess(button);
        } catch (error) {
            // Fallback pour navigateurs plus anciens
            fallbackCopy(text, button);
        }
    });
}

function showCopySuccess(button) {
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check me-1"></i>Copié !';
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.classList.remove('btn-success');
    }, 2000);
}

function fallbackCopy(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(button);
        }
    } catch (error) {
        console.warn('Impossible de copier le texte');
    }
    
    document.body.removeChild(textArea);
}