// Minimal, conservative service worker — deliberately does NOT cache HTML pages.
// A PWA that caches product/checkout pages aggressively is the classic way to
// show a returning customer stale prices, stale stock, or a stale cart. This one
// only ever caches static, fingerprinted assets and always goes to the network
// first for page navigations.
const CACHE_NAME = 'ousodhaloy-static-v1';
const PRECACHE = ['/icon-192.png', '/icon-512.png', '/favicon.svg'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE)));
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return; // never intercept fonts CDN, Meta Pixel, etc.

    // Page navigations: network-first, falling back to a cached shell only when
    // truly offline — never serve a cached product/checkout page over a fresh one.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match('/'))
        );
        return;
    }

    // Fingerprinted build output + icons/fonts: cache-first, they don't change
    // without also changing filename.
    if (url.pathname.startsWith('/build/') || /\.(png|jpe?g|webp|svg|ico|woff2?)$/.test(url.pathname)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((res) => {
                    const clone = res.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                    return res;
                });
            })
        );
    }
    // Everything else (AJAX, admin, API) — pass straight through, no caching.
});
