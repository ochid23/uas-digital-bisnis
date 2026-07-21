const CACHE_NAME = 'eventhub-cache-v1';
const urlsToCache = [
    '/',
    '/manifest.json',
];

// Menginstal Service Worker dan menyimpan cache
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Mengambil data dari cache jika tidak ada jaringan
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});