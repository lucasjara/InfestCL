import {
    closeCollectionDatabase,
    deleteCollectionDatabase,
    openCollectionDatabase,
    STORES,
} from './database.js';

async function getStore(storeName, mode = 'readonly') {
    const database = await openCollectionDatabase();
    const transaction = database.transaction(storeName, mode);

    return {
        transaction,
        store: transaction.objectStore(storeName),
    };
}

function requestToPromise(request) {
    return new Promise((resolve, reject) => {
        request.addEventListener('success', () => resolve(request.result));
        request.addEventListener('error', () => {
            reject(request.error ?? new Error('IndexedDB operation failed.'));
        });
    });
}

function transactionToPromise(transaction) {
    return new Promise((resolve, reject) => {
        transaction.addEventListener('complete', () => resolve());
        transaction.addEventListener('abort', () => {
            reject(transaction.error ?? new Error('IndexedDB transaction aborted.'));
        });
        transaction.addEventListener('error', () => {
            reject(transaction.error ?? new Error('IndexedDB transaction failed.'));
        });
    });
}

async function getById(storeName, id) {
    const { store } = await getStore(storeName);
    return requestToPromise(store.get(id));
}

async function getAll(storeName) {
    const { store } = await getStore(storeName);
    return requestToPromise(store.getAll());
}

async function putRecord(storeName, record) {
    const { store, transaction } = await getStore(storeName, 'readwrite');
    const operation = requestToPromise(store.put(record));
    const completion = transactionToPromise(transaction);

    await completion;
    return operation;
}

async function deleteRecord(storeName, id) {
    const { store, transaction } = await getStore(storeName, 'readwrite');
    const operation = requestToPromise(store.delete(id));
    const completion = transactionToPromise(transaction);

    await completion;
    return operation;
}

async function deleteCollectionCascade(collectionId) {
    const database = await openCollectionDatabase();
    const transaction = database.transaction(
        [STORES.collections, STORES.inventory],
        'readwrite',
    );
    const collections = transaction.objectStore(STORES.collections);
    const inventoryStore = transaction.objectStore(STORES.inventory);
    const inventoryIndex = inventoryStore.index('collectionId');
    const completion = transactionToPromise(transaction);

    try {
        const records = await requestToPromise(
            inventoryIndex.getAll(collectionId),
        );

        for (const record of records) {
            await requestToPromise(inventoryStore.delete(record.id));
        }

        await requestToPromise(collections.delete(collectionId));
        await completion;
    } catch (error) {
        transaction.abort();
        await completion.catch(() => undefined);
        throw error;
    }

    return true;
}

async function addInventory(inventory) {
    if (!inventory.collectionId || !inventory.cardId) {
        throw new Error('Inventory requires collectionId and cardId.');
    }

    if (!Number.isInteger(inventory.quantity) || inventory.quantity < 0) {
        throw new Error('Inventory quantity must be zero or a positive integer.');
    }

    const database = await openCollectionDatabase();
    const transaction = database.transaction(
        [STORES.collections, STORES.cards, STORES.inventory],
        'readwrite',
    );
    const collections = transaction.objectStore(STORES.collections);
    const cards = transaction.objectStore(STORES.cards);
    const inventoryStore = transaction.objectStore(STORES.inventory);
    const completion = transactionToPromise(transaction);

    try {
        const collection = await requestToPromise(collections.get(inventory.collectionId));

        if (!collection) {
            throw new Error('The inventory collection does not exist.');
        }

        const card = await requestToPromise(cards.get(inventory.cardId));

        if (!card) {
            throw new Error('The inventory card does not exist.');
        }

        const existing = await requestToPromise(
            inventoryStore.index('collectionCard').get([
                inventory.collectionId,
                inventory.cardId,
            ]),
        );

        if (existing) {
            throw new Error('The card already exists in this collection inventory.');
        }

        await requestToPromise(inventoryStore.add(inventory));
        await completion;
        return inventory;
    } catch (error) {
        transaction.abort();
        await completion.catch(() => undefined);
        throw error;
    }
}

async function getCollectionInventory(collectionId) {
    const { store } = await getStore(STORES.inventory);
    return requestToPromise(store.index('collectionId').getAll(collectionId));
}

async function getInventoryByCollectionAndCard(collectionId, cardId) {
    const { store } = await getStore(STORES.inventory);
    return requestToPromise(
        store.index('collectionCard').get([collectionId, cardId]),
    );
}

async function resetCollectionData() {
    await deleteCollectionDatabase();
    return openCollectionDatabase();
}

export const indexedDBAdapter = Object.freeze({
    open: openCollectionDatabase,
    close: closeCollectionDatabase,
    resetCollectionData,
    getCollections: () => getAll(STORES.collections),
    getCollectionById: (id) => getById(STORES.collections, id),
    putCollection: (collection) => putRecord(STORES.collections, collection),
    deleteCollection: (id) => deleteRecord(STORES.collections, id),
    getCards: () => getAll(STORES.cards),
    getCardById: (id) => getById(STORES.cards, id),
    putCard: (card) => putRecord(STORES.cards, card),
    deleteCard: (id) => deleteRecord(STORES.cards, id),
    getInventory: () => getAll(STORES.inventory),
    deleteCollectionCascade,
    addInventory,
    getCollectionInventory,
    getInventoryByCollectionAndCard,
    putInventory: (inventory) => putRecord(STORES.inventory, inventory),
    deleteInventory: (id) => deleteRecord(STORES.inventory, id),
    moveInventoryRecords,
});
async function moveInventoryRecords(sourceCollectionId, targetCollectionId, cardIds) {
    if (!sourceCollectionId || !targetCollectionId) {
        throw new Error('Source and target collections are required.');
    }

    if (sourceCollectionId === targetCollectionId) {
        throw new Error('Source and target collections must be different.');
    }

    const selectedCardIds = [...new Set(cardIds)];

    if (selectedCardIds.length === 0) {
        return { moved: 0, cards: [] };
    }

    const database = await openCollectionDatabase();
    const transaction = database.transaction(
        [STORES.collections, STORES.inventory],
        'readwrite',
    );

    const collections = transaction.objectStore(STORES.collections);
    const inventoryStore = transaction.objectStore(STORES.inventory);
    const inventoryIndex = inventoryStore.index('collectionCard');
    const completion = transactionToPromise(transaction);

    try {
        const [sourceCollection, targetCollection] = await Promise.all([
            requestToPromise(collections.get(sourceCollectionId)),
            requestToPromise(collections.get(targetCollectionId)),
        ]);

        if (!sourceCollection || !targetCollection) {
            throw new Error('The source or target collection does not exist.');
        }

        const movedCards = [];

        for (const cardId of selectedCardIds) {
            const sourceRecord = await requestToPromise(
                inventoryIndex.get([sourceCollectionId, cardId]),
            );

            // Ignore cards that are no longer in the source collection.
            if (!sourceRecord) continue;

            const targetRecord = await requestToPromise(
                inventoryIndex.get([targetCollectionId, cardId]),
            );

            if (targetRecord) {
                targetRecord.quantity += sourceRecord.quantity;
                await requestToPromise(inventoryStore.put(targetRecord));
            } else {
                await requestToPromise(inventoryStore.add({
                    ...sourceRecord,
                    id: crypto.randomUUID(),
                    collectionId: targetCollectionId,
                }));
            }

            await requestToPromise(inventoryStore.delete(sourceRecord.id));

            movedCards.push({
                cardId,
                quantity: sourceRecord.quantity,
            });
        }

        await completion;

        return {
            moved: movedCards.length,
            cards: movedCards,
        };
    } catch (error) {
        transaction.abort();
        await completion.catch(() => undefined);
        throw error;
    }
}
