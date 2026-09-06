/**
 * Records named conversion events without collecting form fields or visitor
 * identifiers. Use data-analytics-event on an actionable element.
 */
export function initAnalytics() {
    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;
        const target = event.target.closest('[data-analytics-event]');
        if (!(target instanceof HTMLElement)) return;

        trackEvent(target.dataset.analyticsEvent);
    });
}

export function trackEvent(name) {
    if (!name) return;

    if (typeof window.plausible === 'function') {
        window.plausible(name);
    }

    if (typeof window.gtag === 'function') {
        window.gtag('event', name);
    }
}
