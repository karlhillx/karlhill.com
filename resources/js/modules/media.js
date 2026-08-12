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

    // Lightbox first — later sticky-title setup must not block it.
    initMediaLightbox();

    const articleTitle = document.querySelector('[data-article-title]');
    const stickyTitle = document.querySelector('[data-article-sticky-title]');
    if (articleTitle && stickyTitle) {
        stickyTitle.hidden = false;
        const titleObserver = new IntersectionObserver(
            ([entry]) => {
                stickyTitle.classList.toggle('is-visible', entry && !entry.isIntersecting);
            },
            // IntersectionObserver rootMargin only accepts px/% — rem throws and
            // used to abort the rest of media init (including the screenshot lightbox).
            { rootMargin: '-72px 0px 0px 0px', threshold: 0 }
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

function initMediaLightbox() {
    const dialog = document.querySelector('[data-media-lightbox]');
    const img = dialog?.querySelector('[data-lightbox-img]');
    if (!dialog || !img || typeof dialog.showModal !== 'function') return;

    let lastTrigger = null;

    const open = (trigger) => {
        const src = trigger.getAttribute('data-lightbox-src');
        if (!src) return;
        lastTrigger = trigger;
        img.src = src;
        img.alt = trigger.getAttribute('data-lightbox-alt') || '';
        if (dialog.open) return;
        dialog.showModal();
    };

    // Event delegation so triggers work even if markup is re-rendered.
    document.addEventListener('click', (event) => {
        const trigger = event.target.closest?.('[data-lightbox-open]');
        if (!trigger) return;
        event.preventDefault();
        open(trigger);
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });

    dialog.addEventListener('close', () => {
        img.removeAttribute('src');
        img.alt = '';
        lastTrigger?.focus();
        lastTrigger = null;
    });
}
