/* Offline reading for karlhill.com — network-first HTML, cache-first static. */
const CACHE = 'karlhill-offline-v8';
const PRECACHE = ['/', '/blog', '/now', '/about', '/work', '/resume', '/kit', '/offline.html', '/site.webmanifest'];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(keys.filter((key) => key !== CACHE).map((key) => caches.delete(key)))
            )
            .then(() => self.clients.claim())
    );
});

function isStaticAsset(url) {
    return (
        url.origin === self.location.origin &&
        (url.pathname.startsWith('/build/') ||
            url.pathname.startsWith('/img/') ||
            url.pathname.startsWith('/fonts/') ||
            url.pathname.endsWith('.woff2') ||
            url.pathname.endsWith('.css') ||
            url.pathname.endsWith('.js'))
    );
}

function isReadablePage(url) {
    return (
        url.origin === self.location.origin &&
        (url.pathname === '/' ||
            url.pathname === '/blog' ||
            url.pathname.startsWith('/blog/') ||
            url.pathname === '/now' ||
            url.pathname === '/about' ||
            url.pathname === '/resume' ||
            url.pathname === '/kit' ||
            url.pathname === '/work' ||
            url.pathname.startsWith('/work/'))
    );
}

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') return;

    let url;
    try {
        url = new URL(request.url);
    } catch {
        return;
    }

    if (isStaticAsset(url)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    if (request.mode === 'navigate' || isReadablePage(url)) {
        event.respondWith(networkFirstPage(request));
    }
});

async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return cached || Response.error();
    }
}

async function networkFirstPage(request) {
    const cache = await caches.open(CACHE);
    try {
        const response = await fetch(request);
        if (response.ok) {
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        const cached = await cache.match(request);
        if (cached) return cached;
        const offline = await cache.match('/offline.html');
        return offline || Response.error();
    }
}

self.addEventListener('push', (event) => {
    let data = { title: 'Karl Hill', body: 'New writing is live.', url: '/blog' };
    try {
        data = { ...data, ...(event.data?.json() ?? {}) };
    } catch {
        /* keep defaults */
    }
    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            data: { url: data.url || '/blog' },
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/blog';
    event.waitUntil(self.clients.openWindow(url));
});
