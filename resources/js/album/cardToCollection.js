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
        notifyAchievement('first_card_collected');
        notifyAchievement('ten_cards_collected');
        notifyAchievement('thousand_cards_collected');
        if(card.type === 'pokemon'){
            notifyAchievement('poke_maniaco');
        }else if(card.type === 'personal'){
            notifyAchievement('custom_card_created');
        }
        await inventoryService.addCardToCollection(collectionId, card.id);
        var name_card = `«${card.name}»`;
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: `${name_card} añadida con éxito`,
            showConfirmButton: false,
            timer: 2000,
            background: '#18181b',
            color: '#f4f4f5'
        });
        setStatus('');
    } catch (error) {
        console.error(error);
        setStatus(error.message, true);
    } finally {
        await refreshCollections(false);
    }
});

refreshCollections();
