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

    .album-heading h2 {
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
        cursor: pointer;
    }

    .album-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .reset-collection-button {
        flex: 0 0 auto;
        padding: .85rem 1.15rem;
        border: 1px solid #ef4444;
        border-radius: 999px;
        background: transparent;
        color: #ef4444;
        font: inherit;
        font-size: .82rem;
        font-weight: 800;
        cursor: pointer;
    }

    .reset-collection-button:hover {
        background: rgba(239, 68, 68, .08);
    }

    .reset-collection-button:disabled {
        cursor: wait;
        opacity: .55;
    }

    .reset-status {
        flex-basis: 100%;
        margin: 0;
        color: var(--muted);
        font-size: .78rem;
        text-align: right;
    }

    .collection-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .collection-card {
        min-width: 0;
        min-height: 150px;
        padding: 1.25rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        transition: transform .18s ease, border-color .18s ease;
    }

    .collection-card:hover {
        transform: translateY(-3px);
        border-color: var(--violet);
    }

    .collection-card-link {
        display: flex;
        height: 100%;
        align-items: center;
        gap: 1rem;
        color: inherit;
        text-decoration: none;
    }

    .collection-card-icon {
        display: grid;
        width: 54px;
        height: 54px;
        flex: 0 0 54px;
        place-items: center;
        border-radius: 14px;
        background: rgb(139 92 246 / 12%);
        color: var(--violet);
        font-size: 1.7rem;
    }

    .collection-card-content {
        min-width: 0;
    }

    .collection-card-content strong {
        display: block;
        overflow: hidden;
        margin-bottom: .35rem;
        font-size: 1rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .collection-card-content p {
        margin: 0;
        color: var(--muted);
        font-size: .82rem;
    }

    .collection-dialog {
        width: min(420px, calc(100% - 2rem));
        padding: 0;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        color: inherit;
    }

    .collection-dialog::backdrop {
        background: rgb(0 0 0 / 55%);
    }

    .collection-dialog form {
        display: grid;
        gap: 1rem;
        padding: 1.5rem;
    }

    .collection-dialog h3 {
        margin: 0;
        font-size: 1.35rem;
    }

    .collection-dialog label {
        font-size: .82rem;
        font-weight: 700;
    }

    .collection-dialog input,
    .collection-dialog textarea {
        width: 100%;
        margin-top: .4rem;
        box-sizing: border-box;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--paper);
        color: inherit;
        font: inherit;
        padding: .75rem .85rem;
    }

    .collection-dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
    }

    .collection-dialog-actions button {
        padding: .75rem 1rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface);
        color: inherit;
        font: inherit;
        font-weight: 700;
        cursor: pointer;
    }

    .collection-dialog-actions .collection-submit-button {
        border-color: var(--violet);
        background: var(--violet);
        color: #fff;
    }

    .collection-create-status {
        min-height: 1em;
        margin: 0;
        color: var(--muted);
        font-size: .78rem;
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

        .album-actions {
            justify-content: flex-start;
        }

        .reset-status {
            text-align: left;
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
                <h2>Mis colecciones</h2>
            </div>
            <div class="album-actions">
                <button class="collection-create-button" type="button" data-create-collection-button>
                    Nueva colección
                </button>
                <button
                    class="reset-collection-button"
                    type="button"
                    data-reset-collection-data
                >
                    Eliminar todas las cartas y colecciones
                </button>
                <p class="reset-status" data-reset-status aria-live="polite"></p>
            </div>
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

    <dialog class="collection-dialog" data-create-collection-dialog>
        <form method="dialog" data-create-collection-form>
            <h3>Nueva colección</h3>
            <div>
                <label for="collection-name">Nombre</label>
                <input id="collection-name" name="name" maxlength="80" required>
            </div>
            <div>
                <label for="collection-description">Descripción</label>
                <textarea id="collection-description" name="description" rows="3" maxlength="160"></textarea>
            </div>
            <p class="collection-create-status" data-create-collection-status aria-live="polite"></p>
            <div class="collection-dialog-actions">
                <button type="button" data-cancel-create-collection>Cancelar</button>
                <button class="collection-submit-button" type="submit">Crear colección</button>
            </div>
        </form>
    </dialog>
@endsection

@push('head')
    @vite('resources/js/album/index.js')
@endpush
