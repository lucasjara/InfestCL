@extends('layouts.base')

@section('title', 'Detalle de colección | INFEST Chile')

@section('meta_description', 'Detalle de una colección de cartas INFEST.')

@section('navigation')
    <x-experience-navigation />
    <a href="{{ route('album.index') }}">Colecciones</a>
    <a href="{{ route('cards.list') }}">Crear Carta</a>
@endsection

@section('styles')
    .album-detail-page {
        padding-block: clamp(1.5rem, 5vw, 3rem);
    }

    .album-detail-heading {
        margin-bottom: 1.75rem;
    }

    .album-detail-heading a {
        display: inline-block;
        margin-bottom: 1rem;
        color: var(--muted);
        font-size: .82rem;
        font-weight: 700;
        text-decoration: none;
    }

    .album-detail-heading p {
        margin: 0 0 .45rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .album-detail-heading h1 {
        margin: 0;
        font-size: clamp(2.25rem, 7vw, 4.75rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .collection-card-grid {
        min-height: 220px;
        padding: 1rem;
        border: 1px dashed var(--line);
        border-radius: 18px;
        background: var(--surface);
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
    }

    .collection-card-grid .collection-card {
        --category-color: #8b5cf6;
        display: flex;
        min-width: 0;
        aspect-ratio: 15 / 23;
        flex-direction: column;
        gap: 3%;
        overflow: hidden;
        margin: 0;
        padding: 4%;
        border: 3px solid var(--category-color);
        border-radius: 12px;
        background: #17121f;
        color: #fff;
        box-shadow: 0 0 25px color-mix(in srgb, var(--category-color) 25%, transparent);
        cursor: pointer;
        position: relative;
    }

    /* ================================
       ENCABEZADO DE LA CARTA (Superior)
       ================================ */
    .collection-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        padding: 0 2px;
    }

    .collection-card-header h3 {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        margin: 0;
        font-size: clamp(.7rem, 1vw, .95rem);
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: left;
    }

    .collection-card-category-label {
        color: var(--category-color);
        font-size: .55rem;
        font-weight: 900;
        text-transform: uppercase;
        white-space: nowrap;
        margin-left: auto;
    }

    /* Selector individual (Checkbox) */
    .collection-card-select {
        display: flex;
        width: 22px;
        height: 22px;
        flex: 0 0 22px;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 2px solid #a78bfa;
        border-radius: 5px;
        background: #17121f;
        color: #fff;
        cursor: pointer;
        z-index: 2;
    }

    .collection-card-select::before {
        content: '';
        width: 9px;
        height: 5px;
        border-bottom: 2px solid transparent;
        border-left: 2px solid transparent;
        transform: rotate(-45deg) translate(1px, -1px);
    }

    .collection-card-select[aria-pressed="true"] {
        border-color: #8b5cf6;
        background: #8b5cf6;
    }

    .collection-card-select[aria-pressed="true"]::before {
        border-color: #fff;
    }

    .collection-card-grid .collection-card.is-selected {
        border-color: #a78bfa;
        box-shadow:
            0 0 0 3px rgb(139 92 246 / 35%),
            0 0 25px rgb(139 92 246 / 35%);
    }

    /* ================================
       IMAGEN Y DESCRIPCIÓN
       ================================ */
    .collection-card-image {
        flex: 1;
        min-height: 0;
        overflow: hidden;
        border: 1px solid var(--category-color);
        border-radius: 6px;
    }

    .collection-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .collection-card-description {
        display: flex;
        min-height: 20%;
        align-items: center;
        padding: 5% 7%;
        border-radius: 5px;
        background: #28212f;
    }

    .collection-card-description p {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        font-size: clamp(.48rem, .75vw, .68rem);
        line-height: 1.5;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
    }

    /* ================================
       PIE DE PÁGINA (Footer)
       ================================ */
    .collection-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .4rem;
        font-size: .6rem;
        min-width: 0;
    }

    .collection-card-remove {
        background: transparent;
        border: none;
        color: #888;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
        flex: 0 0 auto;
    }

    .collection-card-remove:hover {
        color: #ff4d4d;
        background-color: rgba(255, 77, 77, 0.1);
    }

    /* ================================
       EFECTOS Y MEDIA QUERIES
       ================================ */
    .collection-card-grid .collection-card:hover,
    .collection-card-grid .collection-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px color-mix(in srgb, var(--category-color) 35%, transparent);
        outline: none;
    }

    .card-qr-dialog {
        width: min(420px, calc(100% - 2rem));
        padding: 0;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        color: var(--ink);
        box-shadow: 0 24px 80px rgb(0 0 0 / 35%);
    }

    .card-qr-dialog::backdrop {
        background: rgb(0 0 0 / 65%);
        backdrop-filter: blur(4px);
    }

    .card-qr-dialog-body {
        display: grid;
        justify-items: center;
        gap: 1rem;
        padding: 1.25rem;
        text-align: center;
    }

    .card-qr-dialog-body h2 {
        margin: 0;
        font-size: 1.25rem;
    }

    .card-qr-dialog-body img {
        width: min(320px, 100%);
        height: auto;
        border-radius: 8px;
        background: #fff;
    }

    .card-qr-status {
        min-height: 1.25rem;
        margin: 0;
        color: var(--muted);
        font-size: .85rem;
    }

    .card-qr-close {
        width: 100%;
        padding: .75rem 1rem;
        border: 0;
        border-radius: 999px;
        background: var(--contrast-bg);
        color: var(--contrast-fg);
        font: inherit;
        font-weight: 800;
        cursor: pointer;
    }

    .qr-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 2rem;
    }

    .qr-loading[hidden] {
        display: none;
    }

    .qr-spinner {
        width: 42px;
        height: 42px;
        border: 4px solid #e5e7eb;
        border-top-color: #8b5cf6;
        border-radius: 50%;
        animation: qr-spin 0.8s linear infinite;
    }

    @keyframes qr-spin {
        to {
            transform: rotate(360deg);
        }
    }

    .collection-filters {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .65rem;
        margin-bottom: 1.25rem;
    }

    .collection-filter-btn {
        padding: .6rem 1.1rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface);
        color: var(--muted);
        font-size: .8rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .2s ease;
    }

    .collection-filter-btn:hover {
        border-color: #8b5cf6;
        color: var(--ink);
    }

    .collection-filter-btn.active {
        border-color: #8b5cf6;
        background: #8b5cf6;
        color: #fff;
    }

    .collection-filter-btn:focus-visible {
        outline: 2px solid #a78bfa;
        outline-offset: 3px;
    }

    .collection-filters[hidden] {
        display: none;
    }

    .collection-card[hidden] {
        display: none !important;
    }

    .card-selection-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .65rem;
        margin-bottom: 1rem;
        padding: .85rem 1rem;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
    }

    .card-selection-toolbar[hidden] {
        display: none !important;
    }

    .card-selection-count {
        margin-right: auto;
        color: var(--ink);
        font-size: .85rem;
        font-weight: 800;
    }

    .card-selection-toolbar button {
        padding: .55rem .9rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: transparent;
        color: var(--ink);
        font: inherit;
        font-size: .78rem;
        font-weight: 800;
        cursor: pointer;
    }

    .card-selection-toolbar button:hover {
        border-color: #8b5cf6;
    }

    .card-selection-toolbar [data-selection-move] {
        border-color: #8b5cf6;
        background: #8b5cf6;
        color: #fff;
    }

    .card-selection-toolbar button:disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    /* Diálogo para elegir colección destino */
    .move-cards-dialog {
        width: min(420px, calc(100% - 2rem));
        padding: 0;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        color: var(--ink);
        box-shadow: 0 24px 80px rgb(0 0 0 / 35%);
    }

    .move-cards-dialog::backdrop {
        background: rgb(0 0 0 / 65%);
        backdrop-filter: blur(4px);
    }

    .move-cards-dialog form {
        display: grid;
        gap: 1rem;
        padding: 1.5rem;
    }

    .move-cards-dialog h2 {
        margin: 0;
        font-size: 1.3rem;
    }

    .move-cards-dialog p {
        margin: 0;
        color: var(--muted);
        font-size: .9rem;
    }

    .move-cards-dialog label {
        font-size: .85rem;
        font-weight: 800;
    }

    .move-cards-dialog select {
        width: 100%;
        min-width: 0;
        padding: .8rem;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--surface);
        color: var(--ink);
        font: inherit;
    }

    .move-cards-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: .65rem;
    }

    .move-cards-actions button {
        padding: .7rem 1rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: transparent;
        color: var(--ink);
        font: inherit;
        font-size: .8rem;
        font-weight: 800;
        cursor: pointer;
    }

    .move-cards-actions button[type="submit"] {
        border-color: #8b5cf6;
        background: #8b5cf6;
        color: #fff;
    }

    .move-cards-actions button:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    @media (max-width: 1100px) {
        .collection-card-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .collection-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .card-selection-toolbar {
            align-items: stretch;
        }
        .card-selection-count {
            width: 100%;
        }
        .card-selection-toolbar button {
            flex: 1;
        }
    }

    @media (max-width: 380px) {
        .collection-card-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .collection-card-grid .collection-card {
            transition: none;
        }
        .qr-spinner {
            animation-duration: 2s;
        }
    }
@endsection

@section('content')
    <div class="shell album-detail-page" data-album-page="detail" data-collection-id="{{ $id }}">
        <header class="album-detail-heading">
            <a href="{{ route('album.index') }}">← Volver a colecciones</a>
            <p>INFEST Collection</p>
            <h1>Detalle de colección</h1>
        </header>

        <section aria-label="Cartas de la colección">
            <div class="collection-filters"
                data-collection-filters
                aria-label="Filtrar cartas por tipo"
                hidden>
            </div>
            <div class="collection-card-grid" data-collection-cards>
                Contenedor para las cartas de la colección.
            </div>
        </section>

        <dialog class="card-qr-dialog" data-card-qr-dialog aria-labelledby="card-qr-title">
            <div class="card-qr-dialog-body">
                <h2 id="card-qr-title" data-card-qr-title>
                    Información de la carta
                </h2>

                <div data-card-qr-loading class="qr-loading" hidden>
                    <div class="qr-spinner"></div>
                    <span>Generando código QR...</span>
                </div>

                <img
                    data-card-qr-image
                    alt="Código QR con toda la información de la carta"
                    hidden
                >

                <p class="card-qr-status" data-card-qr-status></p>

                <button class="card-qr-close" type="button" data-card-qr-close>
                    Cerrar
                </button>
            </div>
        </dialog>
    </div>
@endsection

@push('head')
    @vite('resources/js/album/show.js')
@endpush