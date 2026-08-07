// Site UI — modular entry. Each module owns one concern.
import { initViewTransitions } from './modules/view-transitions.js';
import { initPointerEffects } from './modules/pointer.js';
import { initRevealAndCounters } from './modules/reveal.js';
import { initNavigation } from './modules/navigation.js';
import { initCommandPalette } from './modules/command-palette.js';
import { initShareAndCopy } from './modules/share.js';
import { initContactForms } from './modules/contact.js';
import { initMediaEnhancements } from './modules/media.js';
import { initToast } from './modules/toast.js';
import { initServiceWorker } from './modules/service-worker.js';
import { initCmdkTip } from './modules/cmdk-tip.js';

initViewTransitions();
initPointerEffects();
initRevealAndCounters();
initNavigation();
initCommandPalette();
initShareAndCopy();
initContactForms();
initMediaEnhancements();
initToast();
initServiceWorker();
initCmdkTip();
