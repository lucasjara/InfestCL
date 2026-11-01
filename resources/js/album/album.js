import { initializeCollectionDetail, initializeCollectionList } from './albumUI.js';
import { collectionService } from './services/index.js';

export function initializeAlbumPage() {
    const page = document.querySelector('[data-album-page]');

    if (!page) {
        return;
    }

    collectionService.getCollections().catch((error) => {
        console.error('No fue posible abrir INFEST Collection.', error);
    });

    if (page.dataset.albumPage === 'index') {
        initializeCollectionList(document.querySelector('[data-collection-list]'));
        return;
    }

    initializeCollectionDetail(
        document.querySelector('[data-collection-cards]'),
        page.dataset.collectionId,
    );
}
