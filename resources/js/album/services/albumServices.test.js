import assert from 'node:assert/strict';
import test from 'node:test';

import { CardService } from './cardService.js';
import { CollectionService } from './collectionService.js';
import { InventoryService } from './inventoryService.js';

function createMemoryAdapter() {
    const collections = new Map();
    const cards = new Map();
    const inventory = new Map();

    return {
        collections,
        cards,
        inventory,
        putCollection: async (collection) => collections.set(collection.id, collection),
        getCollections: async () => [...collections.values()],
        getCollectionById: async (id) => collections.get(id) ?? null,
        deleteCollection: async (id) => collections.delete(id),
        deleteCollectionCascade: async (id) => {
            collections.delete(id);

            for (const [inventoryId, record] of inventory) {
                if (record.collectionId === id) {
                    inventory.delete(inventoryId);
                }
            }

            return true;
        },
        putCard: async (card) => cards.set(card.id, card),
        getCardById: async (id) => cards.get(id) ?? null,
        getInventory: async () => [...inventory.values()],
        deleteCard: async (id) => cards.delete(id),
        getCollectionInventory: async (collectionId) => [
            ...inventory.values(),
        ].filter((record) => record.collectionId === collectionId),
        getInventoryByCollectionAndCard: async (collectionId, cardId) => [
            ...inventory.values(),
        ].find((record) => (
            record.collectionId === collectionId && record.cardId === cardId
        )) ?? null,
        addInventory: async (record) => {
            const duplicate = [...inventory.values()].some((existing) => (
                existing.collectionId === record.collectionId
                && existing.cardId === record.cardId
            ));

            if (duplicate) {
                throw new Error('Duplicate inventory record.');
            }

            inventory.set(record.id, record);
            return record;
        },
        putInventory: async (record) => inventory.set(record.id, record),
        deleteInventory: async (id) => inventory.delete(id),
    };
}

test('collection service rejects an empty name and cascades inventory on deletion', async () => {
    const adapter = createMemoryAdapter();
    const fixedDate = new Date('2026-11-01T12:00:00.000Z');
    const service = new CollectionService(adapter, {
        createUuid: () => 'collection-123',
        now: () => fixedDate,
    });

    await assert.rejects(
        () => service.createCollection({ name: '   ' }),
        /collection name/i,
    );

    await service.createCollection({ name: 'Pokémon', description: 'Personal' });
    await adapter.putInventory({
        id: 'inventory-123',
        collectionId: 'collection-123',
        cardId: 'pokemon-25',
        quantity: 1,
    });

    await service.deleteCollection('collection-123');

    assert.equal(await service.getCollectionById('collection-123'), null);
    assert.equal(adapter.inventory.size, 0);
});

test('default collection is protected from deletion', async () => {
    const adapter = createMemoryAdapter();
    const service = new CollectionService(adapter, {
        createUuid: () => 'collection-created',
        now: () => new Date('2026-11-01T12:00:00.000Z'),
    });

    adapter.collections.set('default', {
        id: 'default',
        name: 'Colección predeterminada',
    });

    await assert.rejects(
        () => service.deleteCollection('default'),
        /cannot be deleted/i,
    );
});

test('card service uses stable identifiers and prevents deleting a card in inventory', async () => {
    const adapter = createMemoryAdapter();
    const service = new CardService(adapter, {
        createUuid: () => 'personal-123',
        now: () => new Date('2026-11-01T12:00:00.000Z'),
    });

    const pokemon = await service.createCard({
        id: 'pokemon-25',
        type: 'pokemon',
        externalId: 25,
        name: 'Pikachu',
        image: 'pokemon/25.png',
    });
    const personal = await service.createCard({
        type: 'personal',
        name: 'Carta propia',
        image: new Blob(['image']),
    });

    await adapter.putInventory({
        id: 'inventory-123',
        collectionId: 'collection-123',
        cardId: 'pokemon-25',
        quantity: 1,
    });

    assert.equal(pokemon.id, 'pokemon-25');
    assert.equal(personal.id, 'personal-123');
    await assert.rejects(
        () => service.deleteCard('pokemon-25'),
        /still used/i,
    );
});

test('inventory service increments quantity and deletes the record at zero', async () => {
    const adapter = createMemoryAdapter();
    adapter.collections.set('collection-123', {
        id: 'collection-123',
        name: 'Pokémon',
    });
    adapter.cards.set('pokemon-25', {
        id: 'pokemon-25',
        type: 'pokemon',
        name: 'Pikachu',
    });

    const service = new InventoryService(adapter);

    await service.addCardToCollection('collection-123', 'pokemon-25');
    const doubled = await service.addCardToCollection('collection-123', 'pokemon-25');

    assert.equal(doubled.quantity, 2);
    assert.equal(await service.getCardQuantity('collection-123', 'pokemon-25'), 2);

    await service.updateCardQuantity('collection-123', 'pokemon-25', 0);

    assert.equal(await service.getCardQuantity('collection-123', 'pokemon-25'), 0);
    assert.equal(adapter.inventory.size, 0);
});
