import { prefersReducedMotion } from '../lib/prefs.js';

/**
 * LQIP fade-in, case-study sticky title, series chapter scroll, media lightbox.
 */
export function initMediaEnhancements() {
    document.querySelectorAll('[data-lqip-img]').forEach((img) => {
        const wrap = img.closest('.img-lqip');
        wrap?.classList.add('img-lqip--enhance');

        const markLoaded = () => {
            img.classList.add('is-loaded');
            wrap?.classList.add('is-loaded');
        };
        if (img instanceof HTMLImageElement && img.complete && img.naturalWidth > 0) {
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
    if (articleTitle && stickyTitle instanceof HTMLElement) {
        stickyTitle.hidden = false;
        const titleObserver = new IntersectionObserver(
            ([entry]) => {
                stickyTitle.classList.toggle('is-visible', Boolean(entry && !entry.isIntersecting));
            },
            // IntersectionObserver rootMargin only accepts px/% — rem throws and
            // used to abort the rest of media init (including the screenshot lightbox).
            { rootMargin: '-72px 0px 0px 0px', threshold: 0 }
        );
        titleObserver.observe(articleTitle);

        document.getElementById('mobile-menu')?.addEventListener('toggle', (e) => {
            const event = /** @type {ToggleEvent} */ (e);
            stickyTitle.classList.toggle('is-suppressed', event.newState === 'open');
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

/**
 * Screenshot / figure lightbox via <dialog data-media-lightbox>.
 */
function initMediaLightbox() {
    const dialog = document.querySelector('[data-media-lightbox]');
    const img = dialog?.querySelector('[data-lightbox-img]');
    if (
        !(dialog instanceof HTMLDialogElement) ||
        !(img instanceof HTMLImageElement) ||
        typeof dialog.showModal !== 'function'
    ) {
        return;
    }

    /** @type {HTMLElement|null} */
    let lastTrigger = null;

    /** @param {HTMLElement} trigger */
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
        const target = /** @type {Element|null} */ (
            event.target instanceof Element ? event.target : null
        );
        const trigger = target?.closest?.('[data-lightbox-open]');
        if (!(trigger instanceof HTMLElement)) return;
        event.preventDefault();
        open(trigger);
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });

    dialog.addEventListener('close', () => {
        lastTrigger?.focus?.();
        lastTrigger = null;
        img.removeAttribute('src');
    });
}
