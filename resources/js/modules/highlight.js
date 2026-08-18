/**
 * CSS Custom Highlight API — paint in-article matches from ⌘K or #:~:text=.
 */
export function initHighlight() {
    if (!('highlights' in CSS)) return;

    const root =
        document.querySelector('[data-article] .prose-karl') ||
        document.querySelector('[data-article] [data-article-title]')?.closest('[data-article]');
    if (!root) return;

    const apply = (query) => {
        CSS.highlights.delete('search');
        const q = query.trim();
        if (q.length < 3) return;

        const needle = q.toLowerCase();
        const ranges = [];
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        while (walker.nextNode()) {
            const node = walker.currentNode;
            const text = node.textContent || '';
            const hay = text.toLowerCase();
            let from = 0;
            while (from < hay.length) {
                const index = hay.indexOf(needle, from);
                if (index === -1) break;
                const range = new Range();
                range.setStart(node, index);
                range.setEnd(node, index + needle.length);
                ranges.push(range);
                from = index + needle.length;
            }
        }
        if (ranges.length) {
            CSS.highlights.set('search', new Highlight(...ranges));
        }
    };

    const commandInput = document.getElementById('command-input');
    commandInput?.addEventListener('input', (event) => {
        apply(event.target.value || '');
    });

    const fragment = location.hash.match(/:~:text=([^&]+)/);
    if (fragment) {
        try {
            apply(decodeURIComponent(fragment[1].replace(/,/g, ' ')));
        } catch {
            /* malformed text fragment */
        }
    }
}
