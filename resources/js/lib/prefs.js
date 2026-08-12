export const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
export const prefersFinePointer = window.matchMedia('(pointer: fine)').matches;
export const prefersSaveData =
    (typeof navigator !== 'undefined' &&
        (navigator.connection?.saveData === true ||
            window.matchMedia('(prefers-reduced-data: reduce)').matches)) ||
    false;
/** Ambient spotlight/mesh/tilt — fine pointer, full data, motion OK. */
export const allowAmbientMotion = !prefersReducedMotion && prefersFinePointer && !prefersSaveData;
export const supportsViewTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'view()');
export const supportsScrollTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'scroll()');
