/**
 * Records named conversion events without collecting form fields or visitor
 * identifiers. Use data-analytics-event on an actionable element; any other
 * data-analytics-* attribute (e.g. data-analytics-location="footer") is sent
 * as an event property so CTA placement can be compared in the dashboard.
 *
 * Plausible pageviews use fetchLater (falling back to sendBeacon / keepalive
 * fetch) so the hire-path bundle never loads plausible.io/js/script.js.
 */
export function initAnalytics() {
    installPlausibleTransport();

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

function installPlausibleTransport() {
    const config = siteAnalytics();
    if (config.provider !== 'plausible' || !config.domain) return;

    const existing = window.plausible;
    if (typeof existing === 'function' && !Array.isArray(existing.q)) {
        return;
    }

    const queued = existing?.q || [];
    window.plausible = (name, options) => {
        sendPlausible(config.domain, name, options?.props);
    };

    for (const args of queued) {
        window.plausible(...args);
    }

    whenActivated().then(() => {
        window.plausible('pageview');
    });
}

function siteAnalytics() {
    const configured = window.__siteAnalytics;
    if (configured && typeof configured === 'object') {
        return {
            provider: String(configured.provider || ''),
            domain: String(configured.domain || ''),
        };
    }

    return { provider: '', domain: '' };
}

function sendPlausible(domain, name, props) {
    if (!name || !shouldTrack()) return;

    const payload = {
        n: name,
        u: location.href,
        d: domain,
        r: document.referrer || '',
        w: window.innerWidth,
    };

    if (props && typeof props === 'object' && Object.keys(props).length > 0) {
        payload.p = props;
    }

    const body = JSON.stringify(payload);
    const endpoint = 'https://plausible.io/api/event';
    const init = {
        method: 'POST',
        headers: { 'Content-Type': 'text/plain' },
        body,
        keepalive: true,
        credentials: 'omit',
    };

    if (typeof globalThis.fetchLater === 'function') {
        try {
            globalThis.fetchLater(endpoint, { ...init, activateAfter: 0 });
            return;
        } catch {
            // Fall through to sendBeacon / fetch.
        }
    }

    if (navigator.sendBeacon?.(endpoint, new Blob([body], { type: 'text/plain' }))) {
        return;
    }

    fetch(endpoint, init).catch(() => {});
}

function shouldTrack() {
    try {
        if (localStorage.getItem('plausible_ignore') === 'true') return false;
    } catch {
        // Private mode can throw on localStorage.
    }

    const host = location.hostname;
    return host !== 'localhost' && host !== '127.0.0.1' && !host.endsWith('.test');
}

function whenActivated() {
    if (!document.prerendering) {
        return Promise.resolve();
    }

    return new Promise((resolve) => {
        document.addEventListener('prerenderingchange', resolve, { once: true });
    });
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
