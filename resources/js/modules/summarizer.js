/**
 * Progressive on-device summaries via the Summarizer API (Chrome 138+).
 * Hidden unless the API exists and the model can run. Never starts a download
 * during prerender — wait for activation, then only create() on click.
 *
 * @see https://developer.chrome.com/docs/ai/summarizer-api
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

function sourceText(root) {
    const selector = root.dataset.summarySource || '[data-summary-source]';
    const node = document.querySelector(selector);
    const text = (node?.innerText || '').replace(/\s+\n/g, '\n').trim();
    if (text.length <= SOURCE_CHAR_LIMIT) {
        return text;
    }

    return text.slice(0, SOURCE_CHAR_LIMIT);
}

export function initOnDeviceSummary() {
    if (!('Summarizer' in self)) {
        return;
    }

    const roots = document.querySelectorAll('[data-on-device-summary]');
    if (roots.length === 0) {
        return;
    }

    whenActivated().then(() => {
        roots.forEach((root) => revealIfAvailable(root));
    });
}

async function revealIfAvailable(root) {
    try {
        const availability = await Summarizer.availability();
        if (availability === 'unavailable') {
            return;
        }
    } catch {
        return;
    }

    root.hidden = false;
    const button = root.querySelector('[data-summary-run]');
    const status = root.querySelector('[data-summary-status]');
    const output = root.querySelector('[data-summary-output]');
    if (!button || !status || !output) {
        return;
    }

    let abort = null;

    button.addEventListener('click', async () => {
        if (abort) {
            abort.abort();
            abort = null;
        }

        const input = sourceText(root);
        if (input === '') {
            status.textContent = 'Nothing to summarize on this page.';
            return;
        }

        abort = new AbortController();
        const { signal } = abort;

        button.disabled = true;
        button.setAttribute('aria-expanded', 'true');
        output.hidden = false;
        output.textContent = '';
        status.textContent = 'Starting on-device model…';

        try {
            const summarizer = await Summarizer.create({
                type: root.dataset.summaryType || 'tldr',
                format: 'plain-text',
                length: root.dataset.summaryLength || 'short',
                expectedInputLanguages: ['en'],
                outputLanguage: 'en',
                sharedContext: root.dataset.summaryContext || '',
                monitor(monitor) {
                    monitor.addEventListener('downloadprogress', (event) => {
                        const loaded = Number(event.loaded);
                        const pct = Number.isFinite(loaded) ? Math.round(loaded * 100) : 0;
                        status.textContent =
                            pct < 100 ? `Downloading on-device model… ${pct}%` : 'Summarizing…';
                    });
                },
            });

            if (signal.aborted) {
                summarizer.destroy?.();
                return;
            }

            status.textContent = 'Summarizing…';
            const stream = summarizer.summarizeStreaming(input, { signal });
            let text = '';
            for await (const chunk of stream) {
                text += chunk;
                output.textContent = text;
            }
            status.textContent = 'On-device summary';
            summarizer.destroy?.();
        } catch (error) {
            if (error?.name === 'AbortError') {
                return;
            }
            status.textContent = 'Couldn’t summarize in this browser.';
            output.hidden = true;
        } finally {
            button.disabled = false;
            abort = null;
        }
    });
}
