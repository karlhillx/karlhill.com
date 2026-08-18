import { allowAmbientMotion, prefersFinePointer, prefersReducedMotion } from '../lib/prefs.js';

const SPOT_REST_X = 50;
const SPOT_REST_Y = 35;
const WANDER_DELAY_MS = 6000;
const CTA_DELAY_MS = 8000;
const WANDER_PERIOD_MS = 22000;
const WANDER_AMP_X = 8;
const WANDER_AMP_Y = 5;

export function initPointerEffects() {
    if (prefersReducedMotion || !prefersFinePointer) return;

    pauseHeroAmbientWhenUnseen();
    initMagneticButtons();

    if (allowAmbientMotion) {
        initSpotlightAndIdle();
        initPointerLitCards();
    }
}

/** Pause infinite hero loops when #hero is off-screen or the tab is hidden. */
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

function initSpotlightAndIdle() {
    const root = document.documentElement;
    let spotRaf = null;
    let wanderRaf = null;
    let wanderTimer = 0;
    let ctaTimer = 0;
    let wandering = false;
    let wanderOriginX = SPOT_REST_X;
    let wanderOriginY = SPOT_REST_Y;
    let wanderStartedAt = 0;
    let lx = 0;
    let ly = 0;
    let lastSpotX = SPOT_REST_X;
    let lastSpotY = SPOT_REST_Y;
    let ctaSettled = false;

    const idleCtas = document.querySelectorAll('[data-idle-cta]');

    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

    const applySpot = (x, y) => {
        lastSpotX = x;
        lastSpotY = y;
        root.style.setProperty('--spot-x', `${x}%`);
        root.style.setProperty('--spot-y', `${y}%`);
    };

    const stopWander = () => {
        wandering = false;
        if (wanderRaf !== null) {
            cancelAnimationFrame(wanderRaf);
            wanderRaf = null;
        }
    };

    const tickWander = (now) => {
        if (!wandering) return;
        const t = ((now - wanderStartedAt) / WANDER_PERIOD_MS) * Math.PI * 2;
        applySpot(
            clamp(wanderOriginX + Math.sin(t) * WANDER_AMP_X, 8, 92),
            clamp(wanderOriginY + Math.cos(t * 0.7) * WANDER_AMP_Y, 12, 88)
        );
        wanderRaf = requestAnimationFrame(tickWander);
    };

    const startWander = () => {
        if (document.hidden) return;
        stopWander();
        wandering = true;
        wanderOriginX = lastSpotX;
        wanderOriginY = lastSpotY;
        wanderStartedAt = performance.now();
        wanderRaf = requestAnimationFrame(tickWander);
    };

    const settleCta = () => {
        if (ctaSettled || document.hidden) return;
        ctaSettled = true;
        idleCtas.forEach((el) => el.classList.add('is-idle-settle'));
    };

    const armIdle = () => {
        window.clearTimeout(wanderTimer);
        window.clearTimeout(ctaTimer);
        wanderTimer = window.setTimeout(startWander, WANDER_DELAY_MS);
        if (!ctaSettled && idleCtas.length > 0) {
            ctaTimer = window.setTimeout(settleCta, CTA_DELAY_MS);
        }
    };

    const onActivity = () => {
        stopWander();
        armIdle();
    };

    document.addEventListener(
        'mousemove',
        (event) => {
            lx = event.clientX;
            ly = event.clientY;
            onActivity();
            if (spotRaf !== null) return;
            spotRaf = requestAnimationFrame(() => {
                spotRaf = null;
                const w = window.innerWidth || 1;
                const h = window.innerHeight || 1;
                applySpot((lx / w) * 100, (ly / h) * 100);
            });
        },
        { passive: true }
    );

    window.addEventListener('scroll', onActivity, { passive: true });
    window.addEventListener('wheel', onActivity, { passive: true });
    document.addEventListener('keydown', onActivity, { passive: true });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopWander();
            window.clearTimeout(wanderTimer);
            window.clearTimeout(ctaTimer);
            return;
        }
        armIdle();
    });

    armIdle();
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
