import {
    cardService,
    collectionService,
    inventoryService,
} from './services/index.js';

const select = document.getElementById('collection-select');
const addButton = document.getElementById('add-to-collection');
const status = document.getElementById('collection-action-status');
let currentCard = null;

function setStatus(message, isError = false) {
    if (!status) {
        return;
    }

    status.textContent = message;
    status.style.color = isError ? 'var(--contrast-bg)' : 'var(--muted)';
}

async function refreshCollections(clearStatus = true) {
    if (!select || !addButton) {
        return;
    }

    try {
        const collections = await collectionService.getCollections();
        select.replaceChildren();

        if (collections.length === 0) {
            select.add(new Option('Sin colecciones', ''));
            select.disabled = true;
            addButton.disabled = true;
            setStatus('Crea una colección desde INFEST Collection.');
            return;
        }

        for (const collection of collections) {
            select.add(new Option(collection.name, collection.id));
        }

        const defaultCollection = collections.find(
            (collection) => collection.id === 'default',
        );
        select.value = defaultCollection?.id ?? collections[0].id;
        select.disabled = false;
        addButton.disabled = false;
        if (clearStatus) {
            setStatus('');
        }
    } catch (error) {
        select.disabled = true;
        addButton.disabled = true;
        setStatus(error.message, true);
    }
}

document.addEventListener('infest:card-ready', (event) => {
    currentCard = event.detail;
});

addButton?.addEventListener('click', async () => {
    const collectionId = select?.value;

    if (!collectionId) {
        setStatus('Selecciona una colección antes de continuar.', true);
        return;
    }

    if (!currentCard) {
        setStatus('Obtén una carta antes de añadirla.', true);
        return;
    }

    addButton.disabled = true;
    setStatus('Añadiendo carta…');

    try {
        const card = await cardService.createCard(currentCard);
        await inventoryService.addCardToCollection(collectionId, card.id);
        setStatus(`«${card.name}» añadida a la colección.`);
    } catch (error) {
        console.error(error);
        setStatus(error.message, true);
    } finally {
        await refreshCollections(false);
    }
});

refreshCollections();
