/**
 * Records named conversion events without collecting form fields or visitor
 * identifiers. Use data-analytics-event on an actionable element; any other
 * data-analytics-* attribute (e.g. data-analytics-location="footer") is sent
 * as an event property so CTA placement can be compared in the dashboard.
 */
export function initAnalytics() {
    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;
        const target = event.target.closest('[data-analytics-event]');
        if (!(target instanceof HTMLElement)) return;

        trackEvent(target.dataset.analyticsEvent, propsFromDataset(target.dataset));
    });

    listenForBookingCompletion();
}

/**
 * @param {string | undefined} name
 * @param {Record<string, string>} [props]
 */
export function trackEvent(name, props = {}) {
    if (!name) return;

    const payload = { page: window.location.pathname, ...props };

    if (typeof window.plausible === 'function') {
        window.plausible(name, { props: payload });
    }

    if (typeof window.gtag === 'function') {
        window.gtag('event', name, payload);
    }
}

/**
 * Calendly posts a message to the parent window when a visitor finishes
 * scheduling inside the inline embed. That is the real conversion; the CTA
 * click that scrolled them to the iframe is only intent.
 */
function listenForBookingCompletion() {
    if (!document.querySelector('.booking-embed__frame')) return;

    window.addEventListener('message', (event) => {
        if (!isSchedulerOrigin(event.origin)) return;
        const name = event.data?.event;
        if (typeof name !== 'string') return;

        if (name === 'calendly.event_scheduled' || name === 'bookingSuccessful') {
            trackEvent('booking_completed', { location: 'now-embed' });
        }
    });
}

function isSchedulerOrigin(origin) {
    try {
        const host = new URL(origin).hostname;
        return (
            host === 'calendly.com' || host.endsWith('.calendly.com') || host.endsWith('cal.com')
        );
    } catch {
        return false;
    }
}

/** `data-analytics-location="footer"` → `{ location: 'footer' }` */
function propsFromDataset(dataset) {
    const props = {};
    for (const [key, value] of Object.entries(dataset)) {
        if (key === 'analyticsEvent' || !key.startsWith('analytics') || !value) continue;
        const prop = key
            .slice('analytics'.length)
            .replace(/^[A-Z]/, (c) => c.toLowerCase())
            .replace(/[A-Z]/g, (c) => `_${c.toLowerCase()}`);
        props[prop] = value;
    }
    return props;
}
