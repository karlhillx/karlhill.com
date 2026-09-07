import { allowAmbientMotion, prefersFinePointer, prefersReducedMotion } from '../lib/prefs.js';

const CTA_DELAY_MS = 8000;
const SPOTLIGHT_IDLE_MS = 2800;

export function initPointerEffects() {
    if (prefersReducedMotion || !prefersFinePointer) return;

    pauseHeroAmbientWhenUnseen();
    initMagneticButtons();

    if (allowAmbientMotion) {
        initSpotlight();
        initPointerLitCards();
    }
}

/** Pause leftover hero loops when #hero is off-screen or the tab is hidden. */
function pauseHeroAmbientWhenUnseen() {
    const hero = document.getElementById('hero');
    if (!hero) return;

    const root = document.documentElement;
    let heroVisible = true;

    const sync = () => {
        root.classList.toggle('hero-ambient-paused', document.hidden || !heroVisible);
    };

    document.addEventListener('visibilitychange', sync);
    const observer = new IntersectionObserver(
        ([entry]) => {
            heroVisible = Boolean(entry?.isIntersecting);
            sync();
        },
        { threshold: 0 }
    );
    observer.observe(hero);
    sync();
}

/**
 * Follow the pointer with a translated orb — no :root CSS vars, no idle rAF.
 * Sleeps (opacity 0, drop will-change) shortly after the pointer stops.
 */
function initSpotlight() {
    const orb = document.querySelector('.page-spotlight__orb');
    if (!orb) return;

    let spotRaf = null;
    let idleTimer = null;
    let lx = window.innerWidth * 0.5;
    let ly = window.innerHeight * 0.35;

    const sleep = () => {
        orb.classList.remove('is-active');
        orb.classList.add('is-sleeping');
    };

    const wake = () => {
        orb.classList.add('is-active');
        orb.classList.remove('is-sleeping');
        window.clearTimeout(idleTimer);
        idleTimer = window.setTimeout(sleep, SPOTLIGHT_IDLE_MS);
    };

    const idleCtas = document.querySelectorAll('[data-idle-cta]');
    if (idleCtas.length > 0) {
        window.setTimeout(() => {
            if (!document.hidden) {
                idleCtas.forEach((el) => el.classList.add('is-idle-settle'));
            }
        }, CTA_DELAY_MS);
    }

    document.addEventListener(
        'mousemove',
        (event) => {
            if (document.hidden) return;
            lx = event.clientX;
            ly = event.clientY;
            wake();
            if (spotRaf !== null) return;
            spotRaf = requestAnimationFrame(() => {
                spotRaf = null;
                orb.style.transform = `translate3d(${lx}px, ${ly}px, 0)`;
            });
        },
        { passive: true }
    );

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            window.clearTimeout(idleTimer);
            sleep();
        }
    });
}

function initMagneticButtons() {
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
}

function initPointerLitCards() {
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
                const tiltX = Math.max(Math.min(((py - 50) / 50) * -3.5, 3.5), -3.5);
                const tiltY = Math.max(Math.min(((px - 50) / 50) * 4, 4), -4);
                card.style.setProperty('--tilt-x', `${tiltX}deg`);
                card.style.setProperty('--tilt-y', `${tiltY}deg`);
            });
        });
        card.addEventListener('mouseleave', resetTilt);
    });
}
