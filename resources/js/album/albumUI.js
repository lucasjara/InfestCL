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

        initializeCardQrDialog();

        for (const item of inventory) {
            const card = await cardService.getCardById(item.cardId);
            if (!card) {
                continue;
            }

            const figure = document.createElement('figure');
            figure.className = 'collection-card';
            figure.tabIndex = 0;
            figure.setAttribute('role', 'button');
            figure.setAttribute('aria-label', `Generar QR con la información de ${card.name}`);
            const categoryPresentation = getCategoryPresentation(card);
            figure.style.setProperty('--category-color', categoryPresentation.color);

            const header = document.createElement('div');
            header.className = 'collection-card-header';
            const name = document.createElement('h3');
            name.textContent = card.name;
            const category = document.createElement('span');
            category.textContent = categoryPresentation.label;
            header.append(name, category);

            const imageContainer = document.createElement('div');
            imageContainer.className = 'collection-card-image';
            const image = document.createElement('img');
            image.src = createCardImage(card);
            image.alt = card.name;
            imageContainer.append(image);

            const descriptionContainer = document.createElement('div');
            descriptionContainer.className = 'collection-card-description';
            const description = document.createElement('p');
            description.textContent = card.description ?? '';
            descriptionContainer.append(description);

            const footer = document.createElement('div');
            footer.className = 'collection-card-footer';

            const number = document.createElement('span');
            number.textContent = card.number ? `#${card.number}` : `× ${item.quantity}`;
            
            const quantity = document.createElement('span');
            quantity.textContent = card.number ? `× ${item.quantity}` : '';

            // --- MEJORA DEL BOTÓN DE ELIMINAR ---
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'collection-card-remove';
            // Usamos un ícono SVG de papelera limpio en lugar de una 'x' roja plana
            removeButton.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            `;
            removeButton.setAttribute('aria-label', `Eliminar ${card.name} de esta colección`);
            removeButton.addEventListener('click', async (event) => {
                event.stopPropagation();

                // Alerta de confirmación personalizada estilo oscuro/TCG
                const result = await Swal.fire({
                    title: '¿Eliminar carta?',
                    text: `¿Estás seguro de quitar "${card.name}" de esta colección?`,
                    icon: 'warning',
                    background: '#18181b', // Fondo oscuro acorde a tus tarjetas
                    color: '#f4f4f5',      // Texto claro
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Rojo elegante para eliminar
                    cancelButtonColor: '#3f3f46',   // Gris sutil para cancelar
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-xl border border-neutral-800 shadow-2xl'
                    }
                });

                if (!result.isConfirmed) {
                    return;
                }

                removeButton.disabled = true;

                try {
                    await inventoryService.removeCardFromCollection(
                        collection.id,
                        card.id
                    );

                    figure.remove();

                    if (!container.querySelector('.collection-card')) {
                        container.textContent = 'Esta colección todavía no tiene cartas.';
                    }

                    // Toast de éxito opcional
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Carta eliminada con éxito',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#18181b',
                        color: '#f4f4f5'
                    });

                } catch (error) {
                    console.error(error);
                    
                    // Alerta de error personalizada
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'No fue posible eliminar la carta.',
                        icon: 'error',
                        background: '#18181b',
                        color: '#f4f4f5',
                        confirmButtonColor: '#3f3f46'
                    });

                    removeButton.disabled = false;
                }
            });

            footer.append(removeButton,number);

            figure.append(header, imageContainer, descriptionContainer, footer);
            const openQr = () => openCardQr(card, item.quantity, collection);
            figure.addEventListener('click', openQr);
            figure.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openQr();
                }
            });
            container.append(figure);
        }
    } catch (error) {
        container.textContent = error.message;
    }
}
