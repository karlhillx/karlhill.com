// Vanilla JS for scroll effects, command palette, and blog share actions.

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const prefersFinePointer = window.matchMedia('(pointer: fine)').matches;

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
        el.addEventListener('mousemove', (event) => {
            const rect = el.getBoundingClientRect();
            const dx = event.clientX - (rect.left + rect.width / 2);
            const dy = event.clientY - (rect.top + rect.height / 2);
            el.style.setProperty('--mx', `${Math.max(Math.min(dx * 0.1, 8), -8)}px`);
            el.style.setProperty('--my', `${Math.max(Math.min(dy * 0.1, 8), -8)}px`);
        });
        el.addEventListener('mouseleave', () => {
            el.style.setProperty('--mx', '0px');
            el.style.setProperty('--my', '0px');
        });
    });
}

// ---------------------------------------------------------------------------
// Scroll-reveal (fade up)
// ---------------------------------------------------------------------------
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

// ---------------------------------------------------------------------------
// Stat counters
// ---------------------------------------------------------------------------
function animateCounter(el) {
    const final = el.dataset.final;

    if (prefersReducedMotion) {
        el.textContent = final;
        return;
    }

    const to = parseFloat(el.dataset.to);
    const prefix = el.dataset.prefix || '';
    const suffix = el.dataset.suffix || '';
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

function setActiveSection(sectionId) {
    navSpyLinks.forEach((link) => {
        const active = link.dataset.navSection === sectionId;
        link.classList.toggle('text-accent', active);
        link.classList.toggle('text-neutral-500', !active);
    });

    railLinks.forEach((link) => {
        link.setAttribute(
            'aria-current',
            link.dataset.railSection === sectionId ? 'true' : 'false'
        );
    });

    minimap?.querySelectorAll('button[data-jump]').forEach((btn) => {
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

    minimap.querySelectorAll('button[data-jump]').forEach((btn) => {
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
        const clamped = Math.min(progress, 100);
        root.style.setProperty('--scroll-progress', `${clamped}%`);
        document
            .querySelector('.scroll-progress')
            ?.setAttribute('aria-valuenow', String(Math.round(clamped)));
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
    if (e.newState === 'open') {
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

    // Esc, Tab focus containment, and click-outside are handled natively by
    // popover=auto; we only add the combobox-style list navigation.
    if (!paletteIsOpen()) return;

    const filtered = getFilteredCommands(commandInput?.value || '');
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeCommandIndex = Math.min(activeCommandIndex + 1, Math.max(filtered.length - 1, 0));
        renderCommands(commandInput?.value || '');
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeCommandIndex = Math.max(activeCommandIndex - 1, 0);
        renderCommands(commandInput?.value || '');
    } else if (e.key === 'Enter') {
        e.preventDefault();
        runCommand(activeCommandIndex);
    }
});

// ---------------------------------------------------------------------------
// Blog post share — copy permalink to clipboard
// ---------------------------------------------------------------------------
document.querySelectorAll('[data-copy-link]').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const url = btn.getAttribute('data-copy-link');
        if (!url) return;
        const feedback = document.querySelector('[data-copy-feedback]');
        try {
            await navigator.clipboard.writeText(url);
            if (feedback) {
                feedback.style.opacity = '1';
                clearTimeout(feedback._t);
                feedback._t = setTimeout(() => {
                    feedback.style.opacity = '0';
                }, 1800);
            }
        } catch {
            window.prompt('Copy this link', url);
        }
    });
});
