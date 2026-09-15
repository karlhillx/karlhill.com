/**
 * Progressive on-device Q&A via the Prompt API (LanguageModel, Chrome 138+).
 * Hidden unless the API exists. Never starts a download during prerender.
 */

const SOURCE_CHAR_LIMIT = 12000;

function whenActivated() {
    if (!document.prerendering) {
        return Promise.resolve();
    }

    return new Promise((resolve) => {
        document.addEventListener('prerenderingchange', resolve, { once: true });
    });
}

function languageModel() {
    if ('LanguageModel' in self) {
        return self.LanguageModel;
    }

    return self.ai?.languageModel ?? null;
}

function sourceText(root) {
    const selector = root.dataset.askSource || '[data-ask-source]';
    const node =
        root.closest('[data-ask-source]') ||
        document.querySelector(selector) ||
        document.querySelector('[data-ask-source]');
    const text = (node?.innerText || '').replace(/\s+\n/g, '\n').trim();
    if (text.length <= SOURCE_CHAR_LIMIT) {
        return text;
    }

    return text.slice(0, SOURCE_CHAR_LIMIT);
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

export function initOnDeviceAsk() {
    const Model = languageModel();
    if (!Model || typeof Model.availability !== 'function') {
        return;
    }

    const roots = document.querySelectorAll('[data-on-device-ask]');
    if (roots.length === 0) {
        return;
    }

    whenActivated().then(() => {
        roots.forEach((root) => revealIfAvailable(root, Model));
    });
}

async function revealIfAvailable(root, Model) {
    let availability;
    try {
        availability = await Model.availability();
        if (availability === 'unavailable' || availability === 'no') {
            return;
        }
    } catch {
        return;
    }

    root.hidden = false;
    const form = root.querySelector('[data-ask-form]');
    const input = root.querySelector('[data-ask-input]');
    const button = root.querySelector('[data-ask-run]');
    const cancelButton = root.querySelector('[data-ask-cancel]');
    const status = root.querySelector('[data-ask-status]');
    const output = root.querySelector('[data-ask-output]');
    if (!form || !input || !button || !status || !output) {
        return;
    }

    const idleHint = 'Chrome on-device · nothing leaves this device';
    status.textContent = idleHint;

    let abort = null;

    const resetChrome = () => {
        root.classList.remove('is-running');
        root.removeAttribute('aria-busy');
        button.disabled = false;
        input.disabled = false;
        button.setAttribute('aria-expanded', output.hidden ? 'false' : 'true');
        if (cancelButton) {
            cancelButton.hidden = true;
        }
        abort = null;
    };

    cancelButton?.addEventListener('click', () => abort?.abort());

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const question = input.value.trim();
        if (question === '') {
            input.focus();
            return;
        }

        if (abort) {
            abort.abort();
        }

        const page = sourceText(root);
        if (page === '') {
            status.textContent = 'Nothing on this page to answer from.';
            return;
        }

        abort = new AbortController();
        const { signal } = abort;

        root.classList.add('is-running');
        root.setAttribute('aria-busy', 'true');
        button.disabled = true;
        input.disabled = true;
        button.setAttribute('aria-expanded', 'true');
        if (cancelButton) {
            cancelButton.hidden = false;
        }
        output.hidden = false;
        output.classList.remove('is-ready', 'is-error');
        output.classList.add('is-busy');
        output.textContent = 'Reading this page…';
        status.textContent = 'On-device · Gemini Nano';

        const context = root.dataset.askContext ? `${root.dataset.askContext}\n\n` : '';
        const prompt =
            `${context}Answer only from the page below. If the page does not say, say you do not know.\n\n` +
            `Page:\n${page}\n\nQuestion: ${question}`;

        try {
            const session = await Model.create({
                expectedInputs: [{ type: 'text', languages: ['en'] }],
                expectedOutputs: [{ type: 'text', languages: ['en'] }],
                signal,
            });

            if (signal.aborted) {
                session.destroy?.();
                return;
            }

            let text = '';
            let streamed = false;

            if (typeof session.promptStreaming === 'function') {
                try {
                    const stream = session.promptStreaming(prompt, { signal });
                    for await (const chunk of stream) {
                        if (!streamed) {
                            output.classList.remove('is-busy');
                            output.classList.add('is-ready');
                            output.textContent = '';
                            streamed = true;
                        }
                        text += chunk;
                        output.textContent = text;
                    }
                } catch (streamErr) {
                    if (streamErr?.name === 'AbortError') {
                        throw streamErr;
                    }
                }
            }

            if (!streamed || !text.trim()) {
                text = await session.prompt(prompt, { signal });
                output.classList.remove('is-busy');
                output.classList.add('is-ready');
                output.textContent = text;
            }

            status.textContent = 'On-device answer · Gemini Nano';
            session.destroy?.();
        } catch (error) {
            if (error?.name === 'AbortError') {
                status.textContent = idleHint;
                output.hidden = true;
                output.classList.remove('is-busy', 'is-ready', 'is-error');
                output.replaceChildren();
                return;
            }
            status.textContent = 'Couldn’t answer in this browser.';
            output.classList.remove('is-busy', 'is-ready');
            output.classList.add('is-error');
            output.innerHTML = `<p>Couldn’t start the on-device model. ${escapeHtml(error?.message || 'Try Chrome with Gemini Nano enabled.')}</p>`;
        } finally {
            resetChrome();
        }
    });
}
