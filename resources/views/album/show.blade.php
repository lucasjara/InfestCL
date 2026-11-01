@extends('layouts.base')

@section('title', 'Detalle de colección | INFEST Chile')

@section('meta_description', 'Detalle de una colección de cartas INFEST.')

@section('navigation')
    <a href="{{ route('album.index') }}">Colecciones</a>
    <a href="{{ route('cards.list') }}">Cartas</a>
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
    </div>
@endsection

@push('head')
    @vite('resources/js/album/show.js')
@endpush
