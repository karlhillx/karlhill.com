/**
 * Copy-to-clipboard for `[data-copy-text]` buttons (footer email on every
 * page). Kept in the core bundle so it does not drag the contact-form chunk
 * onto pages that have no form.
 */
export function initCopyText() {
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
                if (!feedback) return;
                feedback.style.opacity = '1';
                clearTimeout(feedback._t);
                feedback._t = setTimeout(() => {
                    feedback.style.opacity = '0';
                }, 1800);
            } catch {
                window.prompt('Copy', text);
            }
        });
    });
}
