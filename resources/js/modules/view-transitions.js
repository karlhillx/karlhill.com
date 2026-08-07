import { prefersReducedMotion } from '../lib/prefs.js';

function pathDepth(urlLike) {
    try {
        return new URL(urlLike, window.location.origin).pathname.split('/').filter(Boolean).length;
    } catch {
        return 0;
    }
}

function navigationType(fromUrl, toUrl) {
    const fromDepth = pathDepth(fromUrl);
    const toDepth = pathDepth(toUrl);
    if (toDepth > fromDepth) return 'forward';
    if (toDepth < fromDepth) return 'back';
    return 'same';
}

function applyViewTransitionType(event) {
    if (!event.viewTransition || prefersReducedMotion) return;
    const fromUrl = event.activation?.from?.url ?? window.location.href;
    const toUrl = event.activation?.entry?.url ?? window.location.href;
    const type = navigationType(fromUrl, toUrl);
    try {
        event.viewTransition.types.clear();
    } catch {
        // ViewTransitionTypeSet.clear() is missing in some early implementations.
    }
    event.viewTransition.types.add(type);
}

export function initViewTransitions() {
    window.addEventListener('pageswap', (event) => {
        applyViewTransitionType(event);
    });

    window.addEventListener('pagereveal', (event) => {
        applyViewTransitionType(event);
    });
}
