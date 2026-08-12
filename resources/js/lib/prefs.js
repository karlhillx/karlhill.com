/**
 * Runtime preference / capability flags for progressive enhancement.
 * Keep these as module-level constants — they are read once at boot.
 */

/** @type {boolean} */
export const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/** @type {boolean} */
export const prefersFinePointer = window.matchMedia('(pointer: fine)').matches;

/** @type {boolean} */
export const prefersSaveData =
    (typeof navigator !== 'undefined' &&
        (navigator.connection?.saveData === true ||
            window.matchMedia('(prefers-reduced-data: reduce)').matches)) ||
    false;

/** Ambient spotlight/mesh/tilt — fine pointer, full data, motion OK. */
/** @type {boolean} */
export const allowAmbientMotion = !prefersReducedMotion && prefersFinePointer && !prefersSaveData;

/** @type {boolean} */
export const supportsViewTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'view()');

/** @type {boolean} */
export const supportsScrollTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'scroll()');

if (prefersSaveData) {
    document.documentElement.classList.add('save-data');
}
