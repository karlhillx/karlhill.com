export function initToast() {
    const toast = document.querySelector('[data-toast]');
    if (!toast) return;

    const dismissToast = () => {
        toast.classList.add('is-leaving');
        toast.classList.remove('is-visible');
        window.setTimeout(() => {
            toast.hidden = true;
        }, 280);
    };
    requestAnimationFrame(() => toast.classList.add('is-visible'));
    const duration = Number(toast.getAttribute('data-toast-duration') || 5000);
    const timer = window.setTimeout(dismissToast, duration);
    toast.querySelector('[data-toast-dismiss]')?.addEventListener('click', () => {
        window.clearTimeout(timer);
        dismissToast();
    });
}
