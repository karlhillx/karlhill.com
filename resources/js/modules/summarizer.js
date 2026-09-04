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
        status.textContent = 'Chrome on-device · Model downloading in background…';
    } else if (isDownloadable) {
        status.textContent = 'Chrome on-device · First run downloads Gemini Nano (~1.5 GB)';
    } else {
        status.textContent = 'Chrome on-device · Model ready · Nothing leaves this device';
    }

    let abort = null;
    let elapsedInterval = null;

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

        button.disabled = true;
        button.setAttribute('aria-expanded', 'true');
        if (cancelButton) {
            cancelButton.hidden = false;
        }
        output.hidden = false;

        // Re-check current availability dynamically
        let currentAvailability = availability;
        try {
            currentAvailability = await Summarizer.availability(CORE_OPTIONS);
        } catch {
            // Keep previous availability
        }

        const needsDownload =
            currentAvailability === 'downloadable' ||
            currentAvailability === 'downloading' ||
            currentAvailability === 'after-download';

        let elapsedSeconds = 0;

        if (needsDownload) {
            status.textContent = 'Downloading on-device model…';
            output.innerHTML = `
                <div class="space-y-3 font-mono text-caption">
                    <div class="flex items-center justify-between text-accent">
                        <span data-summary-progress-label>Downloading Gemini Nano (~1.5 GB)…</span>
                        <div class="flex items-center gap-2">
                            <span data-summary-elapsed class="text-neutral-500 text-[11px]">(0s)</span>
                            <span data-summary-progress-pct class="text-neutral-300 font-semibold">0%</span>
                        </div>
                    </div>
                    <div class="w-full h-1.5 bg-neutral-800 rounded-full overflow-hidden">
                        <div data-summary-progress-bar class="h-full bg-accent transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">
                        Chrome is downloading the local model. First-time setup usually takes 1–3 minutes.
                    </p>
                    <div data-summary-stall-notice class="hidden mt-3 pt-3 border-t border-neutral-800 text-[11px] text-neutral-400 space-y-2 leading-relaxed">
                        <p class="text-neutral-200 font-semibold">Download stuck at 0%? Chrome requires a one-time flag to bypass server gatekeeping:</p>
                        <ol class="list-decimal pl-4 space-y-1.5 text-neutral-300">
                            <li>Open <span class="text-accent underline font-semibold select-all">chrome://flags/#optimization-guide-on-device-model</span> and set to <strong class="text-neutral-100">Enabled BypassPrefRequirement</strong>.</li>
                            <li>Open <span class="text-accent underline font-semibold select-all">chrome://flags/#prompt-api-for-gemini-nano</span> and set to <strong class="text-neutral-100">Enabled</strong>.</li>
                            <li>Relaunch Chrome.</li>
                            <li>Visit <span class="text-accent underline font-semibold select-all">chrome://components</span>, find <strong>Optimization Guide On Device Model</strong>, and click <strong>Check for update</strong>.</li>
                        </ol>
                        <p class="text-neutral-500 pt-1">
                            Also verify your device is connected to power and has &gt;22 GB free disk space.
                        </p>
                    </div>
                </div>
            `;
        } else {
            status.textContent = 'Starting on-device model…';
            output.innerHTML = `
                <div class="space-y-2 font-mono text-caption">
                    <div class="flex items-center gap-2.5 text-neutral-300">
                        <span class="inline-block w-2 h-2 rounded-full bg-accent animate-pulse" aria-hidden="true"></span>
                        <span data-summary-loading-text>Loading Gemini Nano into memory…</span>
                        <span data-summary-elapsed class="text-neutral-500 text-[11px]">(0s)</span>
                    </div>
                    <p class="text-neutral-500 text-[11px]">Initializing local model session…</p>
                    <div data-summary-stall-notice class="hidden mt-3 pt-3 border-t border-neutral-800 text-[11px] text-neutral-400 space-y-1.5 leading-relaxed">
                        <p class="text-neutral-300 font-semibold">Model taking long to initialize?</p>
                        <p class="text-neutral-400">
                            Chrome may be downloading weights in the background or compiling GPU shaders.
                            Check status at <span class="text-accent underline cursor-pointer select-all">chrome://on-device-internals</span> or <span class="text-accent underline cursor-pointer select-all">chrome://components</span>.
                        </p>
                    </div>
                </div>
            `;
        }

        elapsedInterval = setInterval(() => {
            elapsedSeconds++;
            const elapsedSpan = output.querySelector('[data-summary-elapsed]');
            if (elapsedSpan) {
                elapsedSpan.textContent = `(${elapsedSeconds}s)`;
            }

            const stallNotice = output.querySelector('[data-summary-stall-notice]');
            if (stallNotice && elapsedSeconds >= 8) {
                stallNotice.classList.remove('hidden');
            }

            if (elapsedSeconds === 8) {
                const loadingText = output.querySelector('[data-summary-loading-text]');
                if (loadingText && !needsDownload) {
                    loadingText.textContent = 'Compiling shaders & loading local model…';
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
                        status.textContent =
                            safePct < 100
                                ? `Downloading on-device model… ${safePct}%`
                                : 'Extracting and starting model…';

                        const label = output.querySelector('[data-summary-progress-label]');
                        const pctSpan = output.querySelector('[data-summary-progress-pct]');
                        const bar = output.querySelector('[data-summary-progress-bar]');
                        if (label) {
                            label.textContent =
                                safePct < 100
                                    ? `Downloading Gemini Nano… ${safePct}%`
                                    : 'Model downloaded. Starting session…';
                        }
                        if (pctSpan) pctSpan.textContent = `${safePct}%`;
                        if (bar) bar.style.width = `${safePct}%`;
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

            clearInterval(elapsedInterval);
            status.textContent = 'Summarizing…';
            output.innerHTML = `
                <div class="flex items-center gap-2.5 font-mono text-caption text-neutral-400">
                    <span class="inline-block w-2 h-2 rounded-full bg-accent animate-ping" aria-hidden="true"></span>
                    <span>Generating summary…</span>
                </div>
            `;

            let text = '';
            let hasStreamed = false;

            if (typeof summarizer.summarizeStreaming === 'function') {
                try {
                    const stream = summarizer.summarizeStreaming(input, { signal });
                    for await (const chunk of stream) {
                        if (!hasStreamed) {
                            output.textContent = '';
                            hasStreamed = true;
                        }
                        text += chunk;
                        output.textContent = text;
                    }
                } catch (streamErr) {
                    if (streamErr?.name === 'AbortError') throw streamErr;
                }
            }

            if (!hasStreamed || !text.trim()) {
                text = await summarizer.summarize(input, { signal });
                output.textContent = text;
            }

            status.textContent = 'On-device summary (Gemini Nano)';
            summarizer.destroy?.();
        } catch (error) {
            clearInterval(elapsedInterval);
            if (error?.name === 'AbortError') {
                status.textContent = 'Summary cancelled.';
                output.hidden = true;
                return;
            }
            status.textContent = 'Couldn’t summarize in this browser.';
            output.innerHTML = `
                <div class="space-y-2 font-mono text-caption">
                    <p class="text-neutral-300">
                        Failed to start on-device summary: ${error?.message || 'Model download or execution halted'}.
                    </p>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">
                        To run local models in Chrome, open <span class="text-accent">chrome://components</span> and verify "Optimization Guide On Device Model" is up to date, or inspect <span class="text-accent">chrome://on-device-internals</span>.
                    </p>
                </div>
            `;
        } finally {
            clearInterval(elapsedInterval);
            button.disabled = false;
            button.removeAttribute('aria-expanded');
            if (cancelButton) {
                cancelButton.hidden = true;
            }
            abort = null;
        }
    });
}
