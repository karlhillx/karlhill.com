export function initServiceWorker() {
    if (!('serviceWorker' in navigator) || !window.isSecureContext) return;

    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {
            // Registration can fail on file:// or restrictive CSP; ignore.
        });
    });
}
