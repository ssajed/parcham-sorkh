const CACHE_NAME = 'parcham-sorkh-static-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE_URLS = [
    OFFLINE_URL,
    '/icons/icon-192.svg',
    '/icons/icon-512.svg',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys.filter((key) => key.startsWith('parcham-sorkh-static-') && key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(async () => {
                const cache = await caches.open(CACHE_NAME);
                return (await cache.match(OFFLINE_URL)) || Response.error();
            })
        );
        return;
    }

    const path = url.pathname;
    const isStaticAsset =
        path.startsWith('/build/assets/') ||
        path.startsWith('/assets/fonts/') ||
        path.startsWith('/icons/');

    if (!isStaticAsset || !/\.(?:css|js|mjs|woff2?|ttf|otf|svg|png|webp|ico)$/i.test(path)) {
        return;
    }

    event.respondWith((async () => {
        const cache = await caches.open(CACHE_NAME);
        const cached = await cache.match(request);
        if (cached) return cached;

        const response = await fetch(request);
        if (response.ok && response.type === 'basic') {
            await cache.put(request, response.clone());
        }
        return response;
    })());
});
