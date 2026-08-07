export function initContactForms() {
    document.querySelectorAll('[data-contact-form], .js-contact-form').forEach((contactForm) => {
        const tokenInput = contactForm.querySelector('input[name="_token"]');
        const submitBtn = contactForm.querySelector('[data-contact-submit]');

        const setSubmitting = () => {
            if (!submitBtn) return;
            submitBtn.disabled = true;
            submitBtn.setAttribute('aria-busy', 'true');
            if (!submitBtn.dataset.originalHtml) {
                submitBtn.dataset.originalHtml = submitBtn.innerHTML;
            }
            submitBtn.innerHTML = 'Sending…';
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

        let tokenApplied = false;
        contactForm.addEventListener('submit', (e) => {
            if (tokenApplied) {
                setSubmitting();
                return;
            }
            e.preventDefault();
            ensureToken().finally(() => {
                tokenApplied = true;
                contactForm.submit();
            });
        });
    });
}
