import { initializeCollectionDetail, initializeCollectionList } from './albumUI.js';

export function initializeAlbumPage() {
    const page = document.querySelector('[data-album-page]');

    if (!page) {
        return;
    }

    if (page.dataset.albumPage === 'index') {
        initializeCollectionList(document.querySelector('[data-collection-list]'));
        return;
    }

    initializeCollectionDetail(
        document.querySelector('[data-collection-cards]'),
        page.dataset.collectionId,
    );
}
