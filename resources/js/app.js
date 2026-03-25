import './bootstrap';

// Check user's motion preference once
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// ---------------------------------------------------------------------------
// Soft radial glow follows the pointer (CSS vars on :root)
// ---------------------------------------------------------------------------
if (!prefersReducedMotion && window.matchMedia('(pointer: fine)').matches) {
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

    try {
        const res = await fetch('/api/github/repos', { signal: controller.signal });
        clearTimeout(timeout);
        if (!res.ok) throw new Error('api error');

        const repos = await res.json();
        container.setAttribute('aria-busy', 'false');

        if (!Array.isArray(repos) || !repos.length) {
            document.getElementById('open-source')?.style.setProperty('display', 'none');
            return;
        }

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
    } catch {
        clearTimeout(timeout);
        container.setAttribute('aria-busy', 'false');
        document.getElementById('open-source')?.style.setProperty('display', 'none');
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
// Mobile menu — close on outside click
// ---------------------------------------------------------------------------
document.addEventListener('click', (e) => {
    const toggle = document.getElementById('nav-toggle');
    const menu   = document.getElementById('mobile-menu');
    if (!toggle || !menu || menu.hidden) return;
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
        menu.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
    }
});
