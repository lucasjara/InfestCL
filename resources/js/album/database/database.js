export const DATABASE_NAME = 'CardCollectionDB';
export const DATABASE_VERSION = 1;
export const DEFAULT_COLLECTION_ID = 'default';

export const STORES = Object.freeze({
    collections: 'collections',
    cards: 'cards',
    inventory: 'inventory',
});

let databasePromise = null;
let databaseConnection = null;

async function ensureDefaultCollection(database) {
    const transaction = database.transaction(STORES.collections, 'readwrite');
    const store = transaction.objectStore(STORES.collections);
    const timestamp = new Date().toISOString();
    const existing = await requestToPromise(store.get(DEFAULT_COLLECTION_ID));

    if (!existing) {
        await requestToPromise(store.add({
            id: DEFAULT_COLLECTION_ID,
            name: 'predeterminada',
            description: 'Colección principal para guardar cartas.',
            createdAt: timestamp,
            updatedAt: timestamp,
        }));
    }

    await new Promise((resolve, reject) => {
        transaction.addEventListener('complete', resolve);
        transaction.addEventListener('abort', () => reject(
            transaction.error ?? new Error('Unable to create the default collection.')
        ));
        transaction.addEventListener('error', () => reject(
            transaction.error ?? new Error('Unable to create the default collection.')
        ));
    });
}

function requestToPromise(request) {
    return new Promise((resolve, reject) => {
        request.addEventListener('success', () => resolve(request.result));
        request.addEventListener('error', () => {
            reject(request.error ?? new Error('IndexedDB operation failed.'));
        });
    });
}

export function openCollectionDatabase() {
    if (databaseConnection) {
        return Promise.resolve(databaseConnection);
    }

    if (databasePromise) {
        return databasePromise;
    }

    if (!('indexedDB' in globalThis)) {
        return Promise.reject(new Error('IndexedDB is not available in this browser.'));
    }

    databasePromise = new Promise((resolve, reject) => {
        const request = indexedDB.open(DATABASE_NAME, DATABASE_VERSION);

        request.addEventListener('upgradeneeded', () => {
            const database = request.result;

            if (!database.objectStoreNames.contains(STORES.collections)) {
                const collections = database.createObjectStore(STORES.collections, { keyPath: 'id' });
                collections.createIndex('name', 'name', { unique: false });
                collections.createIndex('createdAt', 'createdAt', { unique: false });
            }

            if (!database.objectStoreNames.contains(STORES.cards)) {
                const cards = database.createObjectStore(STORES.cards, { keyPath: 'id' });
                cards.createIndex('type', 'type', { unique: false });
                cards.createIndex('externalId', 'externalId', { unique: false });
            }

            if (!database.objectStoreNames.contains(STORES.inventory)) {
                const inventory = database.createObjectStore(STORES.inventory, { keyPath: 'id' });
                inventory.createIndex(
                    'collectionCard',
                    ['collectionId', 'cardId'],
                    { unique: true },
                );
                inventory.createIndex('collectionId', 'collectionId', { unique: false });
            }
        });

        request.addEventListener('success', () => {
            databaseConnection = request.result;
            databaseConnection.addEventListener('versionchange', () => {
                closeCollectionDatabase();
            });
            ensureDefaultCollection(databaseConnection).then(() => {
                resolve(databaseConnection);
            }).catch((error) => {
                closeCollectionDatabase();
                reject(error);
            });
        });

        request.addEventListener('error', () => {
            reject(request.error ?? new Error('Unable to open IndexedDB.'));
        });

        request.addEventListener('blocked', () => {
            reject(new Error('IndexedDB upgrade is blocked by another open connection.'));
        });
    }).finally(() => {
        databasePromise = null;
    });

    return databasePromise;
}

export async function closeCollectionDatabase() {
    if (databaseConnection) {
        databaseConnection.close();
        databaseConnection = null;
    }

    databasePromise = null;
}

export async function deleteCollectionDatabase() {
    await closeCollectionDatabase();

    if (!('indexedDB' in globalThis)) {
        throw new Error('IndexedDB is not available in this browser.');
    }

    return new Promise((resolve, reject) => {
        const request = indexedDB.deleteDatabase(DATABASE_NAME);
        request.addEventListener('success', () => resolve());
        request.addEventListener('error', () => {
            reject(request.error ?? new Error('Unable to delete IndexedDB.'));
        });
        request.addEventListener('blocked', () => {
            reject(new Error('IndexedDB deletion is blocked by another open connection.'));
        });
    });
}

export function createDatabaseRequest(request) {
    return requestToPromise(request);
}
