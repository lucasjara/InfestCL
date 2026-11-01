export function initializeCollectionList(container) {
    if (!container) {
        return;
    }

    container.setAttribute('data-album-ready', 'true');
}

export function initializeCollectionDetail(container, collectionId) {
    if (!container) {
        return;
    }

    container.setAttribute('data-album-ready', 'true');
    container.setAttribute('data-loaded-collection-id', collectionId);
}
