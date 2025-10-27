/**
 * Service Worker pour SIFCash-Burkina
 * Gestion du cache et fonctionnalités hors ligne
 */

const CACHE_NAME = 'sif-burkina-v1';
const CACHE_VERSION = '1.0.0';

// Ressources à mettre en cache immédiatement
const STATIC_CACHE_URLS = [
    '/',
    '/login',
    '/adherent/login',
    '/offline.html',
    // Assets statiques
    '/build/assets/app.css',
    '/build/assets/app.js',
    // Fonts
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
    // Bootstrap et FontAwesome depuis CDN
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

// Stratégies de cache
const CACHE_STRATEGIES = {
    // Cache first pour les assets statiques
    CACHE_FIRST: [
        /\.(css|js|woff|woff2|ttf|eot)$/,
        /\/build\/assets\//
    ],
    
    // Network first pour les pages dynamiques
    NETWORK_FIRST: [
        /\/api\//,
        /\/adherent\/dashboard/,
        /\/admin\//
    ],
    
    // Stale while revalidate pour les images
    STALE_WHILE_REVALIDATE: [
        /\.(png|jpg|jpeg|svg|gif|webp)$/
    ]
};

// Pages critiques à synchroniser hors ligne
const OFFLINE_FALLBACK_PAGES = [
    '/offline.html',
    '/adherent/dashboard?offline=true'
];

/**
 * Installation du Service Worker
 */
self.addEventListener('install', (event) => {
    console.log('Service Worker installing...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(STATIC_CACHE_URLS);
            })
            .then(() => {
                console.log('Static resources cached');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('Cache installation failed:', error);
            })
    );
});

/**
 * Activation du Service Worker
 */
self.addEventListener('activate', (event) => {
    console.log('Service Worker activating...');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                // Supprimer les anciens caches
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== CACHE_NAME) {
                            console.log('Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker activated');
                return self.clients.claim();
            })
    );
});

/**
 * Interception des requêtes
 */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Ignorer les requêtes non-HTTP
    if (!request.url.startsWith('http')) {
        return;
    }
    
    // Ignorer les requêtes WebSocket
    if (url.protocol === 'ws:' || url.protocol === 'wss:') {
        return;
    }
    
    // Déterminer la stratégie de cache
    const strategy = determineStrategy(request.url);
    
    switch (strategy) {
        case 'CACHE_FIRST':
            event.respondWith(cacheFirst(request));
            break;
        case 'NETWORK_FIRST':
            event.respondWith(networkFirst(request));
            break;
        case 'STALE_WHILE_REVALIDATE':
            event.respondWith(staleWhileRevalidate(request));
            break;
        default:
            event.respondWith(networkFirst(request));
    }
});

/**
 * Stratégies de cache
 */
async function cacheFirst(request) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        return getOfflineFallback(request);
    }
}

async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        return getOfflineFallback(request);
    }
}

async function staleWhileRevalidate(request) {
    const cachedResponse = await caches.match(request);
    
    const networkPromise = fetch(request)
        .then((networkResponse) => {
            if (networkResponse.ok) {
                const cache = caches.open(CACHE_NAME);
                cache.then(c => c.put(request, networkResponse.clone()));
            }
            return networkResponse;
        })
        .catch(() => null);
    
    return cachedResponse || networkPromise || getOfflineFallback(request);
}

/**
 * Déterminer la stratégie de cache pour une URL
 */
function determineStrategy(url) {
    for (const [strategy, patterns] of Object.entries(CACHE_STRATEGIES)) {
        if (patterns.some(pattern => pattern.test(url))) {
            return strategy;
        }
    }
    return 'NETWORK_FIRST';
}

/**
 * Fallback hors ligne
 */
async function getOfflineFallback(request) {
    const url = new URL(request.url);
    
    // Pour les pages HTML
    if (request.destination === 'document') {
        const offlinePage = await caches.match('/offline.html');
        return offlinePage || new Response(
            '<html><body><h1>Hors ligne</h1><p>Vérifiez votre connexion internet.</p></body></html>',
            { headers: { 'Content-Type': 'text/html' } }
        );
    }
    
    // Pour les images
    if (request.destination === 'image') {
        return new Response(
            '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="#f8f9fa"/><text x="50" y="50" text-anchor="middle" fill="#6c757d">Image</text></svg>',
            { headers: { 'Content-Type': 'image/svg+xml' } }
        );
    }
    
    // Pour les API
    if (url.pathname.startsWith('/api/')) {
        return new Response(
            JSON.stringify({ 
                error: 'Hors ligne', 
                message: 'Cette fonctionnalité nécessite une connexion internet' 
            }),
            { 
                status: 503,
                headers: { 'Content-Type': 'application/json' }
            }
        );
    }
    
    return new Response('Ressource non disponible hors ligne', { status: 503 });
}

/**
 * Gestion des messages depuis l'application
 */
self.addEventListener('message', (event) => {
    const { data } = event;
    
    switch (data.action) {
        case 'SKIP_WAITING':
            self.skipWaiting();
            break;
            
        case 'GET_VERSION':
            event.ports[0].postMessage({ version: CACHE_VERSION });
            break;
            
        case 'CLEAR_CACHE':
            clearCache().then(() => {
                event.ports[0].postMessage({ success: true });
            });
            break;
            
        case 'CACHE_URLS':
            cacheUrls(data.urls).then(() => {
                event.ports[0].postMessage({ success: true });
            });
            break;
            
        case 'SYNC_DATA':
            // Synchronisation des données hors ligne (à implémenter selon besoins)
            handleOfflineSync(data.syncData);
            break;
    }
});

/**
 * Synchronisation en arrière-plan
 */
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        event.waitUntil(syncOfflineData());
    }
});

/**
 * Notifications push
 */
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    
    const options = {
        body: data.message || 'Nouvelle notification',
        icon: '/icon-192.png',
        badge: '/badge-72.png',
        data: data.data || {},
        actions: [
            { action: 'view', title: 'Voir' },
            { action: 'dismiss', title: 'Ignorer' }
        ],
        requireInteraction: data.important || false
    };
    
    event.waitUntil(
        self.registration.showNotification(data.title || 'SIFCash-Burkina', options)
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    if (event.action === 'view') {
        const urlToOpen = event.notification.data.url || '/adherent/dashboard';
        
        event.waitUntil(
            clients.matchAll().then((clientList) => {
                for (const client of clientList) {
                    if (client.url === urlToOpen && 'focus' in client) {
                        return client.focus();
                    }
                }
                
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
        );
    }
});

/**
 * Fonctions utilitaires
 */
async function clearCache() {
    const cache = await caches.open(CACHE_NAME);
    const keys = await cache.keys();
    await Promise.all(keys.map(key => cache.delete(key)));
    console.log('Cache cleared');
}

async function cacheUrls(urls) {
    const cache = await caches.open(CACHE_NAME);
    await cache.addAll(urls);
    console.log('URLs cached:', urls);
}

async function syncOfflineData() {
    // Récupérer les données stockées localement
    // Envoyer au serveur quand la connexion est rétablie
    console.log('Syncing offline data...');
}

async function handleOfflineSync(syncData) {
    // Gérer la synchronisation des données modifiées hors ligne
    console.log('Handling offline sync:', syncData);
}