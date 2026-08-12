import { prefersReducedMotion, supportsViewTimeline } from '../lib/prefs.js';

function parseCountValue(str) {
    const match = String(str).match(/^(\D*)([\d,]+(?:\.\d+)?)(.*)$/s);
    if (!match) return null;
    return {
        prefix: match[1],
        to: parseFloat(match[2].replace(/,/g, '')),
        suffix: match[3],
    };
}

function animateCounter(el) {
    const final = el.dataset.final ?? el.textContent.trim();

    if (prefersReducedMotion) {
        el.textContent = final;
        return;
    }

    let to;
    let prefix;
    let suffix;

    if (el.dataset.to !== undefined) {
        to = parseFloat(el.dataset.to);
        prefix = el.dataset.prefix || '';
        suffix = el.dataset.suffix || '';
    } else {
        const parsed = parseCountValue(final);
        if (!parsed) {
            el.textContent = final;
            return;
        }
        ({ to, prefix, suffix } = parsed);
    }

    const isFloat = !Number.isInteger(to);
    const duration = 1800;
    const start = performance.now();

    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = to * eased;
        el.textContent = prefix + (isFloat ? current.toFixed(1) : Math.floor(current)) + suffix;
        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            el.textContent = final;
        }
    };

    requestAnimationFrame(step);
}

export function initRevealAndCounters() {
    if (!supportsViewTimeline) {
        const revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        revealObserver.unobserve(entry.target);
                    }
                });
            },
            // px/% only — rem units throw and break reveal init.
            { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
        );

        document.querySelectorAll('[data-reveal]').forEach((el) => {
            if (prefersReducedMotion) {
                el.classList.add('revealed');
                return;
            }
            const siblings = Array.from(
                el.parentElement.querySelectorAll(':scope > [data-reveal]')
            );
            const index = siblings.indexOf(el);
            if (siblings.length > 1) {
                el.style.transitionDelay = `${index * 100}ms`;
            }
            revealObserver.observe(el);
        });
    }

    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 }
    );

    document.querySelectorAll('[data-counter]').forEach((el) => counterObserver.observe(el));
}
