@extends('layouts.base')

@section('title', 'Colecciones | INFEST Chile')

@section('meta_description', 'Listado de colecciones de cartas INFEST.')

@section('navigation')
    <a href="{{ url('/') }}">Inicio</a>
    <a href="{{ route('cards.list') }}">Cartas</a>
@endsection

@section('styles')
    .album-page {
        padding-block: clamp(1.5rem, 5vw, 3rem);
    }

    .album-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .album-heading p {
        margin: 0 0 .45rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .album-heading h1 {
        margin: 0;
        font-size: clamp(2.25rem, 7vw, 4.75rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .collection-create-button {
        flex: 0 0 auto;
        padding: .85rem 1.15rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--contrast-bg);
        color: var(--contrast-fg);
        font: inherit;
        font-size: .82rem;
        font-weight: 700;
        cursor: not-allowed;
        opacity: .55;
    }

    .collection-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .collection-empty {
        grid-column: 1 / -1;
        min-height: 220px;
        place-items: center;
        padding: 2rem;
        border: 1px dashed var(--line);
        border-radius: 18px;
        background: var(--surface);
        text-align: center;
    }

    .collection-empty strong {
        display: block;
        margin-bottom: .45rem;
        font-size: 1rem;
    }

    .collection-empty p {
        margin: 0;
        color: var(--muted);
        font-size: .88rem;
    }

    @media (max-width: 600px) {
        .album-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .collection-create-button {
            align-self: flex-start;
        }

        .collection-grid {
            grid-template-columns: 1fr;
        }
    }
@endsection

@section('content')
    <div class="shell album-page" data-album-page="index">
        <header class="album-heading">
            <div>
                <p>INFEST Collection</p>
                <h1>Mis colecciones</h1>
            </div>
            <button class="collection-create-button" type="button" disabled>
                Nueva colección
            </button>
        </header>

        <div class="collection-grid" data-collection-list>
            <div class="collection-empty">
                <div>
                    <strong>Todavía no hay colecciones</strong>
                    <p>La administración local se habilitará en la siguiente etapa.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    @vite('resources/js/album/index.js')
@endpush
