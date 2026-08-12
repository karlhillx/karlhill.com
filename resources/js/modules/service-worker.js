/**
 * Register the offline service worker in production only.
 * On local hosts, unregister any leftover SW so a stopped `artisan serve`
 * cannot strand you on offline.html.
 */
export function initServiceWorker() {
    if (!('serviceWorker' in navigator) || !window.isSecureContext) return;

    window.addEventListener('load', () => {
        if (shouldDisableServiceWorker()) {
            clearServiceWorkers();
            return;
        }

        navigator.serviceWorker.register('/sw.js').catch(() => {
            // Registration can fail on file:// or restrictive CSP; ignore.
        });
    });
}

function shouldDisableServiceWorker() {
    if (document.documentElement.dataset.sw === 'off') return true;

    const host = window.location.hostname;
    return (
        host === 'localhost' ||
        host === '127.0.0.1' ||
        host === '0.0.0.0' ||
        host.endsWith('.test') ||
        host.endsWith('.local')
    );
}

function clearServiceWorkers() {
    navigator.serviceWorker.getRegistrations().then((regs) => {
        regs.forEach((reg) => reg.unregister());
    });
    if ('caches' in window) {
        caches.keys().then((keys) => {
            keys.forEach((key) => caches.delete(key));
        });
    }
}
