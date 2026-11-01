function nowIso(now) {
    return now().toISOString();
}

function defaultUuid() {
    return crypto.randomUUID();
}

export const DEFAULT_COLLECTION_ID = 'default';

export class CollectionService {
    constructor(adapter, options = {}) {
        this.adapter = adapter;
        this.now = options.now ?? (() => new Date());
        this.createUuid = options.createUuid ?? defaultUuid;
    }

    async createCollection(data) {
        const name = String(data?.name ?? '').trim();

        if (!name) {
            throw new Error('The collection name is required.');
        }

        const timestamp = nowIso(this.now);
        const collection = {
            id: this.createUuid(),
            name,
            description: String(data.description ?? '').trim(),
            createdAt: timestamp,
            updatedAt: timestamp,
        };

        await this.adapter.putCollection(collection);
        return collection;
    }

    getCollections() {
        return this.adapter.getCollections();
    }

    getCollectionById(id) {
        return this.adapter.getCollectionById(id);
    }

    async updateCollection(id, data) {
        const current = await this.getCollectionById(id);

        if (!current) {
            throw new Error('The collection does not exist.');
        }

        const name = String(data?.name ?? current.name ?? '').trim();

        if (!name) {
            throw new Error('The collection name is required.');
        }

        const collection = {
            ...current,
            name,
            description: String(data.description ?? current.description ?? '').trim(),
            updatedAt: nowIso(this.now),
        };

        await this.adapter.putCollection(collection);
        return collection;
    }

    async deleteCollection(id) {
        if (id === DEFAULT_COLLECTION_ID) {
            throw new Error('The default collection cannot be deleted.');
        }

        const current = await this.getCollectionById(id);

        if (!current) {
            throw new Error('The collection does not exist.');
        }

        if (typeof this.adapter.deleteCollectionCascade === 'function') {
            await this.adapter.deleteCollectionCascade(id);
            return true;
        }

        const inventory = await this.adapter.getCollectionInventory(id);

        for (const item of inventory) {
            await this.adapter.deleteInventory(item.id);
        }

        await this.adapter.deleteCollection(id);
        return true;
    }
}
