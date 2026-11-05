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
    }

    .collection-card-header,
    .collection-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .4rem;
    }

    .collection-card-header h3 {
        overflow: hidden;
        margin: 0;
        font-size: clamp(.7rem, 1vw, 1rem);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .collection-card-header span {
        color: var(--category-color);
        font-size: .55rem;
        font-weight: 900;
        text-transform: uppercase;
    }

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
        padding: 5%;
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

    .collection-card-footer {
        font-size: .6rem;
    }

    .collection-card-grid .collection-card {
        cursor: pointer;
    }

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

    @media (max-width: 1100px) {
        .collection-card-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .collection-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 380px) {
        .collection-card-grid {
            grid-template-columns: 1fr;
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
            <div class="collection-card-grid" data-collection-cards>
                Contenedor para las cartas de la colección.
            </div>
        </section>

        <dialog class="card-qr-dialog" data-card-qr-dialog aria-labelledby="card-qr-title">
            <div class="card-qr-dialog-body">
                <h2 id="card-qr-title" data-card-qr-title>Información de la carta</h2>
                <img data-card-qr-image alt="Código QR con toda la información de la carta" hidden>
                <p class="card-qr-status" data-card-qr-status></p>
                <button class="card-qr-close" type="button" data-card-qr-close>Cerrar</button>
            </div>
        </dialog>
    </div>
@endsection

@push('head')
    @vite('resources/js/album/show.js')
@endpush
