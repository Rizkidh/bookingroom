const CACHE_NAME = 'kai-inventaris-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/css/kai-theme.css',
    '/images/kai-logo.png',
    '/images/kai-background.png',
    'https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});
