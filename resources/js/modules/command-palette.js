import { prefersReducedMotion } from '../lib/prefs.js';

const GROUP_LABELS = {
    page: 'Page',
    writing: 'Writing',
    work: 'Work',
};

function gotoSection(id) {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' });
        return;
    }

    const pageMap = {
        experience: '/about#experience',
        'how-i-lead': '/about#how-i-lead',
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

function parseCommandIndex() {
    const el = document.getElementById('command-index');
    if (!el) return { posts: [], projects: [] };
    try {
        return JSON.parse(el.textContent);
    } catch {
        return { posts: [], projects: [] };
    }
}

function fuzzyScore(query, haystack) {
    const q = query.trim().toLowerCase();
    if (!q) return 1;
    const h = haystack.toLowerCase();
    if (h.includes(q)) {
        return 200 + Math.max(0, 80 - h.indexOf(q));
    }

    let score = 0;
    let qi = 0;
    let streak = 0;
    let lastMatch = -2;

    for (let i = 0; i < h.length && qi < q.length; i++) {
        if (h[i] === q[qi]) {
            score += 3 + streak * 2;
            if (i === lastMatch + 1) streak++;
            else streak = 1;
            if (i === 0 || h[i - 1] === ' ') score += 6;
            lastMatch = i;
            qi++;
        }
    }

    return qi === q.length ? score : 0;
}

/** Prefer title hits over body/keyword matches. */
function commandScore(query, cmd) {
    const q = query.trim();
    if (!q) return 1;

    const titleScore = fuzzyScore(q, cmd.label);
    if (titleScore > 0) {
        return titleScore + 400;
    }

    return fuzzyScore(q, cmd.keywords ?? '');
}

function withGroup(cmd, group = 'page') {
    return { group, ...cmd };
}

export function initCommandPalette() {
    const palette = document.getElementById('command-palette');
    const commandInput = document.getElementById('command-input');
    const commandResults = document.getElementById('command-results');
    if (!palette || !commandInput || !commandResults) return;

    const staticCommands = [
        withGroup({
            label: 'Home',
            keywords: 'home landing portfolio',
            action: () => window.location.assign('/'),
        }),
        withGroup({
            label: 'Work — Portfolio',
            keywords: 'work portfolio projects nasa',
            action: () => window.location.assign('/work'),
        }),
        withGroup({
            label: 'About — Experience',
            keywords: 'about experience career background leadership how i lead',
            action: () => window.location.assign('/about'),
        }),
        withGroup({
            label: 'How I Lead',
            keywords: 'how i lead leadership coaching 1:1 feedback em manager',
            action: () => window.location.assign('/about#how-i-lead'),
        }),
        withGroup({
            label: 'Now — Current focus',
            keywords: 'now focus availability engineering manager em staff leadership recruiters',
            action: () => window.location.assign('/now'),
        }),
        withGroup({
            label: 'Resume',
            keywords: 'resume cv curriculum vitae experience pdf print',
            action: () => window.location.assign('/resume'),
        }),
        withGroup({
            label: 'Book a conversation',
            keywords: 'book calendly schedule call conversation hiring recruiter',
            action: () => {
                const url = document.documentElement.dataset.bookingUrl;
                window.location.assign(url || '/now#contact');
            },
        }),
        withGroup({
            label: 'Writing — Blog',
            keywords: 'writing blog posts articles essays notes governance leadership',
            action: () => window.location.assign('/blog'),
        }),
        withGroup({
            label: 'Experience',
            keywords: 'experience career nasa jacobs',
            action: () => gotoSection('experience'),
        }),
        withGroup({
            label: 'Selected Work',
            keywords: 'work portfolio projects',
            action: () => gotoSection('work'),
        }),
        withGroup({
            label: 'Research',
            keywords: 'research publication paper doi geohorizons flood mapping',
            action: () => gotoSection('research'),
        }),
        withGroup({
            label: 'Stack',
            keywords: 'stack tech tools languages',
            action: () => gotoSection('stack'),
        }),
        withGroup({
            label: 'Credentials',
            keywords: 'certs certifications education scrum stats',
            action: () => gotoSection('credentials'),
        }),
        withGroup({
            label: 'Open Source',
            keywords: 'github repos open source',
            action: () => gotoSection('open-source'),
        }),
        withGroup({
            label: 'Contact',
            keywords: 'contact email hire',
            action: () => gotoSection('contact'),
        }),
        withGroup({
            label: 'RSS Feed',
            keywords: 'rss atom feed subscribe',
            action: () => window.open('/feed.xml', '_blank', 'noopener,noreferrer'),
        }),
        withGroup({
            label: 'JSON Feed',
            keywords: 'json feed subscribe',
            action: () => window.open('/feed.json', '_blank', 'noopener,noreferrer'),
        }),
        withGroup({
            label: 'LinkedIn',
            keywords: 'linkedin social',
            action: () =>
                window.open('https://www.linkedin.com/in/khill/', '_blank', 'noopener,noreferrer'),
        }),
        withGroup({
            label: 'GitHub',
            keywords: 'github code',
            action: () =>
                window.open('https://github.com/karlhillx', '_blank', 'noopener,noreferrer'),
        }),
    ];

    const index = parseCommandIndex();
    const bookingUrl = document.documentElement.dataset.bookingUrl;
    const bookingLabel = document.documentElement.dataset.bookingLabel || 'Book a conversation';
    const bookingCommands = bookingUrl
        ? [
              withGroup({
                  label: bookingLabel,
                  keywords: 'book calendar cal.com calendly schedule conversation meeting hire',
                  action: () => window.open(bookingUrl, '_blank', 'noopener,noreferrer'),
              }),
          ]
        : [];
    const commands = [
        ...staticCommands,
        ...bookingCommands,
        ...index.posts.map((post) =>
            withGroup(
                {
                    label: post.label,
                    keywords: post.keywords ?? 'writing blog',
                    action: () => window.location.assign(post.url),
                },
                post.group ?? 'writing'
            )
        ),
        ...index.projects.map((project) =>
            withGroup(
                {
                    label: project.label,
                    keywords: project.keywords ?? 'work portfolio',
                    action: () => window.location.assign(project.url),
                },
                project.group ?? 'work'
            )
        ),
    ];

    let activeCommandIndex = 0;
    const paletteIsOpen = () => palette.matches(':popover-open') ?? false;

    const getFilteredCommands = (query) => {
        const q = query.trim();
        if (!q) return commands;

        return commands
            .map((cmd) => ({
                cmd,
                score: commandScore(q, cmd),
            }))
            .filter(({ score }) => score > 0)
            .sort((a, b) => b.score - a.score || a.cmd.label.localeCompare(b.cmd.label))
            .map(({ cmd }) => cmd);
    };

    const renderCommands = (query) => {
        const filtered = getFilteredCommands(query);
        activeCommandIndex = Math.min(activeCommandIndex, Math.max(filtered.length - 1, 0));
        commandResults.innerHTML = filtered.length
            ? filtered
                  .map((cmd, i) => {
                      const group = GROUP_LABELS[cmd.group] ?? GROUP_LABELS.page;
                      return `
                <button type="button"
                        id="command-result-${i}"
                        role="option"
                        aria-selected="${i === activeCommandIndex ? 'true' : 'false'}"
                        class="command-result ${i === activeCommandIndex ? 'is-active' : ''}"
                        data-command-index="${i}">
                    <span class="command-result__group font-mono">${group}</span>
                    <span class="font-mono text-xs">${cmd.label}</span>
                </button>`;
                  })
                  .join('')
            : '<p class="font-mono text-xs text-neutral-500 px-2 py-2">No matches</p>';
        commandInput.setAttribute(
            'aria-activedescendant',
            filtered.length ? `command-result-${activeCommandIndex}` : ''
        );

        if (filtered.length) {
            commandResults
                .querySelector('.command-result.is-active')
                ?.scrollIntoView({ block: 'nearest' });
        }
    };

    const runCommand = (indexToRun) => {
        const filtered = getFilteredCommands(commandInput.value || '');
        const command = filtered[indexToRun];
        if (!command) return;
        palette.hidePopover();
        command.action();
    };

    palette.addEventListener('toggle', (e) => {
        const open = e.newState === 'open';
        commandInput.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            document.body.style.overflow = 'hidden';
            commandInput.value = '';
            activeCommandIndex = 0;
            renderCommands('');
            setTimeout(() => commandInput.focus(), 0);
        } else {
            document.body.style.removeProperty('overflow');
        }
    });

    commandInput.addEventListener('input', (e) => {
        activeCommandIndex = 0;
        renderCommands(e.target.value);
    });

    commandResults.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-command-index]');
        if (!trigger) return;
        runCommand(Number(trigger.getAttribute('data-command-index')));
    });

    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            palette.togglePopover();
            return;
        }

        if (e.key === '?' && !e.metaKey && !e.ctrlKey && !e.altKey && !paletteIsOpen()) {
            const el = e.target;
            const typing =
                el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
            if (!typing) {
                e.preventDefault();
                palette.showPopover();
                return;
            }
        }

        if (!paletteIsOpen()) return;

        const filtered = getFilteredCommands(commandInput.value || '');
        const count = filtered.length;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (count) activeCommandIndex = (activeCommandIndex + 1) % count;
            renderCommands(commandInput.value || '');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (count) activeCommandIndex = (activeCommandIndex - 1 + count) % count;
            renderCommands(commandInput.value || '');
        } else if (e.key === 'Home') {
            e.preventDefault();
            activeCommandIndex = 0;
            renderCommands(commandInput.value || '');
        } else if (e.key === 'End') {
            e.preventDefault();
            activeCommandIndex = Math.max(count - 1, 0);
            renderCommands(commandInput.value || '');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            runCommand(activeCommandIndex);
        }
    });
}
