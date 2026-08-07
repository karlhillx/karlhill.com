function flashFeedback(feedback) {
    if (!feedback) return;
    feedback.style.opacity = '1';
    clearTimeout(feedback._t);
    feedback._t = setTimeout(() => {
        feedback.style.opacity = '0';
    }, 1800);
}

export function initShareAndCopy() {
    if (typeof navigator.share === 'function') {
        document.querySelectorAll('[data-native-share]').forEach((btn) => {
            btn.hidden = false;
            btn.addEventListener('click', async () => {
                const url = btn.getAttribute('data-share-url');
                if (!url) return;
                try {
                    await navigator.share({
                        title: btn.getAttribute('data-share-title') || document.title,
                        text: btn.getAttribute('data-share-text') || '',
                        url,
                    });
                } catch (err) {
                    if (err && err.name === 'AbortError') return;
                }
            });
        });
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

    document.querySelectorAll('.prose-karl pre.notranslate').forEach((pre) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'code-copy-btn font-mono';
        btn.setAttribute('aria-label', 'Copy code');
        btn.textContent = 'Copy';

        const feedback = document.createElement('span');
        feedback.className = 'code-copy-feedback font-mono';
        feedback.setAttribute('aria-hidden', 'true');
        feedback.textContent = 'Copied';

        pre.append(btn, feedback);

        btn.addEventListener('click', async () => {
            const code = pre.querySelector('code')?.textContent ?? pre.textContent;
            if (!code) return;
            try {
                await navigator.clipboard.writeText(code.trim());
                feedback.classList.add('is-visible');
                clearTimeout(feedback._t);
                feedback._t = setTimeout(() => feedback.classList.remove('is-visible'), 1800);
            } catch {
                window.prompt('Copy code', code.trim());
            }
        });
    });
}
