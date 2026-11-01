function defaultUuid() {
    return crypto.randomUUID();
}

export class CardService {
    constructor(adapter, options = {}) {
        this.adapter = adapter;
        this.createUuid = options.createUuid ?? defaultUuid;
        this.now = options.now ?? (() => new Date());
    }

    validateCard(data, personalCard = false) {
        const name = String(data?.name ?? '').trim();

        if (!name) {
            throw new Error('The card name is required.');
        }

        const type = String(data?.type ?? 'default');
        const allowedTypes = ['default', 'pokemon', 'personal'];

        if (!allowedTypes.includes(type)) {
            throw new Error('The card type is invalid.');
        }

        if (personalCard || type === 'personal') {
            if (!data?.image) {
                throw new Error('Personal cards require an image.');
            }
        }
    }

    async createCard(data) {
        this.validateCard(data);

        const type = String(data.type ?? 'default');
        const requiresUuid = type === 'personal';
        let id;

        if (requiresUuid) {
            id = this.createUuid();
        } else if (type === 'pokemon') {
            id = String(data.id ?? (
                data.externalId ? `pokemon-${data.externalId}` : ''
            )).trim();
        } else {
            id = String(data.id ?? '').trim();
        }

        if (!id) {
            throw new Error('Default and Pokemon cards require a stable identifier.');
        }

        const card = {
            ...data,
            id,
            type,
            name: String(data.name).trim(),
            createdAt: data.createdAt ?? this.now().toISOString(),
        };

        await this.adapter.putCard(card);
        return card;
    }

    getCardById(id) {
        return this.adapter.getCardById(id);
    }

    async getCardsByIds(ids) {
        const uniqueIds = [...new Set(ids ?? [])];
        const cards = await Promise.all(
            uniqueIds.map((id) => this.adapter.getCardById(id)),
        );

        return cards.filter(Boolean);
    }

    async updateCard(id, data) {
        const current = await this.getCardById(id);

        if (!current) {
            throw new Error('The card does not exist.');
        }

        if (data?.type && data.type !== current.type) {
            throw new Error('The card type cannot be changed.');
        }

        if (data?.id && data.id !== current.id) {
            throw new Error('The card identifier cannot be changed.');
        }

        this.validateCard({ ...current, ...data });

        const card = {
            ...current,
            ...data,
            id: current.id,
            name: String(data.name ?? current.name).trim(),
        };

        await this.adapter.putCard(card);
        return card;
    }

    async deleteCard(id) {
        const current = await this.getCardById(id);

        if (!current) {
            throw new Error('The card does not exist.');
        }

        const inventory = await this.adapter.getInventory();
        const inUse = inventory.some((item) => item.cardId === id);

        if (inUse) {
            throw new Error('The card is still used by one or more collections.');
        }

        await this.adapter.deleteCard(id);
        return true;
    }
}
