import { prefersReducedMotion } from '../lib/prefs.js';

export function initMediaEnhancements() {
    document.querySelectorAll('[data-lqip-img]').forEach((img) => {
        const markLoaded = () => {
            img.classList.add('is-loaded');
            img.closest('.img-lqip')?.classList.add('is-loaded');
        };
        if (img.complete && img.naturalWidth > 0) {
            markLoaded();
            return;
        }
        img.addEventListener('load', markLoaded, { once: true });
        img.addEventListener('error', markLoaded, { once: true });
    });

    const articleTitle = document.querySelector('[data-article-title]');
    const stickyTitle = document.querySelector('[data-article-sticky-title]');
    if (articleTitle && stickyTitle) {
        stickyTitle.hidden = false;
        const titleObserver = new IntersectionObserver(
            ([entry]) => {
                stickyTitle.classList.toggle('is-visible', entry && !entry.isIntersecting);
            },
            { rootMargin: '-4.5rem 0px 0px 0px', threshold: 0 }
        );
        titleObserver.observe(articleTitle);

        document.getElementById('mobile-menu')?.addEventListener('toggle', (e) => {
            stickyTitle.classList.toggle('is-suppressed', e.newState === 'open');
        });
    }

    document.querySelectorAll('[data-series-chapters]').forEach((strip) => {
        const current = strip.querySelector('.series-chapters__item.is-current');
        if (!current || window.matchMedia('(min-width: 768px)').matches) return;
        requestAnimationFrame(() => {
            current.scrollIntoView({
                inline: 'start',
                block: 'nearest',
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
            });
        });
    });
}
