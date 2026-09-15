/**
 * Progressive on-device Q&A via the Prompt API (LanguageModel, Chrome 138+).
 * Hidden unless the API exists. Never starts a download during prerender.
 */

const SOURCE_CHAR_LIMIT = 12000;

const SYSTEM_PROMPT =
    'You answer hiring questions about Karl Hill using only the provided brief and page text. ' +
    'Questions about what he wants, is open to, looking for, or the next role refer to Open to and Direction. ' +
    'Be specific and concise. Do not invent employers, titles, metrics, or dates. ' +
    'If the brief or page covers it, answer. If it does not, say you do not know.';

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

function briefText(root) {
    const node = root.querySelector('[data-ask-brief]');

    return (node?.textContent || '').replace(/\s+\n/g, '\n').trim();
}

function sourceText(root) {
    const selector = root.dataset.askFrom || '[data-ask-source]';
    const nodes = [...document.querySelectorAll(selector)].filter(
        (node) => node !== root && !root.contains(node) && !node.closest('[data-on-device-ask]')
    );

    const parts = nodes.map((node) => {
        const clone = node.cloneNode(true);
        clone.querySelectorAll('[data-on-device-ask]').forEach((el) => el.remove());

        return (clone.innerText || '').replace(/\s+\n/g, '\n').trim();
    });

    const text = parts.filter(Boolean).join('\n\n').trim();
    if (text.length <= SOURCE_CHAR_LIMIT) {
        return text;
    }

    return text.slice(0, SOURCE_CHAR_LIMIT);
}

function userPrompt(brief, page, question) {
    const sections = [];
    if (brief) {
        sections.push(`Brief:\n${brief}`);
    }
    if (page) {
        sections.push(`Page:\n${page}`);
    }

    return `${sections.join('\n\n')}\n\nQuestion: ${question}`;
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

    const runQuestion = (question) => {
        input.value = question;
        form.requestSubmit();
    };

    cancelButton?.addEventListener('click', () => abort?.abort());

    root.querySelectorAll('[data-ask-prompt]').forEach((chip) => {
        chip.addEventListener('click', () => {
            const question = (chip.dataset.askPrompt || chip.textContent || '').trim();
            if (question !== '') {
                runQuestion(question);
            }
        });
    });

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

        const brief = briefText(root);
        const page = sourceText(root);
        if (brief === '' && page === '') {
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

        const prompt = `${SYSTEM_PROMPT}\n\n${userPrompt(brief, page, question)}`;

        try {
            const session = await createSession(Model, signal);

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

async function createSession(Model, signal) {
    const base = {
        expectedInputs: [{ type: 'text', languages: ['en'] }],
        expectedOutputs: [{ type: 'text', languages: ['en'] }],
        signal,
    };

    try {
        return await Model.create({
            ...base,
            initialPrompts: [{ role: 'system', content: SYSTEM_PROMPT }],
        });
    } catch {
        return Model.create(base);
    }
}
