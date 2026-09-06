/**
 * Progressive on-device summaries via the Summarizer API (Chrome 138+).
 * Hidden unless the API exists and the model can run. Never starts a download
 * during prerender — wait for activation, then only create() on click.
 *
 * @see https://developer.chrome.com/docs/ai/summarizer-api
 */

const SOURCE_CHAR_LIMIT = 12000;
const CORE_OPTIONS = {
    expectedInputLanguages: ['en'],
    outputLanguage: 'en',
};

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

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

function busyMarkup({ downloading }) {
    const title = downloading ? 'Downloading Gemini Nano' : 'Starting on-device model';
    const note = downloading
        ? 'First-time setup, then the model stays in this browser. Usually 1–3 minutes.'
        : 'Gemini Nano runs locally. Nothing is sent off this device.';
    const helpTitle = downloading ? 'Download stuck at 0%?' : 'Taking longer than expected?';
    const helpBody = downloading
        ? `<p>${helpTitle} Chrome needs a one-time flag:</p>
            <ol>
                <li>Set <span class="summary-panel__code">chrome://flags/#optimization-guide-on-device-model</span> to <strong>Enabled BypassPrefRequirement</strong>.</li>
                <li>Set <span class="summary-panel__code">chrome://flags/#prompt-api-for-gemini-nano</span> to <strong>Enabled</strong>, then relaunch.</li>
                <li>In <span class="summary-panel__code">chrome://components</span>, update <strong>Optimization Guide On Device Model</strong>.</li>
            </ol>
            <p>Stay on power, and keep more than 22 GB free.</p>`
        : `<p>${helpTitle} Chrome may still be fetching weights or compiling shaders. Check <span class="summary-panel__code">chrome://on-device-internals</span> or <span class="summary-panel__code">chrome://components</span>.</p>`;

    return `<div class="summary-panel">
        <div class="summary-panel__status">
            <span class="summary-panel__live" aria-hidden="true"></span>
            <p class="summary-panel__title" data-summary-title>${title}</p>
            <span class="summary-panel__elapsed" data-summary-elapsed>0s</span>
        </div>
        <div class="summary-panel__meter" data-summary-progress ${downloading ? '' : 'hidden'}>
            <span data-summary-progress-bar></span>
        </div>
        <p class="summary-panel__note" data-summary-note>${note}</p>
        <div class="summary-panel__help" data-summary-stall-notice hidden>${helpBody}</div>
    </div>`;
}

function setOutputState(output, state) {
    output.classList.remove('is-busy', 'is-ready', 'is-error');
    if (state) {
        output.classList.add(`is-${state}`);
    }
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
    let availability;
    try {
        availability = await Summarizer.availability(CORE_OPTIONS);
        if (availability === 'unavailable' || availability === 'no') {
            return;
        }
    } catch {
        return;
    }

    root.hidden = false;
    const button = root.querySelector('[data-summary-run]');
    const cancelButton = root.querySelector('[data-summary-cancel]');
    const status = root.querySelector('[data-summary-status]');
    const output = root.querySelector('[data-summary-output]');
    if (!button || !status || !output) {
        return;
    }

    const isDownloading = availability === 'downloading';
    const isDownloadable = availability === 'downloadable' || availability === 'after-download';

    if (isDownloading) {
        status.textContent = 'Chrome on-device · Model downloading in background';
    } else if (isDownloadable) {
        status.textContent = 'Chrome on-device · First run downloads Gemini Nano (~1.5 GB)';
    } else {
        status.textContent = 'Chrome on-device · Model ready · Nothing leaves this device';
    }

    const idleHint = status.textContent;
    let abort = null;
    let elapsedInterval = null;

    const stopElapsed = () => {
        if (elapsedInterval) {
            clearInterval(elapsedInterval);
            elapsedInterval = null;
        }
    };

    const resetChrome = () => {
        stopElapsed();
        root.classList.remove('is-running');
        root.removeAttribute('aria-busy');
        button.disabled = false;
        button.setAttribute('aria-expanded', output.hidden ? 'false' : 'true');
        if (cancelButton) {
            cancelButton.hidden = true;
        }
        abort = null;
    };

    if (cancelButton) {
        cancelButton.addEventListener('click', () => {
            if (abort) {
                abort.abort();
            }
        });
    }

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

        root.classList.add('is-running');
        root.setAttribute('aria-busy', 'true');
        button.disabled = true;
        button.setAttribute('aria-expanded', 'true');
        if (cancelButton) {
            cancelButton.hidden = false;
        }
        output.hidden = false;
        setOutputState(output, 'busy');

        let currentAvailability = availability;
        try {
            currentAvailability = await Summarizer.availability(CORE_OPTIONS);
        } catch {
            // Keep previous availability
        }

        let needsDownload =
            currentAvailability === 'downloadable' ||
            currentAvailability === 'downloading' ||
            currentAvailability === 'after-download';

        output.innerHTML = busyMarkup({ downloading: needsDownload });

        let elapsedSeconds = 0;
        elapsedInterval = setInterval(() => {
            elapsedSeconds += 1;
            const elapsedSpan = output.querySelector('[data-summary-elapsed]');
            if (elapsedSpan) {
                elapsedSpan.textContent = `${elapsedSeconds}s`;
            }

            const stallNotice = output.querySelector('[data-summary-stall-notice]');
            if (stallNotice && elapsedSeconds >= 8) {
                stallNotice.hidden = false;
            }

            if (elapsedSeconds === 8 && !needsDownload) {
                const title = output.querySelector('[data-summary-title]');
                const note = output.querySelector('[data-summary-note]');
                if (title) {
                    title.textContent = 'Compiling the local model';
                }
                if (note) {
                    note.textContent =
                        'Chrome may be compiling shaders. This usually finishes shortly.';
                }
            }
        }, 1000);

        try {
            const createOptions = {
                type: root.dataset.summaryType || 'tldr',
                format: 'plain-text',
                length: root.dataset.summaryLength || 'short',
                expectedInputLanguages: ['en'],
                outputLanguage: 'en',
                signal,
                monitor(monitor) {
                    monitor.addEventListener('downloadprogress', (event) => {
                        const loaded = Number(event.loaded);
                        const total = Number(event.total) || 1;
                        const pct =
                            total <= 1 && loaded <= 1
                                ? Math.round(loaded * 100)
                                : Math.round((loaded / total) * 100);
                        const safePct = Math.min(100, Math.max(0, Number.isFinite(pct) ? pct : 0));
                        needsDownload = true;

                        const meter = output.querySelector('[data-summary-progress]');
                        const bar = output.querySelector('[data-summary-progress-bar]');
                        const title = output.querySelector('[data-summary-title]');
                        const note = output.querySelector('[data-summary-note]');
                        if (meter) {
                            meter.hidden = false;
                        }
                        if (bar) {
                            bar.style.width = `${safePct}%`;
                        }
                        if (title) {
                            title.textContent =
                                safePct < 100
                                    ? `Downloading Gemini Nano · ${safePct}%`
                                    : 'Model downloaded · starting session';
                        }
                        if (note && safePct < 100) {
                            note.textContent =
                                'First-time setup, then the model stays in this browser. Usually 1–3 minutes.';
                        }
                    });
                },
            };

            if (root.dataset.summaryContext) {
                createOptions.sharedContext = root.dataset.summaryContext;
            }

            const summarizer = await Summarizer.create(createOptions);

            if (signal.aborted) {
                summarizer.destroy?.();
                return;
            }

            stopElapsed();
            const title = output.querySelector('[data-summary-title]');
            const note = output.querySelector('[data-summary-note]');
            const meter = output.querySelector('[data-summary-progress]');
            const help = output.querySelector('[data-summary-stall-notice]');
            if (title) {
                title.textContent = 'Writing the summary';
            }
            if (note) {
                note.textContent = 'On-device · Gemini Nano';
            }
            if (meter) {
                meter.hidden = true;
            }
            if (help) {
                help.hidden = true;
            }

            let text = '';
            let hasStreamed = false;

            if (typeof summarizer.summarizeStreaming === 'function') {
                try {
                    const stream = summarizer.summarizeStreaming(input, { signal });
                    for await (const chunk of stream) {
                        if (!hasStreamed) {
                            setOutputState(output, 'ready');
                            output.textContent = '';
                            hasStreamed = true;
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

            if (!hasStreamed || !text.trim()) {
                text = await summarizer.summarize(input, { signal });
                setOutputState(output, 'ready');
                output.textContent = text;
            }

            status.textContent = 'On-device summary · Gemini Nano';
            summarizer.destroy?.();
        } catch (error) {
            stopElapsed();
            if (error?.name === 'AbortError') {
                status.textContent = idleHint;
                output.hidden = true;
                setOutputState(output, null);
                output.replaceChildren();
                return;
            }
            status.textContent = 'Couldn’t summarize in this browser.';
            setOutputState(output, 'error');
            output.innerHTML = `<div class="summary-panel">
                <p class="summary-panel__title">Couldn’t start the on-device model</p>
                <p class="summary-panel__note">${escapeHtml(error?.message || 'Download or execution halted')}.</p>
                <p class="summary-panel__note">Check <span class="summary-panel__code">chrome://components</span> for Optimization Guide On Device Model, or <span class="summary-panel__code">chrome://on-device-internals</span>.</p>
            </div>`;
        } finally {
            resetChrome();
        }
    });
}
