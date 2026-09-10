import { showToast } from './toast.js';
import { trackEvent } from './analytics.js';

const TURNSTILE_SRC = 'https://challenges.cloudflare.com/turnstile/v0/api.js';

let turnstileLoader = null;

/**
 * Progressive enhancement: fetch submit keeps the visitor on-page with
 * inline success / field errors. Non-JS still posts and redirects.
 * Copy-to-clipboard buttons live in copy-text.js (core) since the footer
 * email button is on every page while the form is not.
 */
export function initContactForms() {
    document.querySelectorAll('[data-contact-form], .js-contact-form').forEach((contactForm) => {
        if (!(contactForm instanceof HTMLFormElement)) return;

        const tokenInput = contactForm.querySelector('input[name="_token"]');
        const submitBtn = contactForm.querySelector('[data-contact-submit]');
        const statusEl = ensureStatusRegion(contactForm);
        const fieldsEl = contactForm.querySelector('[data-contact-fields]');

        const setSubmitting = (busy) => {
            if (!submitBtn) return;
            submitBtn.disabled = busy;
            submitBtn.setAttribute('aria-busy', busy ? 'true' : 'false');
            if (!submitBtn.dataset.originalHtml) {
                submitBtn.dataset.originalHtml = submitBtn.innerHTML;
            }
            submitBtn.innerHTML = busy ? 'Sending…' : submitBtn.dataset.originalHtml;
        };

        let tokenReady = null;
        const ensureToken = () => {
            tokenReady ??= fetch('/csrf-token', {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            })
                .then((res) => (res.ok ? res.json() : null))
                .then((data) => {
                    if (data && data.token && tokenInput) tokenInput.value = data.token;
                })
                .catch(() => {});
            return tokenReady;
        };

        const warm = () => {
            ensureToken();
            loadTurnstile();
        };

        if (contactForm.hasAttribute('data-contact-complete')) {
            // Non-JS submit: the server redirected back with a one-shot flash,
            // so this render is the only chance to record the conversion.
            trackEvent('contact_form_submitted', { method: 'redirect' });
            return;
        }

        contactForm.addEventListener('focusin', warm, { once: true });
        watchTurnstile(contactForm, warm);

        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            setSubmitting(true);
            clearFieldErrors(contactForm);
            statusEl.hidden = true;
            statusEl.textContent = '';
            statusEl.className = 'contact-form-status';

            Promise.all([ensureToken(), loadTurnstile()])
                .then(() =>
                    fetch(contactForm.action, {
                        method: 'POST',
                        body: new FormData(contactForm),
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Contact-Ajax': '1',
                        },
                    })
                )
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));

                    if (res.status === 422) {
                        applyFieldErrors(contactForm, data.errors || {});
                        resetTurnstile(contactForm);
                        statusEl.hidden = false;
                        statusEl.className = 'contact-form-status contact-form-status--error';
                        statusEl.setAttribute('role', 'alert');
                        statusEl.textContent = 'Please fix the highlighted fields and try again.';
                        return;
                    }

                    if (res.status === 503 || data.status === 'contact-failed') {
                        const msg =
                            data.message ||
                            `Couldn't send that. Email me at ${data.email || 'the address on this page'}.`;
                        resetTurnstile(contactForm);
                        statusEl.hidden = false;
                        statusEl.className = 'contact-form-status contact-form-status--error';
                        statusEl.setAttribute('role', 'alert');
                        statusEl.textContent = msg;
                        showToast(msg, 'error');
                        return;
                    }

                    if (!res.ok) {
                        throw new Error(`Contact failed (${res.status})`);
                    }

                    const msg = data.message || 'Thanks — message sent.';
                    contactForm.reset();
                    await ensureToken();
                    resetTurnstile(contactForm);
                    contactForm.setAttribute('data-contact-complete', '');
                    if (fieldsEl) fieldsEl.hidden = true;
                    renderSuccess(statusEl, msg, data);
                    showToast(msg, 'success');
                    trackEvent('contact_form_submitted', { method: 'fetch' });
                    statusEl.focus?.();
                })
                .catch(() => {
                    resetTurnstile(contactForm);
                    statusEl.hidden = false;
                    statusEl.className = 'contact-form-status contact-form-status--error';
                    statusEl.setAttribute('role', 'alert');
                    statusEl.textContent =
                        'Something went wrong sending that. You can still email me directly.';
                })
                .finally(() => setSubmitting(false));
        });
    });
}

function watchTurnstile(form, load) {
    if (!form.querySelector('.cf-turnstile[data-sitekey]')) return;
    if (typeof IntersectionObserver !== 'function') return;

    const io = new IntersectionObserver(
        (entries) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                load();
                io.disconnect();
            }
        },
        { rootMargin: '200px 0px' }
    );
    io.observe(form);
}

function loadTurnstile() {
    if (typeof window.turnstile === 'object' && window.turnstile) {
        return Promise.resolve();
    }
    if (!document.querySelector('.cf-turnstile[data-sitekey]')) {
        return Promise.resolve();
    }
    if (turnstileLoader) return turnstileLoader;

    turnstileLoader = new Promise((resolve) => {
        const existing = document.querySelector('script[data-turnstile-api]');
        if (existing) {
            if (typeof window.turnstile === 'object' && window.turnstile) {
                resolve();
                return;
            }
            existing.addEventListener('load', () => resolve(), { once: true });
            existing.addEventListener('error', () => resolve(), { once: true });
            return;
        }

        const script = document.createElement('script');
        script.src = TURNSTILE_SRC;
        script.async = true;
        script.dataset.turnstileApi = '';
        const nonce = document.querySelector('script[nonce]')?.nonce;
        if (nonce) script.setAttribute('nonce', nonce);
        script.addEventListener('load', () => resolve(), { once: true });
        script.addEventListener('error', () => resolve(), { once: true });
        document.head.append(script);
    });

    return turnstileLoader;
}

function resetTurnstile(form) {
    const widget = form.querySelector('.cf-turnstile');
    const api = window.turnstile;
    if (!widget || typeof api?.reset !== 'function') return;
    try {
        api.reset(widget);
    } catch {
        try {
            api.reset();
        } catch {
            /* widget already gone */
        }
    }
}

function renderSuccess(statusEl, msg, data) {
    statusEl.hidden = false;
    statusEl.className = 'contact-form-status contact-form-status--success';
    statusEl.setAttribute('role', 'status');
    statusEl.replaceChildren();

    const p = document.createElement('p');
    p.textContent = msg;
    statusEl.append(p);

    if (data.booking_url) {
        const next = document.createElement('p');
        next.className = 'contact-form-status__next';
        next.append('You can also book a conversation. ');
        const a = document.createElement('a');
        a.href = data.booking_url;
        a.className = 'text-accent underline underline-offset-2 hover:decoration-accent';
        a.textContent = data.booking_label || 'Book a conversation';
        next.append(a);
        statusEl.append(next);
    }
}

function ensureStatusRegion(form) {
    let el = form.querySelector('[data-contact-status]');
    if (el) return el;
    el = document.createElement('div');
    el.setAttribute('data-contact-status', '');
    el.className = 'contact-form-status';
    el.hidden = true;
    el.tabIndex = -1;
    form.append(el);
    return el;
}

function clearFieldErrors(form) {
    form.querySelectorAll('[aria-invalid="true"]').forEach((input) => {
        input.removeAttribute('aria-invalid');
        input.classList.remove('border-red-500/60');
        input.classList.add('border-neutral-800');
    });
    form.querySelectorAll('[data-contact-error]').forEach((node) => node.remove());
}

function applyFieldErrors(form, errors) {
    Object.entries(errors).forEach(([field, messages]) => {
        const msg = Array.isArray(messages) ? messages[0] : messages;
        if (!msg) return;

        if (field === 'turnstile') {
            const host = form.querySelector('[data-turnstile-error]');
            if (!host) return;
            const p = document.createElement('p');
            p.id = 'contact-turnstile-error';
            p.dataset.contactError = '';
            p.className = 'mt-2 font-mono text-caption text-danger';
            p.setAttribute('role', 'alert');
            p.textContent = msg;
            host.replaceChildren(p);
            return;
        }

        const input = form.querySelector(`[name="${CSS.escape(field)}"]`);
        if (!input) return;

        input.setAttribute('aria-invalid', 'true');
        input.classList.add('border-red-500/60');
        input.classList.remove('border-neutral-800');

        const id = `${input.id || field}-error`;
        input.setAttribute('aria-describedby', id);

        const p = document.createElement('p');
        p.id = id;
        p.dataset.contactError = '';
        p.className = 'mt-1 font-mono text-caption text-danger';
        p.textContent = msg;
        input.insertAdjacentElement('afterend', p);
    });
}
