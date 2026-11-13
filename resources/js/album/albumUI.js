import QRCode from 'qrcode';
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

const categoryPresentation = {
    magic: { label: 'MAGIA', color: '#8b5cf6' },
    fire: { label: 'FUEGO', color: '#ef4444' },
    water: { label: 'AGUA', color: '#0ea5e9' },
    nature: { label: 'NATURALEZA', color: '#22c55e' },
    grass: { label: 'PLANTA', color: '#22c55e' },
    dark: { label: 'OSCURIDAD', color: '#64748b' },
    normal: { label: 'NORMAL', color: '#a8a29e' },
    electric: { label: 'ELÉCTRICO', color: '#eab308' },
    ice: { label: 'HIELO', color: '#67e8f9' },
    fighting: { label: 'LUCHA', color: '#dc2626' },
    poison: { label: 'VENENO', color: '#a855f7' },
    ground: { label: 'TIERRA', color: '#d97706' },
    flying: { label: 'VOLADOR', color: '#818cf8' },
    psychic: { label: 'PSÍQUICO', color: '#ec4899' },
    bug: { label: 'BICHO', color: '#84cc16' },
    rock: { label: 'ROCA', color: '#a16207' },
    ghost: { label: 'FANTASMA', color: '#7c3aed' },
    dragon: { label: 'DRAGÓN', color: '#4f46e5' },
    steel: { label: 'ACERO', color: '#94a3b8' },
    fairy: { label: 'HADA', color: '#f472b6' },
};

function getCategoryPresentation(card) {
    const categoryKey = String(card.category ?? card.type ?? 'magic')
        .trim()
        .toLowerCase();

    return categoryPresentation[categoryKey] ?? {
        label: String(card.category ?? card.type ?? 'INFEST').toUpperCase(),
        color: '#8b5cf6',
    };
}

function makeQrSerializable(value) {
    if (value instanceof Blob) {
        return {
            type: value.type,
            size: value.size,
        };
    }

    if (Array.isArray(value)) {
        return value.map((item) => makeQrSerializable(item));
    }

    if (value && typeof value === 'object') {
        return Object.fromEntries(
            Object.entries(value).map(([key, item]) => [
                key,
                makeQrSerializable(item),
            ]),
        );
    }

    return value;
}

async function getAbsoluteCardImageUrl(card) {
    const image = createCardImage(card);

    if (typeof image !== 'string') {
        return null;
    }

    if (image.startsWith('blob:')) {
        const response = await fetch(image);
        const blob = await response.blob();

        const formData = new FormData();
        formData.append('image', blob, 'card.webp');

        const uploadResponse = await fetch('/api/cards/upload-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                )?.content
            }
        });

        if (!uploadResponse.ok) {
            throw new Error('Error al subir la imagen');
        }
        const result = await uploadResponse.json();
        return result.url;
    }

    if (image.startsWith('http://') || image.startsWith('https://')) {
        return image;
    }

    return new URL(
        image.startsWith('/') ? image : `/${image}`,
        window.location.origin
    ).href;
}

async function openCardQr(card, quantity, collection) {
    const dialog = document.querySelector('[data-card-qr-dialog]');
    const qrImage = dialog?.querySelector('[data-card-qr-image]');
    const qrStatus = dialog?.querySelector('[data-card-qr-status]');
    const qrTitle = dialog?.querySelector('[data-card-qr-title]');
    const qrLoading = dialog?.querySelector('[data-card-qr-loading]');

    if (!dialog || !qrImage || !qrStatus) {
        return;
    }

    // Mostrar diálogo inmediatamente.
    qrImage.hidden = true;
    qrLoading && (qrLoading.hidden = false);
    qrStatus.textContent = 'Generando código QR...';

    if (qrTitle) {
        qrTitle.textContent = card.name;
    }

    if (!dialog.open) {
        if (typeof dialog.showModal === 'function') {
            dialog.showModal();
        } else {
            dialog.setAttribute('open', '');
        }
    }

    try {
        const payload = {
            card: {
                ...makeQrSerializable(card),
                imageUrl: await getAbsoluteCardImageUrl(card),
                image: "",
            },
            collection: {
                id: collection.id,
                name: collection.name,
            },
            quantity,
        };

        const generatedImage = await QRCode.toDataURL(
            JSON.stringify(payload),
            {
                width: 320,
                margin: 2,
                errorCorrectionLevel: 'M',
            }
        );

        qrImage.src = generatedImage;
        qrImage.hidden = false;
        qrStatus.textContent = '';

    } catch (error) {
        qrImage.hidden = true;
        qrStatus.textContent =
            'No fue posible generar el QR. La carta contiene demasiada información.';

        console.error('No fue posible generar el QR de la carta.', error);

    } finally {
        if (qrLoading) {
            qrLoading.hidden = true;
        }
    }
}

function initializeCardQrDialog() {
    const dialog = document.querySelector('[data-card-qr-dialog]');
    if (!dialog || dialog.dataset.initialized === 'true') {
        return;
    }

    dialog.dataset.initialized = 'true';
    const closeButton = dialog.querySelector('[data-card-qr-close]');
    closeButton?.addEventListener('click', () => {
        dialog.close?.();
        dialog.removeAttribute('open');
    });
}


export async function initializeCollectionList(container) {
    if (!container) {
        return;
    }

    container.setAttribute('data-album-ready', 'true');
    const resetButton = document.querySelector('[data-reset-collection-data]');
    const resetStatus = document.querySelector('[data-reset-status]');
    const createButton = document.querySelector('[data-create-collection-button]');
    const createDialog = document.querySelector('[data-create-collection-dialog]');
    const createForm = document.querySelector('[data-create-collection-form]');
    const cancelCreateButton = document.querySelector('[data-cancel-create-collection]');
    const createStatus = document.querySelector('[data-create-collection-status]');

    if (createButton && createDialog && !createButton.dataset.initialized) {
        createButton.dataset.initialized = 'true';
        createButton.addEventListener('click', () => {
            if (createStatus) {
                createStatus.textContent = '';
            }
            if (typeof createDialog.showModal === 'function') {
                createDialog.showModal();
                createDialog.querySelector('#collection-name')?.focus();
                return;
            }

            createDialog.setAttribute('open', '');
        });

        cancelCreateButton?.addEventListener('click', () => {
            createDialog.close?.();
            createDialog.removeAttribute('open');
        });

        createForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(createForm);
            const submitButton = createForm.querySelector('.collection-submit-button');

            submitButton.disabled = true;
            if (createStatus) {
                createStatus.textContent = 'Creando colección...';
            }

            try {
                await collectionService.createCollection({
                    name: formData.get('name'),
                    description: formData.get('description'),
                });
                createForm.reset();
                createDialog.close?.();
                createDialog.removeAttribute('open');
                await initializeCollectionList(container);
            } catch (error) {
                if (createStatus) {
                    createStatus.textContent = error.message;
                }
            } finally {
                submitButton.disabled = false;
            }
        });
    }

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
            article.className = 'collection-card';

            const link = document.createElement('a');
            link.href = `/album/${encodeURIComponent(collection.id)}`;
            link.className = 'collection-card-link';

            const icon = document.createElement('span');
            icon.className = 'collection-card-icon';
            icon.setAttribute('aria-hidden', 'true');
            icon.textContent = '◫';

            const content = document.createElement('div');
            content.className = 'collection-card-content';

            const name = document.createElement('strong');
            name.textContent = collection.name;

            const count = document.createElement('p');
            count.textContent = `${cards.filter(Boolean).length} carta(s)`;

            content.append(name, count);
            link.append(icon, content);
            article.append(link);
            container.append(article);
        }
    } catch (error) {
        container.textContent = error.message;
    }
}
function createSelectionToolbar() {
    let toolbar = document.querySelector('[data-card-selection-toolbar]');

    if (toolbar) {
        return toolbar;
    }

    toolbar = document.createElement('div');
    toolbar.className = 'card-selection-toolbar';
    toolbar.dataset.cardSelectionToolbar = '';
    toolbar.hidden = true;

    const counter = document.createElement('span');
    counter.className = 'card-selection-count';
    counter.dataset.selectionCount = '';
    counter.textContent = '0 seleccionadas';

    const selectAllButton = document.createElement('button');
    selectAllButton.type = 'button';
    selectAllButton.dataset.selectionAll = '';
    selectAllButton.textContent = 'Seleccionar todas';

    const moveButton = document.createElement('button');
    moveButton.type = 'button';
    moveButton.dataset.selectionMove = '';
    moveButton.textContent = 'Mover';

    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.dataset.selectionCancel = '';
    cancelButton.textContent = 'Cancelar';

    toolbar.append(counter, selectAllButton, moveButton, cancelButton);

    return toolbar;
}

function createMoveCollectionDialog() {
    let dialog = document.querySelector('[data-move-cards-dialog]');

    if (dialog) {
        return dialog;
    }

    dialog = document.createElement('dialog');
    dialog.className = 'move-cards-dialog';
    dialog.dataset.moveCardsDialog = '';

    dialog.innerHTML = `
        <form data-move-cards-form>
            <h2>Mover cartas</h2>
            <p data-move-cards-description>
                Selecciona la colección de destino.
            </p>

            <label for="move-cards-target">
                Colección de destino
            </label>

            <select id="move-cards-target" required data-move-cards-target>
                <option value="">Selecciona una colección</option>
            </select>

            <p class="move-cards-status" data-move-cards-status role="status"></p>

            <div class="move-cards-actions">
                <button type="button" data-move-cards-cancel>
                    Cancelar
                </button>
                <button type="submit" data-move-cards-submit>
                    Confirmar movimiento
                </button>
            </div>
        </form>
    `;

    document.body.append(dialog);

    dialog.querySelector('[data-move-cards-cancel]')
        ?.addEventListener('click', () => dialog.close());

    return dialog;
}
// Mejora en el manejo de selección y limpieza de estados al refrescar
export async function initializeCollectionDetail(container, collectionId, activeTypeFilter = 'all') {
    if (!container) return;

    container.setAttribute('data-album-ready', 'true');
    container.setAttribute('data-loaded-collection-id', collectionId);

    const filtersContainer = document.querySelector('[data-collection-filters]');
    const selectedCardIds = new Set();

    try {
        const collections = await collectionService.getCollections();
        const collection = collections.find(item => item.id === collectionId) ?? collections.find(item => item.id === 'default');

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
            if (filtersContainer) filtersContainer.hidden = true;
            return;
        }

        initializeCardQrDialog();
        const toolbar = createSelectionToolbar();
        container.before(toolbar);

        const selectionCount = toolbar.querySelector('[data-selection-count]');
        const selectAllButton = toolbar.querySelector('[data-selection-all]');
        const moveButton = toolbar.querySelector('[data-selection-move]');
        const cancelButton = toolbar.querySelector('[data-selection-cancel]');

        const moveDialog = createMoveCollectionDialog();
        const moveForm = moveDialog.querySelector('[data-move-cards-form]');
        const targetSelect = moveDialog.querySelector('[data-move-cards-target]');
        const moveStatus = moveDialog.querySelector('[data-move-cards-status]');
        const moveSubmit = moveDialog.querySelector('[data-move-cards-submit]');
        const moveDescription = moveDialog.querySelector('[data-move-cards-description]');

        const updateSelectionUI = () => {
            const count = selectedCardIds.size;
            toolbar.hidden = count === 0;

            if (selectionCount) {
                selectionCount.textContent = `${count} carta(s) seleccionada(s)`;
            }
            moveButton.disabled = count === 0;

            container.querySelectorAll('.collection-card').forEach((figure) => {
                const isSelected = selectedCardIds.has(figure.dataset.cardId);
                figure.classList.toggle('is-selected', isSelected);
                
                const selectButton = figure.querySelector('[data-card-select]');
                if (selectButton) {
                    selectButton.setAttribute('aria-pressed', String(isSelected));
                    selectButton.textContent = '';
                }
            });
        };

        const clearSelection = () => {
            selectedCardIds.clear();
            updateSelectionUI();
        };

        selectAllButton.onclick = () => {
            const visibleCards = [...container.querySelectorAll('.collection-card[data-card-id]')].filter((figure) => !figure.hidden);
            const allVisibleSelected = visibleCards.length > 0 && visibleCards.every((figure) => selectedCardIds.has(figure.dataset.cardId));

            if (allVisibleSelected) {
                visibleCards.forEach((figure) => selectedCardIds.delete(figure.dataset.cardId));
            } else {
                visibleCards.forEach((figure) => selectedCardIds.add(figure.dataset.cardId));
            }
            updateSelectionUI();
        };

        cancelButton.onclick = clearSelection;

        moveButton.onclick = () => {
            if (selectedCardIds.size === 0) return;
            targetSelect.replaceChildren();

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Selecciona una colección';
            targetSelect.append(placeholder);

            collections
                .filter((item) => item.id !== collection.id)
                .forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    targetSelect.append(option);
                });

            moveDescription.textContent = `Vas a mover ${selectedCardIds.size} carta(s) desde "${collection.name}".`;
            moveStatus.textContent = '';
            moveSubmit.disabled = false;

            if (typeof moveDialog.showModal === 'function') {
                moveDialog.showModal();
            } else {
                moveDialog.setAttribute('open', '');
            }
        };

        moveForm.onsubmit = async (event) => {
            event.preventDefault();
            const targetCollectionId = targetSelect.value;
            if (!targetCollectionId) {
                moveStatus.textContent = 'Selecciona una colección de destino.';
                return;
            }

            const cardIds = [...selectedCardIds];
            moveSubmit.disabled = true;
            moveStatus.textContent = 'Moviendo cartas...';

            try {
                const result = await inventoryService.moveCardsToCollection(collection.id, targetCollectionId, cardIds);
                moveDialog.close();

                await Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `${result.moved} carta(s) movida(s)`,
                    showConfirmButton: false,
                    timer: 2200,
                    background: '#18181b',
                    color: '#f4f4f5',
                });

                await initializeCollectionDetail(container, collection.id);
            } catch (error) {
                console.error('Error al mover cartas:', error);
                moveStatus.textContent = error.message || 'No fue posible mover las cartas.';
                moveSubmit.disabled = false;
            }
        };

        // Renderizado eficiente de cartas
        for (const item of inventory) {
            const card = await cardService.getCardById(item.cardId);
            if (!card) continue;

            const figure = document.createElement('figure');
            figure.className = 'collection-card';
            figure.tabIndex = 0;
            figure.dataset.cardId = card.id;
            figure.dataset.cardType = String(card.type ?? '').trim().toLowerCase();
            figure.setAttribute('aria-label', `Carta ${card.name}. Pulsa para generar su código QR.`);

            const category = getCategoryPresentation(card);
            figure.style.setProperty('--category-color', category.color);

            // ==========================================
            // ENCABEZADO: Selector + Nombre + Categoría
            // ==========================================
            const header = document.createElement('div');
            header.className = 'collection-card-header';

            // 1. Selector de carta (checkbox) a la izquierda del nombre
            const selectButton = document.createElement('button');
            selectButton.type = 'button';
            selectButton.className = 'collection-card-select';
            selectButton.dataset.cardSelect = '';
            selectButton.setAttribute('aria-pressed', 'false');
            selectButton.setAttribute('aria-label', `Seleccionar ${card.name}`);

            selectButton.addEventListener('click', (event) => {
                event.stopPropagation();
                if (selectedCardIds.has(card.id)) {
                    selectedCardIds.delete(card.id);
                } else {
                    selectedCardIds.add(card.id);
                }
                updateSelectionUI();
            });

            // Nombre de la carta
            const name = document.createElement('h3');
            name.textContent = card.name;

            // 2. Categoría ubicada a la derecha del nombre (donde estaba el selector antes)
            const categoryLabel = document.createElement('span');
            categoryLabel.className = 'collection-card-category-label';
            categoryLabel.textContent = category.label;

            header.append(selectButton, name);

            // ==========================================
            // IMAGEN DE LA CARTA
            // ==========================================
            const imageContainer = document.createElement('div');
            imageContainer.className = 'collection-card-image';

            const image = document.createElement('img');
            image.src = createCardImage(card);
            image.alt = card.name;
            imageContainer.append(image);

            // ==========================================
            // DESCRIPCIÓN
            // ==========================================
            const descriptionContainer = document.createElement('div');
            descriptionContainer.className = 'collection-card-description';

            const description = document.createElement('p');
            description.textContent = card.description ?? '';
            descriptionContainer.append(description);

            // ==========================================
            // FOOTER: Papelera + Número/Cantidad
            // ==========================================
            const footer = document.createElement('div');
            footer.className = 'collection-card-footer';

            const number = document.createElement('span');
            number.textContent = card.number ? `#${card.number}` : `× ${item.quantity}`;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'collection-card-remove';
            removeButton.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            `;

            removeButton.addEventListener('click', async (event) => {
                event.stopPropagation();
                const result = await Swal.fire({
                    title: '¿Eliminar carta?',
                    text: `¿Estás seguro de quitar "${card.name}" de esta colección?`,
                    icon: 'warning',
                    background: '#18181b',
                    color: '#f4f4f5',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3f3f46',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                });

                if (!result.isConfirmed) return;

                removeButton.disabled = true;
                try {
                    await inventoryService.removeCardFromCollection(collection.id, card.id);
                    selectedCardIds.delete(card.id);
                    figure.remove();
                    updateCollectionTypeFilters(container, filtersContainer);
                    updateSelectionUI();

                    if (!container.querySelector('.collection-card')) {
                        toolbar.hidden = true;
                        container.textContent = 'Esta colección todavía no tiene cartas.';
                    }
                } catch (error) {
                    console.error(error);
                    removeButton.disabled = false;
                }
            });

            footer.append(categoryLabel,removeButton, number);
            figure.append(header, imageContainer, descriptionContainer, footer);

            figure.addEventListener('click', () => {
                openCardQr(card, item.quantity, collection);
            });

            container.append(figure);
        }

        updateCollectionTypeFilters(container, filtersContainer);
        updateSelectionUI();

    } catch (error) {
        console.error(error);
        container.textContent = error.message;
    }
}

const cardTypePresentation = {
    pokemon: 'Pokémon',
    personal: 'Personalizadas',
    default: 'Predeterminadas',
};

function updateCollectionTypeFilters(container, filtersContainer) {
    if (!filtersContainer) return;

    const activeButton = filtersContainer.querySelector(
        '.collection-filter-btn.active'
    );

    let selectedType = activeButton?.dataset.type ?? 'all';

    const cards = [
        ...container.querySelectorAll('.collection-card[data-card-type]')
    ];

    const availableTypes = [
        ...new Set(
            cards
                .map(card => card.dataset.cardType)
                .filter(Boolean)
        )
    ];

    filtersContainer.replaceChildren();

    if (availableTypes.length === 0) {
        filtersContainer.hidden = true;
        return;
    }

    if (
        selectedType !== 'all' &&
        !availableTypes.includes(selectedType)
    ) {
        selectedType = 'all';
    }

    const types = [
        { value: 'all', label: 'Todas' },
        ...availableTypes.map(type => ({
            value: type,
            label: cardTypePresentation[type] ?? type.toUpperCase()
        }))
    ];

    types.forEach(({ value, label }) => {
        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'collection-filter-btn';
        button.dataset.type = value;
        button.textContent = label;

        const active = selectedType === value;

        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', String(active));

        button.addEventListener('click', () => {
            selectedType = value;

            filtersContainer
                .querySelectorAll('.collection-filter-btn')
                .forEach(btn => {
                    const isActive = btn.dataset.type === value;
                    btn.classList.toggle('active', isActive);
                    btn.setAttribute('aria-pressed', String(isActive));
                });

            cards.forEach(card => {
                card.hidden =
                    value !== 'all' &&
                    card.dataset.cardType !== value;
            });
        });

        filtersContainer.append(button);
    });

    filtersContainer.hidden = false;

    cards.forEach(card => {
        card.hidden =
            selectedType !== 'all' &&
            card.dataset.cardType !== selectedType;
    });
}