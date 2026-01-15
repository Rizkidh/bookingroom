const CACHE_NAME = 'kai-inventaris-v2';
const ASSETS_TO_CACHE = [
    '/css/kai-theme.css',
    '/images/kai-logo.png',
    '/images/kai-background.png',
];

// List of paths that should NEVER be cached or should always be Network-First
const DYNAMIC_PATHS = [
    '/dashboard',
    '/inventories',
    '/activity-logs',
    '/login',
    '/logout'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.filter((cacheName) => {
                    return cacheName !== CACHE_NAME;
                }).map((cacheName) => {
                    return caches.delete(cacheName);
                })
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    // If it's a Turbo request, let the network handle it directly
    // This prevents the Service Worker from interfering with Turbo's sophisticated navigation
    if (event.request.headers.get('Turbo-Frame') || event.request.headers.get('X-Turbo-Request-Id')) {
        return;
    }

    const url = new URL(event.request.url);
    
    // Strategy: Network-First for everything, especially dynamic paths
    // This prevents "getting stuck" on old data or redirect loops
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // If successful, and it's a static asset, update cache
                if (response.status === 200 && ASSETS_TO_CACHE.includes(url.pathname)) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // If network fails, try cache
                return caches.match(event.request);
            })
    );
});
