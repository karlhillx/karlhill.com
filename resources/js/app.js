// Site UI — modular entry.
// Core modules boot on every page; heavier features load from data-features.
import { initViewTransitions } from './modules/view-transitions.js';
import { initNavigation } from './modules/navigation.js';
import { initCommandPalette } from './modules/command-palette.js';
import { initToast } from './modules/toast.js';
import { initServiceWorker } from './modules/service-worker.js';

initViewTransitions();
initNavigation();
initCommandPalette();
initToast();
initServiceWorker();

const features = new Set(
    (document.documentElement.dataset.features || '').split(/\s+/).filter(Boolean)
);

/** @param {boolean} enabled @param {() => Promise<{ [k: string]: Function }>} loader @param {string} initName */
function loadWhen(enabled, loader, initName) {
    if (!enabled) return;
    loader()
        .then((mod) => {
            const init = mod[initName];
            if (typeof init === 'function') init();
        })
        .catch(() => {
            /* optional chunk — fail soft */
        });
}

loadWhen(features.has('pointer'), () => import('./modules/pointer.js'), 'initPointerEffects');
loadWhen(features.has('reveal'), () => import('./modules/reveal.js'), 'initRevealAndCounters');
loadWhen(features.has('contact'), () => import('./modules/contact.js'), 'initContactForms');
// Media: route flag OR markup present (LQIP / lightbox) so images never stay opacity:0.
loadWhen(
    features.has('media') ||
        Boolean(
            document.querySelector('[data-lqip-img], [data-media-lightbox], [data-lightbox-open]')
        ),
    () => import('./modules/media.js'),
    'initMediaEnhancements'
);
loadWhen(features.has('share'), () => import('./modules/share.js'), 'initShareAndCopy');
loadWhen(features.has('cmdk-tip'), () => import('./modules/cmdk-tip.js'), 'initCmdkTip');
loadWhen(features.has('push'), () => import('./modules/push.js'), 'initPushSubscribe');
loadWhen(features.has('highlight'), () => import('./modules/highlight.js'), 'initHighlight');
loadWhen(features.has('soft-nav'), () => import('./modules/soft-nav.js'), 'initSoftNav');
loadWhen(features.has('webgpu'), () => import('./modules/webgpu-flood.js'), 'initWebGpuFlood');
