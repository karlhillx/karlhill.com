import { prefersReducedMotion } from '../lib/prefs.js';

function isFilterUrl(url) {
    try {
        const parsed = new URL(url, window.location.origin);
        if (parsed.origin !== window.location.origin) return false;
        return (
            parsed.pathname === '/work' ||
            parsed.pathname.startsWith('/work/tag/') ||
            parsed.pathname === '/blog' ||
            parsed.pathname.startsWith('/blog/tag/')
        );
    } catch {
        return false;
    }
}

async function swapTarget(url) {
    const response = await fetch(url, {
        headers: { Accept: 'text/html' },
        credentials: 'same-origin',
    });
    if (!response.ok) {
        window.location.assign(url);
        return;
    }
    const html = await response.text();
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const next = doc.querySelector('[data-soft-nav-target]');
    const current = document.querySelector('[data-soft-nav-target]');
    const nextFilter = doc.querySelector('[data-soft-nav]');
    const currentFilter = document.querySelector('[data-soft-nav]');

    if (next && current) current.replaceWith(next);
    if (nextFilter && currentFilter) currentFilter.replaceWith(nextFilter);
    document.title = doc.title;
    document.documentElement.dataset.features = doc.documentElement.dataset.features || '';
    window.dispatchEvent(new CustomEvent('site:navigated', { detail: { url } }));
}

function runSwap(url) {
    const go = () => swapTarget(url);
    if (prefersReducedMotion || !document.startViewTransition) {
        return go();
    }

    const start = document.startViewTransition;
    try {
        return start.call(document, {
            update: go,
            types: ['same'],
        });
    } catch {
        return document.startViewTransition(go);
    }
}

/**
 * Same-document tag-filter swaps via the Navigation API (fallback: click).
 * Scoped view transitions animate the grid without a full MPA reload.
 */
export function initSoftNav() {
    const intercept = (url) => {
        if (!isFilterUrl(url)) return false;
        runSwap(url);
        return true;
    };

    if ('navigation' in window && window.navigation?.addEventListener) {
        window.navigation.addEventListener('navigate', (event) => {
            if (!event.canIntercept || event.hashChange || event.downloadRequest) return;
            if (event.navigationType === 'reload') return;
            const url = event.destination?.url;
            if (!url || !isFilterUrl(url)) return;
            event.intercept({
                handler: () => swapTarget(url),
            });
        });
        return;
    }

    document.addEventListener('click', (event) => {
        const link = event.target.closest?.('[data-soft-nav] a');
        if (!link || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        if (link.target === '_blank') return;
        const url = link.href;
        if (!isFilterUrl(url)) return;
        event.preventDefault();
        intercept(url);
        history.pushState({}, '', url);
    });
}
