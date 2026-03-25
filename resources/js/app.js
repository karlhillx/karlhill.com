import './bootstrap';

// Check user's motion preference once
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
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

document.querySelectorAll('[data-reveal]').forEach(el => {
    if (prefersReducedMotion) {
        el.classList.add('revealed');
        return;
    }
    // Stagger siblings that share the same direct parent
    const siblings = Array.from(
        el.parentElement.querySelectorAll(':scope > [data-reveal]')
    );
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

    const to       = parseFloat(el.dataset.to);
    const prefix   = el.dataset.prefix || '';
    const suffix   = el.dataset.suffix || '';
    const isFloat  = !Number.isInteger(to);
    const duration = 1800;
    const start    = performance.now();

    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased    = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        const current  = to * eased;
        el.textContent = prefix + (isFloat ? current.toFixed(1) : Math.floor(current)) + suffix;
        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            el.textContent = final;
        }
    };

    requestAnimationFrame(step);
}

const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounter(entry.target);
            counterObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('[data-counter]').forEach(el => counterObserver.observe(el));

// ---------------------------------------------------------------------------
// GitHub repos
// ---------------------------------------------------------------------------
const langColors = {
    JavaScript: '#f1e05a',
    TypeScript: '#3178c6',
    Python:     '#3572A5',
    PHP:        '#4F5D95',
    Java:       '#b07219',
    HTML:       '#e34c26',
    CSS:        '#563d7c',
    Shell:      '#89e051',
    Go:         '#00ADD8',
    Ruby:       '#701516',
    Blade:      '#f7523f',
};
const excludedRepoNames = new Set(['karlhillx', 'karlhill.com']);

function esc(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

async function loadGitHubRepos() {
    const container = document.getElementById('github-repos');
    if (!container) return;

    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 8000);

    function renderRepoCards(repos) {
        container.className = 'grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-neutral-800';
        container.innerHTML = repos.map(repo => {
            const safeUrl = repo.url?.startsWith('https://github.com/') ? repo.url : '#';
            return `
            <a href="${esc(safeUrl)}" target="_blank" rel="noopener noreferrer"
               class="bg-[#080808] group block p-6 hover:bg-neutral-900/40 transition-all duration-200">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <p class="font-mono text-sm text-neutral-200 group-hover:text-orange-400 transition-colors leading-snug break-all">${esc(repo.name)}</p>
                    ${repo.stars ? `<span class="font-mono text-xs text-neutral-600 whitespace-nowrap shrink-0">★ ${esc(repo.stars)}</span>` : ''}
                </div>
                <p class="text-neutral-500 text-xs leading-relaxed mb-4 line-clamp-2">${esc(repo.description || '')}</p>
                <div class="flex flex-wrap items-center gap-3">
                    ${repo.language ? `
                    <span class="flex items-center gap-1.5 font-mono text-xs text-neutral-500">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background:${langColors[repo.language] || '#666'}"></span>
                        ${esc(repo.language)}
                    </span>` : ''}
                    ${(repo.topics || []).slice(0, 2).map(t =>
                        `<span class="font-mono text-xs px-2 py-0.5 border border-neutral-800 text-neutral-600">${esc(t)}</span>`
                    ).join('')}
                </div>
            </a>
        `}).join('');
    }

    function renderEmptyMessage() {
        container.innerHTML = `
            <div class="bg-[#080808] p-6 sm:col-span-2 lg:col-span-3">
                <p class="font-mono text-xs text-neutral-500 uppercase tracking-widest mb-2">Open Source</p>
                <p class="text-neutral-400 text-sm">No public repositories were returned right now. Please check back shortly.</p>
            </div>`;
    }

    function normalizeRepos(rawRepos) {
        if (!Array.isArray(rawRepos)) return [];
        return rawRepos
            .filter((r) => {
                if (r?.archived) return false;
                const name = String(r?.name || '').toLowerCase();
                return !excludedRepoNames.has(name);
            })
            .slice(0, 6)
            .map((r) => ({
                name: r.name,
                description: r.description,
                url: r.url || r.html_url,
                stars: r.stars ?? r.stargazers_count ?? 0,
                language: r.language,
                topics: Array.isArray(r.topics) ? r.topics : [],
            }));
    }

    try {
        const res = await fetch('/api/github/repos', { signal: controller.signal });
        clearTimeout(timeout);
        if (!res.ok) throw new Error('api error');

        const repos = normalizeRepos(await res.json());
        container.setAttribute('aria-busy', 'false');

        // Fallback: fetch directly from GitHub in the browser when backend
        // returns an empty list due to server-side API/network issues.
        if (!repos.length) {
            const directRes = await fetch('https://api.github.com/users/karlhillx/repos?sort=updated&direction=desc&per_page=100&type=public');
            if (directRes.ok) {
                const directRepos = normalizeRepos(await directRes.json());
                if (directRepos.length) {
                    renderRepoCards(directRepos);
                    return;
                }
            }
            renderEmptyMessage();
            return;
        }

        renderRepoCards(repos);
    } catch {
        clearTimeout(timeout);
        container.setAttribute('aria-busy', 'false');
        try {
            const directRes = await fetch('https://api.github.com/users/karlhillx/repos?sort=updated&direction=desc&per_page=100&type=public');
            if (directRes.ok) {
                const directRepos = normalizeRepos(await directRes.json());
                if (directRepos.length) {
                    renderRepoCards(directRepos);
                    return;
                }
            }
        } catch {
            // Keep the user-facing empty state below.
        }
        renderEmptyMessage();
    }
}

let githubReposRequested = false;
function scheduleGitHubReposLoad() {
    if (githubReposRequested) return;
    githubReposRequested = true;

    const run = () => {
        void loadGitHubRepos();
    };

    if ('requestIdleCallback' in window) {
        window.requestIdleCallback(run, { timeout: 1500 });
    } else {
        setTimeout(run, 400);
    }
}

const openSourceSection = document.getElementById('open-source');
if (openSourceSection) {
    const openSourceObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            scheduleGitHubReposLoad();
            openSourceObserver.disconnect();
        });
    }, { rootMargin: '500px 0px' });

    openSourceObserver.observe(openSourceSection);
}

// ---------------------------------------------------------------------------
// Scroll spy — highlight nav link for the section currently in view
// ---------------------------------------------------------------------------
const navLinks = document.querySelectorAll('nav a[href^="#"]');

const spyObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const id = entry.target.getAttribute('id');
        navLinks.forEach(link => {
            const active = link.getAttribute('href') === `#${id}`;
            link.classList.toggle('text-orange-500', active);
            link.classList.toggle('text-neutral-500', !active);
        });
    });
}, {
    rootMargin: '-40% 0px -55% 0px',
    threshold: 0,
});

document.querySelectorAll('section[id], footer[id]').forEach(el => spyObserver.observe(el));

// ---------------------------------------------------------------------------
// Mobile menu controls
// ---------------------------------------------------------------------------
const navToggle = document.getElementById('nav-toggle');
const mobileMenu = document.getElementById('mobile-menu');

navToggle?.addEventListener('click', () => {
    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', String(!expanded));
    if (mobileMenu) mobileMenu.hidden = expanded;
});

mobileMenu?.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
        mobileMenu.hidden = true;
        navToggle?.setAttribute('aria-expanded', 'false');
    });
});

document.addEventListener('click', (e) => {
    if (!navToggle || !mobileMenu || mobileMenu.hidden) return;
    if (!navToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.hidden = true;
        navToggle.setAttribute('aria-expanded', 'false');
    }
});

// ---------------------------------------------------------------------------
// Reading progress + contextual back to top button
// ---------------------------------------------------------------------------
const backTopBtn = document.getElementById('quick-back-top');
const root = document.documentElement;

function updateScrollUI() {
    const max = root.scrollHeight - window.innerHeight;
    const progress = max > 0 ? (window.scrollY / max) * 100 : 0;
    root.style.setProperty('--scroll-progress', `${Math.min(progress, 100)}%`);
    backTopBtn?.classList.toggle('is-visible', window.scrollY > 560);
}

window.addEventListener('scroll', updateScrollUI, { passive: true });
window.addEventListener('resize', updateScrollUI);
updateScrollUI();

backTopBtn?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
});

// ---------------------------------------------------------------------------
// Section mini-map synced to active section
// ---------------------------------------------------------------------------
const minimap = document.getElementById('section-minimap');
const sections = Array.from(document.querySelectorAll('main section[id], footer[id]'));

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
            target?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth', block: 'start' });
        });
    });
}

function setActiveSection(sectionId) {
    minimap?.querySelectorAll('button[data-jump]').forEach((btn) => {
        btn.setAttribute('aria-current', btn.getAttribute('data-jump') === sectionId ? 'true' : 'false');
    });
}

const navSectionsObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const id = entry.target.getAttribute('id');
        if (!id) return;
        setActiveSection(id);
    });
}, { rootMargin: '-40% 0px -50% 0px', threshold: 0 });

sections.forEach((section) => navSectionsObserver.observe(section));

// ---------------------------------------------------------------------------
// Command palette (Cmd/Ctrl+K) with fuzzy section routing
// ---------------------------------------------------------------------------
const palette = document.getElementById('command-palette');
const paletteTrigger = document.getElementById('command-palette-trigger');
const commandInput = document.getElementById('command-input');
const commandResults = document.getElementById('command-results');

const commands = [
    { label: 'Experience', keywords: 'experience career nasa jacobs', action: () => document.getElementById('experience')?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' }) },
    { label: 'Selected Work', keywords: 'work portfolio projects', action: () => document.getElementById('work')?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' }) },
    { label: 'Stack', keywords: 'stack tech tools languages', action: () => document.getElementById('stack')?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' }) },
    { label: 'Open Source', keywords: 'github repos open source', action: () => document.getElementById('open-source')?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' }) },
    { label: 'Certifications', keywords: 'certs education scrum', action: () => document.getElementById('certs')?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' }) },
    { label: 'Contact', keywords: 'contact email hire', action: () => document.getElementById('contact')?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' }) },
    { label: 'LinkedIn', keywords: 'linkedin social', action: () => window.open('https://www.linkedin.com/in/khill/', '_blank', 'noopener,noreferrer') },
    { label: 'GitHub', keywords: 'github code', action: () => window.open('https://github.com/karlhillx', '_blank', 'noopener,noreferrer') },
];

let activeCommandIndex = 0;

function openPalette() {
    if (!palette || !commandInput) return;
    palette.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    renderCommands('');
    setTimeout(() => commandInput.focus(), 0);
}

function closePalette() {
    if (!palette) return;
    palette.classList.add('hidden');
    document.body.style.removeProperty('overflow');
}

function runCommand(index) {
    const filtered = getFilteredCommands(commandInput?.value || '');
    const command = filtered[index];
    if (!command) return;
    closePalette();
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
            .map((cmd, index) => `
                <button type="button" class="command-result ${index === activeCommandIndex ? 'is-active' : ''}" data-command-index="${index}">
                    <span class="font-mono text-xs">${cmd.label}</span>
                </button>`)
            .join('')
        : '<p class="font-mono text-xs text-neutral-500 px-2 py-2">No matches</p>';
}

paletteTrigger?.addEventListener('click', openPalette);

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
    const cmdK = (e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k';
    if (cmdK) {
        e.preventDefault();
        if (palette?.classList.contains('hidden')) {
            openPalette();
        } else {
            closePalette();
        }
        return;
    }

    if (palette?.classList.contains('hidden')) return;

    if (e.key === 'Escape') {
        e.preventDefault();
        closePalette();
        return;
    }

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

palette?.addEventListener('click', (e) => {
    if (e.target === palette) closePalette();
});
