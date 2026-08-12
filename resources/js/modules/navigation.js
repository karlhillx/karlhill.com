import { prefersReducedMotion, supportsScrollTimeline } from '../lib/prefs.js';

export function initNavigation() {
    const minimap = document.getElementById('section-minimap');
    const sections = Array.from(document.querySelectorAll('main section[id], footer[id]'));
    const navSpyLinks = document.querySelectorAll('nav[aria-label="Primary"] a[data-nav-section]');
    const railLinks = document.querySelectorAll('#section-rail a[data-rail-section]');
    let minimapButtons = [];

    const setActiveSection = (sectionId) => {
        navSpyLinks.forEach((link) => {
            const active = link.dataset.navSection === sectionId;
            link.classList.toggle('text-accent', active);
            link.classList.toggle('text-neutral-500', !active);
        });

        railLinks.forEach((link) => {
            if (link.dataset.railSection === sectionId) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });

        minimapButtons.forEach((btn) => {
            btn.setAttribute(
                'aria-current',
                btn.getAttribute('data-jump') === sectionId ? 'true' : 'false'
            );
        });
    };

    if (minimap && sections.length > 0) {
        minimap.innerHTML = sections
            .map((section) => {
                const id = section.getAttribute('id');
                const label = section.dataset.sectionLabel || id?.replace(/-/g, ' ') || 'section';
                const anchor = String(id || 'section').replace(/[^a-zA-Z0-9_-]/g, '');
                const tip = String(label)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/"/g, '&quot;');
                return `<div class="section-minimap-item">
                <button type="button" data-jump="${id}" style="anchor-name: --mm-${anchor}" aria-label="Jump to ${tip}"></button>
                <span class="section-minimap-tip" style="position-anchor: --mm-${anchor}">${tip}</span>
            </div>`;
            })
            .join('');

        minimapButtons = Array.from(minimap.querySelectorAll('button[data-jump]'));

        minimapButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-jump');
                const target = id ? document.getElementById(id) : null;
                target?.scrollIntoView({
                    behavior: prefersReducedMotion ? 'auto' : 'smooth',
                    block: 'start',
                });
            });
        });
    }

    // IntersectionObserver rootMargin must use px or % only (rem throws in
    // browsers and historically aborted media.js before the lightbox wired up).
    const spyObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const id = entry.target.getAttribute('id');
                if (id) setActiveSection(id);
            });
        },
        {
            rootMargin: '-40% 0px -55% 0px',
            threshold: 0,
        }
    );

    sections.forEach((el) => spyObserver.observe(el));
    document.querySelectorAll('footer[id]').forEach((el) => spyObserver.observe(el));

    const mobileArticleToc = document.querySelector('.article-toc-mobile');
    const tocLinks = document.querySelectorAll('[data-toc-link]');

    if (tocLinks.length > 0) {
        const tocTargets = Array.from(tocLinks)
            .map((link) => {
                const id = link.getAttribute('href')?.slice(1);
                return id ? document.getElementById(id) : null;
            })
            .filter(Boolean);

        const setActiveToc = (id) => {
            tocLinks.forEach((link) => {
                const active = link.getAttribute('href') === `#${id}`;
                link.classList.toggle('is-active', active);
                if (active) {
                    link.setAttribute('aria-current', 'location');
                } else {
                    link.removeAttribute('aria-current');
                }
            });
        };

        const tocObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    const id = entry.target.getAttribute('id');
                    if (id) setActiveToc(id);
                });
            },
            {
                rootMargin: '-30% 0px -60% 0px',
                threshold: 0,
            }
        );

        tocTargets.forEach((el) => tocObserver.observe(el));

        tocLinks.forEach((link) => {
            link.addEventListener('click', () => {
                mobileArticleToc?.removeAttribute('open');
            });
        });
    }

    const navToggle = document.getElementById('nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenu?.addEventListener('toggle', (e) => {
        navToggle?.setAttribute('aria-expanded', e.newState === 'open' ? 'true' : 'false');
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => mobileMenu.hidePopover());
    });

    const backTopBtn = document.getElementById('quick-back-top');
    const root = document.documentElement;
    const primaryNav = document.querySelector('nav[aria-label="Primary"]');

    if (!supportsScrollTimeline) {
        const updateScrollUI = () => {
            const max = root.scrollHeight - window.innerHeight;
            const progress = max > 0 ? (window.scrollY / max) * 100 : 0;
            root.style.setProperty('--scroll-progress', `${Math.min(progress, 100)}%`);
            backTopBtn?.classList.toggle('is-visible', window.scrollY > 560);
            primaryNav?.classList.toggle('is-compact', window.scrollY > 160);
        };

        window.addEventListener('scroll', updateScrollUI, { passive: true });
        window.addEventListener('resize', updateScrollUI);
        updateScrollUI();
    }

    backTopBtn?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    });
}
