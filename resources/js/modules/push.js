/**
 * Opt-in Web Push for new essays. Hidden unless VAPID is configured
 * and the browser exposes PushManager + a registered service worker.
 */
export function initPushSubscribe() {
    const button = document.querySelector('[data-push-subscribe]');
    const publicKey = document.documentElement.dataset.vapidPublic;
    if (!button || !publicKey) return;
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    button.hidden = false;

    const setLabel = (subscribed) => {
        button.textContent = subscribed ? 'Notifications on' : 'Notify me of new essays';
        button.setAttribute('aria-pressed', subscribed ? 'true' : 'false');
    };

    const urlBase64ToUint8Array = (base64String) => {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw = atob(base64);
        const output = new Uint8Array(raw.length);
        for (let i = 0; i < raw.length; i++) output[i] = raw.charCodeAt(i);
        return output;
    };

    const csrf = async () => {
        const res = await fetch('/csrf-token', { headers: { Accept: 'application/json' } });
        const data = await res.json();
        return data.token;
    };

    navigator.serviceWorker.ready
        .then((reg) => reg.pushManager.getSubscription())
        .then((existing) => setLabel(Boolean(existing)))
        .catch(() => {});

    button.addEventListener('click', async () => {
        try {
            const registration = await navigator.serviceWorker.ready;
            let subscription = await registration.pushManager.getSubscription();

            if (subscription) {
                const token = await csrf();
                await fetch('/push/unsubscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ endpoint: subscription.endpoint }),
                });
                await subscription.unsubscribe();
                setLabel(false);
                return;
            }

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(publicKey),
            });
            const token = await csrf();
            await fetch('/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify(subscription.toJSON()),
            });
            setLabel(true);
        } catch {
            button.textContent = 'Notifications unavailable';
        }
    });
}
