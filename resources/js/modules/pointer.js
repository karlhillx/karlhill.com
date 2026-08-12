import { prefersFinePointer, prefersReducedMotion } from '../lib/prefs.js';

export function initPointerEffects() {
    if (prefersReducedMotion || !prefersFinePointer) return;

    const root = document.documentElement;
    let spotRaf = null;
    let lx = 0;
    let ly = 0;

    document.addEventListener(
        'mousemove',
        (e) => {
            lx = e.clientX;
            ly = e.clientY;
            if (spotRaf !== null) return;
            spotRaf = requestAnimationFrame(() => {
                spotRaf = null;
                const w = window.innerWidth || 1;
                const h = window.innerHeight || 1;
                root.style.setProperty('--spot-x', `${(lx / w) * 100}%`);
                root.style.setProperty('--spot-y', `${(ly / h) * 100}%`);
            });
        },
        { passive: true }
    );

    document.querySelectorAll('.magnetic-btn').forEach((el) => {
        let rect = null;
        let rafId = null;
        let mx = 0;
        let my = 0;

        el.addEventListener('mouseenter', () => {
            rect = el.getBoundingClientRect();
        });
        el.addEventListener('mousemove', (event) => {
            if (!rect) rect = el.getBoundingClientRect();
            mx = event.clientX - (rect.left + rect.width / 2);
            my = event.clientY - (rect.top + rect.height / 2);
            if (rafId !== null) return;
            rafId = requestAnimationFrame(() => {
                rafId = null;
                el.style.setProperty('--mx', `${Math.max(Math.min(mx * 0.1, 8), -8)}px`);
                el.style.setProperty('--my', `${Math.max(Math.min(my * 0.1, 8), -8)}px`);
            });
        });
        el.addEventListener('mouseleave', () => {
            rect = null;
            el.style.setProperty('--mx', '0px');
            el.style.setProperty('--my', '0px');
        });
    });

    document.querySelectorAll('.pointer-lit').forEach((card) => {
        let rafId = null;
        let px = 50;
        let py = 40;

        const resetTilt = () => {
            card.style.setProperty('--tilt-x', '0deg');
            card.style.setProperty('--tilt-y', '0deg');
        };

        card.addEventListener('mousemove', (event) => {
            const rect = card.getBoundingClientRect();
            px = ((event.clientX - rect.left) / Math.max(rect.width, 1)) * 100;
            py = ((event.clientY - rect.top) / Math.max(rect.height, 1)) * 100;
            if (rafId !== null) return;
            rafId = requestAnimationFrame(() => {
                rafId = null;
                card.style.setProperty('--card-x', `${px}%`);
                card.style.setProperty('--card-y', `${py}%`);
                // Soft perspective tilt — capped so cards stay readable.
                const tiltX = Math.max(Math.min(((py - 50) / 50) * -3.5, 3.5), -3.5);
                const tiltY = Math.max(Math.min(((px - 50) / 50) * 4, 4), -4);
                card.style.setProperty('--tilt-x', `${tiltX}deg`);
                card.style.setProperty('--tilt-y', `${tiltY}deg`);
            });
        });
        card.addEventListener('mouseleave', resetTilt);
    });
}
