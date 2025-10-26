/**
 * Offline Storage Manager
 * Gestion du stockage local et de la synchronisation hors ligne
 */

class OfflineStorageManager {
    constructor() {
        this.dbName = 'SIF_Burkina_DB';
        this.dbVersion = 1;
        this.db = null;
        this.syncQueue = [];
        this.storeName = 'offline_data';
        
        this.init();
    }

    /**
     * Initialisation d'IndexedDB
     */
    async init() {
        if (!('indexedDB' in window)) {
            console.warn('IndexedDB non supporté');
            return;
        }

        try {
            this.db = await this.openDB();
            await this.loadSyncQueue();
            this.setupAutoSync();
        } catch (error) {
            console.error('Erreur initialisation IndexedDB:', error);
        }
    }

    /**
     * Ouvrir la base de données IndexedDB
     */
    openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => resolve(request.result);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Store principal pour les données hors ligne
                if (!db.objectStoreNames.contains(this.storeName)) {
                    const store = db.createObjectStore(this.storeName, { keyPath: 'id' });
                    store.createIndex('type', 'type', { unique: false });
                    store.createIndex('timestamp', 'timestamp', { unique: false });
                    store.createIndex('synced', 'synced', { unique: false });
                }

                // Store pour les données utilisateur
                if (!db.objectStoreNames.contains('user_data')) {
                    const userStore = db.createObjectStore('user_data', { keyPath: 'key' });
                }

                // Store pour le cache des requêtes
                if (!db.objectStoreNames.contains('api_cache')) {
                    const cacheStore = db.createObjectStore('api_cache', { keyPath: 'url' });
                    cacheStore.createIndex('expiry', 'expiry', { unique: false });
                }

                console.log('Base de données créée/mise à jour');
            };
        });
    }

    /**
     * Sauvegarder des données hors ligne
     */
    async saveOfflineData(type, data, id = null) {
        if (!this.db) return false;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);

        const record = {
            id: id || this.generateId(),
            type: type,
            data: data,
            timestamp: Date.now(),
            synced: false,
            action: data.action || 'save' // save, update, delete
        };

        try {
            await store.put(record);
            console.log('Données sauvées hors ligne:', type, record.id);
            return record.id;
        } catch (error) {
            console.error('Erreur sauvegarde hors ligne:', error);
            return false;
        }
    }

    /**
     * Récupérer des données hors ligne
     */
    async getOfflineData(type = null, synced = null) {
        if (!this.db) return [];

        const transaction = this.db.transaction([this.storeName], 'readonly');
        const store = transaction.objectStore(this.storeName);

        try {
            let result;

            if (type) {
                const index = store.index('type');
                result = await this.getFromIndex(index, type);
            } else {
                result = await this.getAllFromStore(store);
            }

            if (synced !== null) {
                result = result.filter(item => item.synced === synced);
            }

            return result;
        } catch (error) {
            console.error('Erreur récupération données:', error);
            return [];
        }
    }

    /**
     * Marquer des données comme synchronisées
     */
    async markAsSynced(id) {
        if (!this.db) return false;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);

        try {
            const record = await store.get(id);
            if (record) {
                record.synced = true;
                record.syncedAt = Date.now();
                await store.put(record);
                console.log('Données marquées comme synchronisées:', id);
                return true;
            }
            return false;
        } catch (error) {
            console.error('Erreur marquage synchronisation:', error);
            return false;
        }
    }

    /**
     * Supprimer des données hors ligne
     */
    async deleteOfflineData(id) {
        if (!this.db) return false;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);

        try {
            await store.delete(id);
            console.log('Données supprimées:', id);
            return true;
        } catch (error) {
            console.error('Erreur suppression:', error);
            return false;
        }
    }

    /**
     * Sauvegarder les données utilisateur
     */
    async saveUserData(key, value) {
        if (!this.db) return false;

        const transaction = this.db.transaction(['user_data'], 'readwrite');
        const store = transaction.objectStore('user_data');

        try {
            await store.put({
                key: key,
                value: value,
                timestamp: Date.now()
            });
            return true;
        } catch (error) {
            console.error('Erreur sauvegarde données utilisateur:', error);
            return false;
        }
    }

    /**
     * Récupérer des données utilisateur
     */
    async getUserData(key) {
        if (!this.db) return null;

        const transaction = this.db.transaction(['user_data'], 'readonly');
        const store = transaction.objectStore('user_data');

        try {
            const result = await store.get(key);
            return result ? result.value : null;
        } catch (error) {
            console.error('Erreur récupération données utilisateur:', error);
            return null;
        }
    }

    /**
     * Cache API avec expiration
     */
    async cacheAPIResponse(url, data, ttl = 300000) { // 5 minutes par défaut
        if (!this.db) return false;

        const transaction = this.db.transaction(['api_cache'], 'readwrite');
        const store = transaction.objectStore('api_cache');

        try {
            await store.put({
                url: url,
                data: data,
                timestamp: Date.now(),
                expiry: Date.now() + ttl
            });
            return true;
        } catch (error) {
            console.error('Erreur mise en cache API:', error);
            return false;
        }
    }

    /**
     * Récupérer une réponse API du cache
     */
    async getCachedAPIResponse(url) {
        if (!this.db) return null;

        const transaction = this.db.transaction(['api_cache'], 'readonly');
        const store = transaction.objectStore('api_cache');

        try {
            const result = await store.get(url);
            
            if (result && result.expiry > Date.now()) {
                console.log('Données récupérées du cache:', url);
                return result.data;
            } else if (result) {
                // Supprimer les données expirées
                await store.delete(url);
            }
            
            return null;
        } catch (error) {
            console.error('Erreur récupération cache API:', error);
            return null;
        }
    }

    /**
     * Synchroniser les données hors ligne
     */
    async syncOfflineData() {
        if (!navigator.onLine) {
            console.log('Hors ligne - synchronisation différée');
            return;
        }

        const unsyncedData = await this.getOfflineData(null, false);
        
        if (unsyncedData.length === 0) {
            console.log('Aucune donnée à synchroniser');
            return;
        }

        console.log(`Synchronisation de ${unsyncedData.length} éléments`);

        for (const item of unsyncedData) {
            try {
                await this.syncSingleItem(item);
            } catch (error) {
                console.error('Erreur synchronisation item:', item.id, error);
            }
        }
    }

    /**
     * Synchroniser un élément
     */
    async syncSingleItem(item) {
        const { id, type, data, action } = item;
        let endpoint, method, body;

        // Déterminer l'endpoint et la méthode selon le type et l'action
        switch (type) {
            case 'cotisation':
                endpoint = '/api/cotisations';
                method = action === 'delete' ? 'DELETE' : 
                        data.id ? 'PUT' : 'POST';
                if (action === 'delete') {
                    endpoint += `/${data.id}`;
                } else if (data.id) {
                    endpoint += `/${data.id}`;
                }
                body = action !== 'delete' ? JSON.stringify(data) : null;
                break;

            case 'profil':
                endpoint = '/api/profil';
                method = 'PUT';
                body = JSON.stringify(data);
                break;

            case 'transaction':
                endpoint = '/api/transactions';
                method = action === 'delete' ? 'DELETE' : 
                        data.id ? 'PUT' : 'POST';
                if (action === 'delete' || data.id) {
                    endpoint += `/${data.id}`;
                }
                body = action !== 'delete' ? JSON.stringify(data) : null;
                break;

            default:
                console.warn('Type de synchronisation non géré:', type);
                return;
        }

        // Effectuer la requête
        const response = await fetch(endpoint, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: body
        });

        if (response.ok) {
            await this.markAsSynced(id);
            console.log('Synchronisation réussie:', type, id);
            
            // Notifier le succès
            this.notifySync('success', type);
        } else {
            throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
    }

    /**
     * Charger la queue de synchronisation
     */
    async loadSyncQueue() {
        this.syncQueue = await this.getOfflineData(null, false);
        console.log(`${this.syncQueue.length} éléments en attente de synchronisation`);
    }

    /**
     * Configuration de la synchronisation automatique
     */
    setupAutoSync() {
        // Synchroniser quand on revient en ligne
        window.addEventListener('online', () => {
            setTimeout(() => this.syncOfflineData(), 1000);
        });

        // Synchronisation périodique (toutes les 5 minutes)
        setInterval(() => {
            if (navigator.onLine) {
                this.syncOfflineData();
            }
        }, 300000);

        // Synchroniser quand la page devient visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && navigator.onLine) {
                this.syncOfflineData();
            }
        });
    }

    /**
     * Notifier les résultats de synchronisation
     */
    notifySync(status, type) {
        if (window.swManager) {
            const message = status === 'success' 
                ? `${type} synchronisé avec succès`
                : `Erreur synchronisation ${type}`;
            
            navigator.serviceWorker.controller?.postMessage({
                type: 'SYNC_COMPLETE',
                status: status,
                dataType: type,
                message: message
            });
        }
    }

    /**
     * Nettoyer les données anciennes
     */
    async cleanupOldData(maxAge = 7 * 24 * 60 * 60 * 1000) { // 7 jours
        if (!this.db) return;

        const cutoff = Date.now() - maxAge;
        const transaction = this.db.transaction([this.storeName, 'api_cache'], 'readwrite');

        try {
            // Nettoyer les données synchronisées anciennes
            const store = transaction.objectStore(this.storeName);
            const index = store.index('timestamp');
            const request = index.openCursor(IDBKeyRange.upperBound(cutoff));

            request.onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    if (cursor.value.synced) {
                        cursor.delete();
                    }
                    cursor.continue();
                }
            };

            // Nettoyer le cache API expiré
            const cacheStore = transaction.objectStore('api_cache');
            const cacheIndex = cacheStore.index('expiry');
            const cacheRequest = cacheIndex.openCursor(IDBKeyRange.upperBound(Date.now()));

            cacheRequest.onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    cursor.delete();
                    cursor.continue();
                }
            };

            console.log('Nettoyage des données anciennes effectué');
        } catch (error) {
            console.error('Erreur nettoyage:', error);
        }
    }

    /**
     * Utilitaires
     */
    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    getFromIndex(index, key) {
        return new Promise((resolve, reject) => {
            const request = index.getAll(key);
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    getAllFromStore(store) {
        return new Promise((resolve, reject) => {
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Statistiques du stockage
     */
    async getStorageStats() {
        if (!this.db) return null;

        const transaction = this.db.transaction([this.storeName, 'user_data', 'api_cache'], 'readonly');
        
        try {
            const offlineStore = transaction.objectStore(this.storeName);
            const userStore = transaction.objectStore('user_data');
            const cacheStore = transaction.objectStore('api_cache');

            const [offlineCount, userCount, cacheCount] = await Promise.all([
                this.getStoreCount(offlineStore),
                this.getStoreCount(userStore),
                this.getStoreCount(cacheStore)
            ]);

            const unsyncedCount = await this.getOfflineData(null, false).then(data => data.length);

            return {
                offlineData: offlineCount,
                userData: userCount,
                cachedAPI: cacheCount,
                unsynced: unsyncedCount
            };
        } catch (error) {
            console.error('Erreur statistiques:', error);
            return null;
        }
    }

    getStoreCount(store) {
        return new Promise((resolve, reject) => {
            const request = store.count();
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }
}

// Initialiser automatiquement
const offlineStorage = new OfflineStorageManager();

// Exporter pour utilisation globale
window.OfflineStorage = offlineStorage;

export default OfflineStorageManager;