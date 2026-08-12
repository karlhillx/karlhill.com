import { showToast } from './toast.js';

/**
 * Progressive enhancement: fetch submit keeps the visitor on-page with
 * inline success / field errors. Non-JS still posts and redirects.
 */
export function initContactForms() {
    document.querySelectorAll('[data-contact-form], .js-contact-form').forEach((contactForm) => {
        if (!(contactForm instanceof HTMLFormElement)) return;

        const tokenInput = contactForm.querySelector('input[name="_token"]');
        const submitBtn = contactForm.querySelector('[data-contact-submit]');
        const statusEl = ensureStatusRegion(contactForm);

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

        contactForm.addEventListener('focusin', ensureToken, { once: true });

        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            setSubmitting(true);
            clearFieldErrors(contactForm);
            statusEl.hidden = true;
            statusEl.textContent = '';

            ensureToken()
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
                    // Re-apply CSRF after reset wiped the token input.
                    await ensureToken();
                    statusEl.hidden = false;
                    statusEl.className = 'contact-form-status contact-form-status--success';
                    statusEl.setAttribute('role', 'status');
                    statusEl.textContent = msg;
                    showToast(msg, 'success');
                    statusEl.focus?.();
                })
                .catch(() => {
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
        const input = form.querySelector(`[name="${CSS.escape(field)}"]`);
        if (!input) return;
        const msg = Array.isArray(messages) ? messages[0] : messages;
        if (!msg) return;

        input.setAttribute('aria-invalid', 'true');
        input.classList.add('border-red-500/60');
        input.classList.remove('border-neutral-800');

        const id = `${input.id || field}-error`;
        input.setAttribute('aria-describedby', id);

        const p = document.createElement('p');
        p.id = id;
        p.dataset.contactError = '';
        p.className = 'mt-1 font-mono text-[11px] text-red-400';
        p.textContent = msg;
        input.insertAdjacentElement('afterend', p);
    });
}
