// Vanilla JS for scroll effects, command palette, and blog share actions.

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const prefersFinePointer = window.matchMedia('(pointer: fine)').matches;
const supportsViewTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'view()');

// ---------------------------------------------------------------------------
// Soft radial glow follows the pointer (CSS vars on :root)
// ---------------------------------------------------------------------------
if (!prefersReducedMotion && prefersFinePointer) {
    const root = document.documentElement;
    let rafId = null;
    let lx = 0;
    let ly = 0;

    document.addEventListener(
        'mousemove',
        (e) => {
            lx = e.clientX;
            ly = e.clientY;
            if (rafId !== null) return;
            rafId = requestAnimationFrame(() => {
                rafId = null;
                const w = window.innerWidth || 1;
                const h = window.innerHeight || 1;
                root.style.setProperty('--spot-x', `${(lx / w) * 100}%`);
                root.style.setProperty('--spot-y', `${(ly / h) * 100}%`);
            });
        },
        { passive: true }
    );
}

// ---------------------------------------------------------------------------
// Magnetic hover intent on selected controls
// ---------------------------------------------------------------------------
if (!prefersReducedMotion && prefersFinePointer) {
    document.querySelectorAll('.magnetic-btn').forEach((el) => {
        // Cache the rect on entry so mousemove never forces a layout read,
        // and batch the style writes into a single rAF per frame.
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

// ---------------------------------------------------------------------------
// Scroll-reveal (fade up) — JS fallback only. Browsers with view() timelines
// run the reveal entirely in CSS (see [data-reveal] in app.css), so the
// observer and inline stagger delays would be dead weight there.
// ---------------------------------------------------------------------------
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
        { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
    );

    document.querySelectorAll('[data-reveal]').forEach((el) => {
        if (prefersReducedMotion) {
            el.classList.add('revealed');
            return;
        }
        // Stagger siblings that share the same direct parent
        const siblings = Array.from(el.parentElement.querySelectorAll(':scope > [data-reveal]'));
        const index = siblings.indexOf(el);
        if (siblings.length > 1) {
            el.style.transitionDelay = `${index * 100}ms`;
        }
        revealObserver.observe(el);
    });
}

// ---------------------------------------------------------------------------
// Stat counters
// ---------------------------------------------------------------------------
// Split a display string like "$105M", "1.5M+", "10×" or "~40%" into a leading
// prefix, the numeric value, and a trailing suffix. Returns null when there's
// no number to animate (e.g. "Global", "Near RT") so we leave the text as-is.
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

    // Explicit data-* wins; otherwise derive the parts from the display string.
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
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
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

// ---------------------------------------------------------------------------
// Section navigation helpers (minimap, scroll spy, mobile rail)
// ---------------------------------------------------------------------------
const minimap = document.getElementById('section-minimap');
const sections = Array.from(document.querySelectorAll('main section[id], footer[id]'));
const navSpyLinks = document.querySelectorAll('nav[aria-label="Primary"] a[data-nav-section]');
const railLinks = document.querySelectorAll('#section-rail a[data-rail-section]');
let minimapButtons = [];

function setActiveSection(sectionId) {
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
}

if (minimap && sections.length > 0) {
    minimap.innerHTML = sections
        .map((section) => {
            const id = section.getAttribute('id');
            const label = id?.replace('-', ' ') || 'section';
            return `<button type="button" data-jump="${id}" aria-label="Jump to ${label}" title="${label}"></button>`;
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

// ---------------------------------------------------------------------------
// Mobile menu controls
// ---------------------------------------------------------------------------
// The menu is a native popover (opened declaratively via `popovertarget` on
// #nav-toggle). Light-dismiss, Esc, and top-layer are handled by the platform;
// we only mirror the open state onto the toggle for assistive tech.
const navToggle = document.getElementById('nav-toggle');
const mobileMenu = document.getElementById('mobile-menu');

mobileMenu?.addEventListener('toggle', (e) => {
    navToggle?.setAttribute('aria-expanded', e.newState === 'open' ? 'true' : 'false');
});

// Same-page anchor clicks (e.g. /#contact on the homepage) are fragment
// navigations, which don't light-dismiss a popover — close it explicitly.
mobileMenu?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => mobileMenu.hidePopover());
});

// ---------------------------------------------------------------------------
// Reading progress + contextual back to top button
// ---------------------------------------------------------------------------
const backTopBtn = document.getElementById('quick-back-top');
const root = document.documentElement;

// Scroll-driven CSS (animation-timeline: scroll()) powers the progress bar and
// the back-to-top reveal wherever it's supported. Only attach a scroll listener
// as a fallback for browsers that lack it (e.g. Safari today).
const supportsScrollTimeline =
    typeof CSS !== 'undefined' && CSS.supports('animation-timeline', 'scroll()');

if (!supportsScrollTimeline) {
    const updateScrollUI = () => {
        const max = root.scrollHeight - window.innerHeight;
        const progress = max > 0 ? (window.scrollY / max) * 100 : 0;
        root.style.setProperty('--scroll-progress', `${Math.min(progress, 100)}%`);
        backTopBtn?.classList.toggle('is-visible', window.scrollY > 560);
    };

    window.addEventListener('scroll', updateScrollUI, { passive: true });
    window.addEventListener('resize', updateScrollUI);
    updateScrollUI();
}

backTopBtn?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
});

// ---------------------------------------------------------------------------
// Command palette (Cmd/Ctrl+K) with fuzzy section routing
// ---------------------------------------------------------------------------
const palette = document.getElementById('command-palette');
const commandInput = document.getElementById('command-input');
const commandResults = document.getElementById('command-results');

// Section commands: scroll if anchor exists on this page, else navigate to the right page.
function gotoSection(id) {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' });
        return;
    }

    const pageMap = {
        experience: '/about#experience',
        research: '/about#research',
        stack: '/about#stack',
        credentials: '/about#credentials',
        work: '/work#work',
        'open-source': '/work#open-source',
        contact: '/#contact',
        writing: '/#writing',
        why: '/#why',
    };

    window.location.assign(pageMap[id] ?? `/#${id}`);
}

const commands = [
    {
        label: 'Home',
        keywords: 'home landing portfolio',
        action: () => window.location.assign('/'),
    },
    {
        label: 'Work — Portfolio',
        keywords: 'work portfolio projects nasa',
        action: () => window.location.assign('/work'),
    },
    {
        label: 'About — Experience',
        keywords: 'about experience career background',
        action: () => window.location.assign('/about'),
    },
    {
        label: 'Writing — Blog',
        keywords: 'writing blog posts articles essays notes governance',
        action: () => window.location.assign('/blog'),
    },
    {
        label: 'Experience',
        keywords: 'experience career nasa jacobs',
        action: () => gotoSection('experience'),
    },
    {
        label: 'Selected Work',
        keywords: 'work portfolio projects',
        action: () => gotoSection('work'),
    },
    {
        label: 'Research',
        keywords: 'research publication paper doi geohorizons flood mapping',
        action: () => gotoSection('research'),
    },
    { label: 'Stack', keywords: 'stack tech tools languages', action: () => gotoSection('stack') },
    {
        label: 'Credentials',
        keywords: 'certs certifications education scrum stats',
        action: () => gotoSection('credentials'),
    },
    {
        label: 'Open Source',
        keywords: 'github repos open source',
        action: () => gotoSection('open-source'),
    },
    { label: 'Contact', keywords: 'contact email hire', action: () => gotoSection('contact') },
    {
        label: 'RSS Feed',
        keywords: 'rss atom feed subscribe',
        action: () => window.open('/feed.xml', '_blank', 'noopener,noreferrer'),
    },
    {
        label: 'LinkedIn',
        keywords: 'linkedin social',
        action: () =>
            window.open('https://www.linkedin.com/in/khill/', '_blank', 'noopener,noreferrer'),
    },
    {
        label: 'GitHub',
        keywords: 'github code',
        action: () => window.open('https://github.com/karlhillx', '_blank', 'noopener,noreferrer'),
    },
];

let activeCommandIndex = 0;

const paletteIsOpen = () => palette?.matches(':popover-open') ?? false;

// Reset + focus on open, restore scroll on close. Open/close itself is native:
// triggers use `popovertarget`, Cmd+K calls togglePopover(), and Esc /
// click-outside are handled by popover=auto.
palette?.addEventListener('toggle', (e) => {
    const open = e.newState === 'open';
    commandInput?.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open) {
        document.body.style.overflow = 'hidden';
        if (commandInput) commandInput.value = '';
        activeCommandIndex = 0;
        renderCommands('');
        setTimeout(() => commandInput?.focus(), 0);
    } else {
        document.body.style.removeProperty('overflow');
    }
});

function runCommand(index) {
    const filtered = getFilteredCommands(commandInput?.value || '');
    const command = filtered[index];
    if (!command) return;
    palette?.hidePopover();
    command.action();
}

function getFilteredCommands(query) {
    const q = query.trim().toLowerCase();
    if (!q) return commands;
    return commands.filter((cmd) => {
        const combined = `${cmd.label} ${cmd.keywords}`.toLowerCase();
        return combined.includes(q);
    });
}

function renderCommands(query) {
    if (!commandResults) return;
    const filtered = getFilteredCommands(query);
    activeCommandIndex = Math.min(activeCommandIndex, Math.max(filtered.length - 1, 0));
    commandResults.innerHTML = filtered.length
        ? filtered
              .map(
                  (cmd, index) => `
                <button type="button"
                        id="command-result-${index}"
                        role="option"
                        aria-selected="${index === activeCommandIndex ? 'true' : 'false'}"
                        class="command-result ${index === activeCommandIndex ? 'is-active' : ''}"
                        data-command-index="${index}">
                    <span class="font-mono text-xs">${cmd.label}</span>
                </button>`
              )
              .join('')
        : '<p class="font-mono text-xs text-neutral-500 px-2 py-2">No matches</p>';
    commandInput?.setAttribute(
        'aria-activedescendant',
        filtered.length ? `command-result-${activeCommandIndex}` : ''
    );

    // Keep the highlighted option visible inside the scrollable results list —
    // otherwise on short viewports the selection scrolls out of sight and the
    // arrow keys appear to do nothing.
    if (filtered.length) {
        commandResults
            .querySelector('.command-result.is-active')
            ?.scrollIntoView({ block: 'nearest' });
    }
}

commandInput?.addEventListener('input', (e) => {
    activeCommandIndex = 0;
    renderCommands(e.target.value);
});

commandResults?.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-command-index]');
    if (!trigger) return;
    runCommand(Number(trigger.getAttribute('data-command-index')));
});

document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        palette?.togglePopover();
        return;
    }

    // "?" is the classic "show me the shortcuts" key — open the palette, unless
    // the visitor is typing into a field.
    if (e.key === '?' && !e.metaKey && !e.ctrlKey && !e.altKey && !paletteIsOpen()) {
        const el = e.target;
        const typing =
            el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
        if (!typing) {
            e.preventDefault();
            palette?.showPopover();
            return;
        }
    }

    // Esc, Tab focus containment, and click-outside are handled natively by
    // popover=auto; we only add the combobox-style list navigation.
    if (!paletteIsOpen()) return;

    const filtered = getFilteredCommands(commandInput?.value || '');
    const count = filtered.length;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (count) activeCommandIndex = (activeCommandIndex + 1) % count;
        renderCommands(commandInput?.value || '');
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (count) activeCommandIndex = (activeCommandIndex - 1 + count) % count;
        renderCommands(commandInput?.value || '');
    } else if (e.key === 'Home') {
        e.preventDefault();
        activeCommandIndex = 0;
        renderCommands(commandInput?.value || '');
    } else if (e.key === 'End') {
        e.preventDefault();
        activeCommandIndex = Math.max(count - 1, 0);
        renderCommands(commandInput?.value || '');
    } else if (e.key === 'Enter') {
        e.preventDefault();
        runCommand(activeCommandIndex);
    }
});

// ---------------------------------------------------------------------------
// Blog post share — copy permalink to clipboard
// ---------------------------------------------------------------------------
function flashFeedback(feedback) {
    if (!feedback) return;
    feedback.style.opacity = '1';
    clearTimeout(feedback._t);
    feedback._t = setTimeout(() => {
        feedback.style.opacity = '0';
    }, 1800);
}

document.querySelectorAll('[data-copy-link]').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const url = btn.getAttribute('data-copy-link');
        if (!url) return;
        try {
            await navigator.clipboard.writeText(url);
            flashFeedback(document.querySelector('[data-copy-feedback]'));
        } catch {
            window.prompt('Copy this link', url);
        }
    });
});

// ---------------------------------------------------------------------------
// Contact form — refresh the CSRF token so the (publicly cacheable) home page
// can never submit a stale/absent token behind a shared cache or CDN.
// ---------------------------------------------------------------------------
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    const tokenInput = contactForm.querySelector('input[name="_token"]');

    // Defer the token request until the visitor actually engages with the
    // form — most page views never touch it.
    let tokenReady = null;
    const ensureToken = () => {
        tokenReady ??= fetch('/csrf-token', {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })
            .then((res) => (res.ok ? res.json() : null))
            .then((data) => {
                if (data && data.token && tokenInput) tokenInput.value = data.token;
            })
            .catch(() => {});
        return tokenReady;
    };

    contactForm.addEventListener('focusin', ensureToken, { once: true });

    let tokenApplied = false;
    contactForm.addEventListener('submit', (e) => {
        if (tokenApplied) return;
        // Hold the submit until the fresh token has been applied, then re-fire.
        e.preventDefault();
        ensureToken().finally(() => {
            tokenApplied = true;
            contactForm.submit();
        });
    });
}

// Generic copy-to-clipboard (e.g. email address) with a scoped confirmation.
document.querySelectorAll('[data-copy-text]').forEach((btn) => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const text = btn.getAttribute('data-copy-text');
        if (!text) return;
        const feedback =
            btn.parentElement?.querySelector('[data-copy-feedback]') ??
            document.querySelector('[data-copy-feedback]');
        try {
            await navigator.clipboard.writeText(text);
            flashFeedback(feedback);
        } catch {
            window.prompt('Copy', text);
        }
    });
});
