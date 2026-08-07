export const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
export const prefersFinePointer = window.matchMedia('(pointer: fine)').matches;
export const supportsViewTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'view()');
export const supportsScrollTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'scroll()');
