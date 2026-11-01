import {
    cardService,
    collectionService,
    inventoryService,
} from './services/index.js';
import { albumStorageAdapter } from './storageConfig.js';

function createCardImage(card) {
    if (card.image instanceof Blob) {
        return URL.createObjectURL(card.image);
    }

    if (typeof card.image === 'string') {
        return card.image.startsWith('http') || card.image.startsWith('/')
            ? card.image
            : `/${card.image}`;
    }

    return '/images/cards/dragon-astral.webp';
}

export async function initializeCollectionList(container) {
    if (!container) {
        return;
    }

    container.setAttribute('data-album-ready', 'true');
    const resetButton = document.querySelector('[data-reset-collection-data]');
    const resetStatus = document.querySelector('[data-reset-status]');

    if (resetButton && !resetButton.dataset.initialized) {
        resetButton.dataset.initialized = 'true';
        resetButton.addEventListener('click', async () => {
            const confirmed = window.confirm(
                '¿Eliminar todas las cartas, inventarios y colecciones? La colección predeterminada se volverá a crear.',
            );

            if (!confirmed) {
                return;
            }

            resetButton.disabled = true;
            if (resetStatus) {
                resetStatus.textContent = 'Eliminando datos...';
            }

            try {
                await albumStorageAdapter.resetCollectionData();
                await initializeCollectionList(container);
                if (resetStatus) {
                    resetStatus.textContent = 'Todas las cartas y colecciones fueron eliminadas.';
                }
            } catch (error) {
                if (resetStatus) {
                    resetStatus.textContent = error.message;
                }
            } finally {
                resetButton.disabled = false;
            }
        });
    }

    try {
        const collections = await collectionService.getCollections();
        container.replaceChildren();

        const orderedCollections = [
            ...collections.filter((collection) => collection.id === 'default'),
            ...collections.filter((collection) => collection.id !== 'default'),
        ];

        for (const collection of orderedCollections) {
            const inventory = await inventoryService.getCollectionInventory(collection.id);
            const cards = await Promise.all(
                inventory.map((item) => cardService.getCardById(item.cardId)),
            );
            const article = document.createElement('article');
            article.className = 'collection-empty';

            const link = document.createElement('a');
            link.href = `/album/${encodeURIComponent(collection.id)}`;
            link.textContent = collection.name;
            link.style.textDecoration = 'none';

            const count = document.createElement('p');
            count.textContent = `${cards.filter(Boolean).length} carta(s)`;

            article.append(link, count);
            container.append(article);
        }
    } catch (error) {
        container.textContent = error.message;
    }
}

export async function initializeCollectionDetail(container, collectionId) {
    if (!container) {
        return;
    }

    container.setAttribute('data-album-ready', 'true');
    container.setAttribute('data-loaded-collection-id', collectionId);

    try {
        const collections = await collectionService.getCollections();
        const collection = collections.find((item) => item.id === collectionId)
            ?? collections.find((item) => item.id === 'default');

        if (!collection) {
            container.textContent = 'La colección predeterminada no está disponible.';
            return;
        }

        const heading = document.querySelector('.album-detail-heading h1');
        if (heading) {
            heading.textContent = collection.name;
        }

        const inventory = await inventoryService.getCollectionInventory(collection.id);
        container.replaceChildren();

        if (inventory.length === 0) {
            container.textContent = 'Esta colección todavía no tiene cartas.';
            return;
        }

        container.style.display = 'grid';
        container.style.gridTemplateColumns = 'repeat(auto-fill, minmax(160px, 1fr))';
        container.style.gap = '1rem';

        for (const item of inventory) {
            const card = await cardService.getCardById(item.cardId);
            if (!card) {
                continue;
            }

            const figure = document.createElement('figure');
            figure.style.margin = '0';
            const image = document.createElement('img');
            image.src = createCardImage(card);
            image.alt = card.name;
            image.style.width = '100%';
            image.style.borderRadius = '12px';
            const caption = document.createElement('figcaption');
            caption.textContent = `${card.name} × ${item.quantity}`;
            figure.append(image, caption);
            container.append(figure);
        }
    } catch (error) {
        container.textContent = error.message;
    }
}
