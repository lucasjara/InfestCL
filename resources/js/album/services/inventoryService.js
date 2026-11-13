export class InventoryService {
    constructor(adapter) {
        this.adapter = adapter;
        this.now = () => new Date();
    }

    async validateRelationships(collectionId, cardId) {
        const collection = await this.adapter.getCollectionById(collectionId);

        if (!collection) {
            throw new Error('The collection does not exist.');
        }

        const card = await this.adapter.getCardById(cardId);

        if (!card) {
            throw new Error('The card does not exist.');
        }
    }

    async addCardToCollection(collectionId, cardId) {
        await this.validateRelationships(collectionId, cardId);
        const existing = await this.getInventoryRecord(collectionId, cardId);

        if (existing) {
            existing.quantity += 1;
            await this.adapter.putInventory(existing);
            return existing;
        }

        const inventory = {
            id: crypto.randomUUID(),
            collectionId,
            cardId,
            quantity: 1,
            obtainedAt: this.now().toISOString(),
        };

        await this.adapter.addInventory(inventory);
        return inventory;
    }

    async removeCardFromCollection(collectionId, cardId) {
        const existing = await this.getInventoryRecord(collectionId, cardId);

        if (!existing) {
            return false;
        }

        await this.adapter.deleteInventory(existing.id);
        return true;
    }

    async updateCardQuantity(collectionId, cardId, quantity) {
        if (!Number.isInteger(quantity) || quantity < 0) {
            throw new Error('The card quantity must be zero or a positive integer.');
        }

        await this.validateRelationships(collectionId, cardId);
        const existing = await this.getInventoryRecord(collectionId, cardId);

        if (quantity === 0) {
            if (existing) {
                await this.adapter.deleteInventory(existing.id);
            }
            return false;
        }

        if (existing) {
            existing.quantity = quantity;
            await this.adapter.putInventory(existing);
            return existing;
        }

        const inventory = {
            id: crypto.randomUUID(),
            collectionId,
            cardId,
            quantity,
            obtainedAt: this.now().toISOString(),
        };

        await this.adapter.addInventory(inventory);
        return inventory;
    }

    getCollectionInventory(collectionId) {
        return this.adapter.getCollectionInventory(collectionId);
    }

    async getCardQuantity(collectionId, cardId) {
        const inventory = await this.getInventoryRecord(collectionId, cardId);
        return inventory?.quantity ?? 0;
    }

    getInventoryRecord(collectionId, cardId) {
        return this.adapter.getInventoryByCollectionAndCard(collectionId, cardId);
    }
    async moveCardsToCollection(sourceCollectionId, targetCollectionId, cardIds) {
        if (!Array.isArray(cardIds)) {
            throw new Error('Card IDs must be an array.');
        }

        if (sourceCollectionId === targetCollectionId) {
            throw new Error('Source and target collections must be different.');
        }

        return this.adapter.moveInventoryRecords(
            sourceCollectionId,
            targetCollectionId,
            cardIds,
        );
    }
}
