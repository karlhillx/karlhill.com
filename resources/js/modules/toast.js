/**
 * Site toast — session-rendered or summoned from JS (contact ajax).
 * @param {string} [message]
 * @param {'success'|'error'} [variant]
 */
export function showToast(message, variant = 'success') {
    if (!message) {
        const existing = document.querySelector('[data-toast]');
        if (existing) wireToast(existing);
        return;
    }

    let toast = document.querySelector('[data-toast]');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'site-toast';
        toast.setAttribute('data-toast', '');
        toast.setAttribute('data-toast-duration', '5200');
        toast.style.setProperty('--toast-duration', '5200ms');
        toast.innerHTML = `
            <p class="font-mono text-xs uppercase tracking-widest" data-toast-message></p>
            <button type="button" class="site-toast__dismiss" data-toast-dismiss aria-label="Dismiss">×</button>
            <span class="site-toast__progress" aria-hidden="true"></span>
        `;
        document.body.append(toast);
    }

    toast.className = `site-toast site-toast--${variant === 'error' ? 'error' : 'success'}`;
    toast.setAttribute('role', variant === 'error' ? 'alert' : 'status');
    toast.hidden = false;
    toast.classList.remove('is-leaving', 'is-visible');

    const msgEl = toast.querySelector('[data-toast-message]') || toast.querySelector('p');
    if (msgEl) msgEl.textContent = message;

    wireToast(toast);
}

export function initToast() {
    const toast = document.querySelector('[data-toast]');
    if (toast) wireToast(toast);
}

/** @param {HTMLElement} toast */
function wireToast(toast) {
    if (toast.dataset.toastWired === '1') {
        // Re-show when summoned again with a new message.
        toast.hidden = false;
        toast.classList.remove('is-leaving');
        requestAnimationFrame(() => toast.classList.add('is-visible'));
        restartTimer(toast);
        return;
    }
    toast.dataset.toastWired = '1';

    const dismissToast = () => {
        toast.classList.add('is-leaving');
        toast.classList.remove('is-visible');
        window.setTimeout(() => {
            toast.hidden = true;
        }, 280);
    };

    toast._dismissToast = dismissToast;
    requestAnimationFrame(() => toast.classList.add('is-visible'));
    restartTimer(toast);

    toast.querySelector('[data-toast-dismiss]')?.addEventListener('click', () => {
        if (toast._toastTimer) window.clearTimeout(toast._toastTimer);
        dismissToast();
    });
}

/** @param {HTMLElement} toast */
function restartTimer(toast) {
    if (toast._toastTimer) window.clearTimeout(toast._toastTimer);
    const duration = Number(toast.getAttribute('data-toast-duration') || 5000);
    toast._toastTimer = window.setTimeout(() => toast._dismissToast?.(), duration);
}
