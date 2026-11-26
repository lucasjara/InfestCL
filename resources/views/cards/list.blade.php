@extends('layouts.base')

@section('title', 'Carta random | INFEST Chile')

@section('meta_description', 'Visualización de la carta random.')

@section('navigation')
    <x-experience-navigation />
    <a href="{{ route('album.index') }}">
        <i data-lucide="layers"></i>
        Colecciones
    </a>
    <a href="{{ route('cards.list') }}" aria-current="page">
        <i data-lucide="sparkles"></i>
        Generador
    </a>
@endsection

@section('styles')
    .bracket-page {
        padding-block: clamp(1.5rem, 5vw, 3rem);
    }

    .bracket-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 1.75rem;
    }

    .bracket-kicker {
        margin: 0 0 .45rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .bracket-heading h1 {
        max-width: 760px;
        margin: 0;
        font-size: clamp(2.25rem, 7vw, 4.75rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .new-tournament-button {
        flex: 0 0 auto;
        padding: .85rem 1.15rem;
        border-radius: 999px;
        background: var(--contrast-bg);
        color: var(--contrast-fg);
        font-size: .82rem;
        font-weight: 500;
        text-decoration: none;
    }

    .tournament-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        margin-bottom: 1.75rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .summary-item {
        min-width: 0;
        padding: 1rem 1.25rem;
        border-right: 1px solid var(--line);
    }

    .summary-item:last-child {
        border-right: 0;
    }

    .summary-item span {
        display: block;
        margin-bottom: .25rem;
        color: var(--muted);
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .summary-item strong {
        display: block;
        overflow: hidden;
        font-size: .95rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 230px;
        gap: 1.25rem;
        align-items: start;
    }

    .bracket-panel,
    .prizes-panel {
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .bracket-panel {
        overflow-x: auto;
    }

    .competition-column {
        min-width: 0;
    }

    .third-place-panel {
        margin-top: 1rem;
        padding: 1rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .third-place-panel h2 {
        margin: 0 0 .75rem;
        font-size: .9rem;
    }

    .third-place-candidates {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
    }

    .third-place-candidate {
        display: flex;
        min-height: 48px;
        align-items: center;
        gap: .55rem;
        padding: .55rem .65rem;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
        font-size: .8rem;
        font-weight: 750;
    }

    .third-place-candidate.is-winner {
        border-color: var(--violet);
    }

    .bracket {
        display: flex;
        min-width: max-content;
        gap: 1.25rem;
        padding: 1.25rem;
    }

    .round-column {
        width: 210px;
    }

    .round-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 38px;
        margin-bottom: .75rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .round-heading span:last-child {
        display: grid;
        width: 24px;
        height: 24px;
        place-items: center;
        border-radius: 50%;
        background: var(--paper);
        font-size: .68rem;
    }

    .matches {
        display: flex;
        min-height: 500px;
        flex-direction: column;
        justify-content: space-around;
        gap: 1rem;
    }

    .match-card {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
    }

    .match-card::before {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 3px;
        background: var(--violet);
        content: "";
        opacity: .75;
    }

    .participant-slot {
        display: flex;
        min-height: 48px;
        align-items: center;
        gap: .55rem;
        padding: .55rem .65rem;
        font-size: .8rem;
        font-weight: 750;
    }

    .participant-slot + .participant-slot {
        border-top: 1px solid var(--line);
    }

    .participant-slot.is-empty {
        color: var(--muted);
        font-weight: 550;
    }

    .participant-slot.is-winner {
        background: var(--surface);
    }

    .slot-participant-name {
        min-width: 0;
        flex: 1;
    }

    .winner-action {
        width: 28px;
        height: 28px;
        flex: 0 0 auto;
        display: grid;
        place-items: center;
        padding: 0;
        border: 1px solid var(--line);
        border-radius: 50%;
        background: transparent;
        color: var(--muted);
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        transition: background .15s ease, color .15s ease, border-color .15s ease;
    }

    .winner-action:hover {
        border-color: var(--violet);
        color: var(--violet);
    }

    .participant-slot.is-winner .winner-action {
        border-color: var(--violet);
        background: var(--violet);
        color: var(--contrast-bg);
    }

    .slot-index {
        display: grid;
        width: 22px;
        height: 22px;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 50%;
        background: var(--surface);
        border: 1px solid var(--line);
        font-size: .62rem;
    }

    .prizes-panel {
        padding: 1rem;
    }

    .prizes-panel h2 {
        margin: 0 0 .75rem;
        font-size: .9rem;
    }

    .prize-card {
        margin-top: .65rem;
        padding: .85rem;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
    }

    .prize-place {
        color: var(--muted);
        font-size: .68rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .prize-card strong {
        display: block;
        margin-top: .2rem;
        font-size: .88rem;
    }

    .prize-card p {
        margin: .35rem 0 0;
        color: var(--muted);
        font-size: .76rem;
        line-height: 1.45;
    }

    .prize-winner {
        margin-top: .75rem;
        padding-top: .75rem;
        border-top: 1px solid var(--line);
        color: var(--violet);
        font-size: .78rem;
        font-weight: 750;
    }

    .third-place {
        margin-top: 1rem;
        padding: 1rem;
        border: 1px dashed var(--line);
        border-radius: 12px;
        text-align: center;
    }

    .easter-egg-card {
        margin-top: .75rem;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
    }

    .easter-egg-card img {
        display: block;
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .empty-state {
        max-width: 520px;
        margin-inline: auto;
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state h1 {
        margin-bottom: .5rem;
    }

    .empty-state p {
        color: var(--muted);
    }

    @media (max-width: 900px) {
        .tournament-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .summary-item:nth-child(2) {
            border-right: 0;
        }

        .summary-item:nth-child(-n + 2) {
            border-bottom: 1px solid var(--line);
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .bracket-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .tournament-summary {
            grid-template-columns: 1fr;
        }

        .summary-item,
        .summary-item:nth-child(2) {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }

        .summary-item:last-child {
            border-bottom: 0;
        }

        .round-column {
            width: 190px;
        }

        .third-place-candidates {
            grid-template-columns: 1fr;
        }
    }
    
    .card-generator-page {
        padding-block: clamp(0.5rem, 5vw, 1rem);
    }

    .generator-heading {
        margin-bottom: 2rem;
    }

    .generator-kicker,
    .panel-kicker {
        color: var(--muted);
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .12em;
    }

    .generator-heading h1 {
        margin: .5rem 0;
        font-size: clamp(2rem, 5vw, 3.5rem);
        letter-spacing: -.06em;
    }

    .generator-description {
        color: var(--muted);
    }

    .generator-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
        align-items: stretch;
    }

    .generator-panel,
    .result-panel {
        min-width: 0;
        padding: 1.5rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .panel-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .panel-heading h2 {
        margin: .35rem 0 0;
        font-size: 1.2rem;
    }

    .generate-button {
        width: auto;
        min-height: 42px;
        padding-inline: 1.25rem;
        white-space: nowrap;
        border-radius: 999px;
    }

    .result-actions {
        display: flex;
        align-items: center;
        gap: .5rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .collection-select,
    .add-to-collection-button {
        min-height: 42px;
        border-radius: 999px;
        font: inherit;
        font-size: .78rem;
        font-weight: 750;
    }

    .collection-select {
        max-width: 180px;
        padding: .65rem 2rem .65rem .85rem;
        border: 1px solid var(--line);
        background: var(--paper);
        color: var(--ink);
    }

    .add-to-collection-button {
        padding: .65rem 1rem;
        border: 1px solid var(--violet);
        background: transparent;
        color: var(--violet);
        cursor: pointer;
    }

    .add-to-collection-button:disabled,
    .collection-select:disabled {
        cursor: not-allowed;
        opacity: .55;
    }

    .collection-action-status {
        width: 100%;
        min-height: 1rem;
        margin: 0;
        color: var(--muted);
        font-size: .72rem;
        text-align: right;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        margin-bottom: 1.25rem;
    }

    .form-field label {
        font-size: .82rem;
        font-weight: 750;
    }

    .form-field input,
    .form-field textarea,
    .form-field select {
        width: 100%;
        padding: .85rem 1rem;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--paper);
        color: var(--text);
        font: inherit;
        font-size: .85rem;
    }

    .form-field textarea {
        resize: vertical;
    }

    .form-field small {
        color: var(--muted);
        font-size: .72rem;
    }

    .image-adjustments {
        min-width: 0;
        margin: 0;
        padding: .85rem 1rem 1rem;
        border: 1px solid var(--line);
        border-radius: 10px;
    }

    .image-adjustments legend {
        padding: 0 .35rem;
        font-size: .78rem;
        font-weight: 750;
    }

    .image-adjustment-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .75rem;
    }

    .image-adjustment-grid label {
        display: flex;
        flex-direction: column;
        gap: .35rem;
        color: var(--muted);
        font-size: .68rem;
        font-weight: 750;
    }

    .image-adjustment-grid input[type="range"] {
        width: 100%;
        padding: 0;
    }

    .reset-image-button {
        margin-top: .75rem;
        padding: .55rem .8rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--paper);
        color: var(--ink);
        font: inherit;
        font-size: .72rem;
        font-weight: 750;
        cursor: pointer;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .generate-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .generate-button {
        width: auto;
        min-height: 42px;
        padding-inline: 1.25rem;
        padding-block: 0.6rem;
        white-space: nowrap;
        border-radius: 999px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        position: relative;
        overflow: visible;
    }

    .generate-button__main {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .generate-button small {
        font-size: 0.68rem;
        font-weight: 400;
        opacity: 0.85;
        white-space: normal;
        text-align: center;
        line-height: 1.2;
    }

    .generate-tag {
        position: absolute;
        top: -10px;
        right: 12px;
        font-size: 0.62rem;
        font-weight: 700;
        background: #f472b6;
        color: #fff;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .generate-button svg {
        width: 18px;
        height: 18px;
    }

    .generate-button--primary {
        background: #7c3aed;
        color: #fff;
        border-color: #7c3aed;
    }

    .generate-button--ai {
        background: #0ea5e9;
        color: #fff;
        border-color: #0ea5e9;
    }

    .generate-button--api {
        background: #22c55e;
        color: #fff;
        border-color: #22c55e;
    }

    .generate-button svg {
        width: 18px;
        height: 18px;
    }

    @media (max-width: 600px) {
    .panel-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .panel-kicker svg {
        width: 0.7em;
        height: 0.7em;
        flex-shrink: 0;
        vertical-align: middle;
    }

    .panel-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .panel-kicker i[data-lucide] {
        width: 0.7em;
        height: 0.7em;
        flex-shrink: 0;
    }

    .description-counter {
        display: block;
        text-align: right;
        font-size: 0.72rem;
        color: var(--muted);
        margin-top: 0.25rem;
    }

    .generate-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .generate-actions .generate-button {
            width: 100%;
            justify-content: center;
        }
    }
        width: 100%;
        min-height: 48px;
        border: 0;
        border-radius: 999px;
        background: var(--violet);
        color: var(--contrast-bg);
        font: inherit;
        font-weight: 750;
        cursor: pointer;
    }

    .generate-button:hover {
        opacity: .9;
    }

    .card-result {
        display: grid;
        min-height: 480px;
        place-items: center;
        padding: 1rem;
        border: 1px dashed var(--line);
        border-radius: 14px;
        background:
            radial-gradient(circle at 50% 40%, rgba(124, 58, 237, 0.12), transparent 60%),
            var(--paper);
        position: relative;
        overflow: hidden;
    }

    .generated-card {
        --category-color: #8b5cf6;

        display: flex;
        width: min(240px, 100%);
        aspect-ratio: 15 / 23;
        flex-direction: column;
        gap: 3%;
        overflow: hidden;
        padding: 4%;
        border: 3px solid var(--category-color);
        border-radius: 12px;
        background: #17121f;
        color: #fff;
        box-shadow: 0 0 25px color-mix(in srgb, var(--category-color) 25%, transparent);
    }

    .generated-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .4rem;
    }

    .generated-card-header h3 {
        overflow: hidden;
        margin: 0;
        font-size: clamp(.8rem, 3vw, 1.1rem);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .generated-card-header span {
        color: var(--category-color);
        font-size: .55rem;
        font-weight: 900;
    }

    .generated-card-image {
        flex: 1;
        min-height: 0;
        overflow: hidden;
        border: 1px solid var(--category-color);
        border-radius: 6px;
    }

    .generated-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transform-origin: center;
        touch-action: none;
        user-select: none;
    }

    .generated-card-image img.is-api-image {
        object-fit: contain;
    }

    .generated-card-image img.is-adjustable {
        cursor: grab;
    }

    .generated-card-image img.is-dragging {
        cursor: grabbing;
        transition: none;
    }

    .card-loading {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        z-index: 9997;
    }

    .card-loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(168, 85, 247, 0.25);
        border-top-color: #a855f7;
        border-radius: 50%;
        box-shadow: 0 0 18px rgba(168, 85, 247, 0.6);
        animation: card-spin 0.8s linear infinite;
    }

    @keyframes card-spin {
        to { transform: rotate(360deg); }
    }

    .generated-card-description {
        display: flex;
        min-height: 20%;
        align-items: center;
        padding: 5%;
        border-radius: 5px;
        background: #28212f;
    }

    .generated-card-description p {
        margin: 0;
        font-size: clamp(.5rem, 1.5vw, .68rem);
        line-height: 1.5;
    }

    .generated-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .6rem;
    }

    .card-level-dots {
        color: #facc15;
        letter-spacing: .1em;
    }

    .result-information {
        display: flex;
        flex-direction: column;
        gap: .35rem;
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 10px;
        background: var(--paper);
    }

    .result-information span {
        color: var(--muted);
        font-size: .65rem;
        font-weight: 800;
    }

    .result-information strong {
        font-size: .8rem;
    }

    @media (max-width: 800px) {
        .generator-grid {
            grid-template-columns: 1fr;
        }

    .form-field label {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .form-field label svg {
        width: 12px !important;
        height: 12px !important;
        max-width: 12px !important;
        max-height: 12px !important;
        color: var(--violet);
        flex-shrink: 0;
        vertical-align: middle;
    }
        display: block;
        text-align: right;
        font-size: 0.72rem;
        color: var(--muted);
        margin-top: 0.25rem;
    }

    .card-result {
            min-height: 420px;
        }
    }

    @media (max-width: 480px) {
        .generator-panel,
        .result-panel {
            padding: 1rem;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .image-adjustment-grid {
            grid-template-columns: 1fr;
        }
    }

@endsection
@section('content')
<main class="card-generator-page" style="margin-left:2%;margin-right:2%;">
        <div class="generator-grid">

            {{-- IZQUIERDA: FORMULARIO --}}
            <section class="generator-panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-kicker"><i data-lucide="settings"></i> CONFIGURACIÓN</span>
                        <h2>Datos de la carta</h2>
                    </div>
                </div>

                <form id="card-form">
                    <div class="form-field">
                        <label for="card-name">
                            <i data-lucide="tag"></i> Nombre de la carta
                        </label>
                        <input
                            type="text"
                            id="card-name"
                            value="Dragón Astral"
                            placeholder="Nombre de la carta"
                        >
                    </div>

                    <div class="form-field">
                        <label for="card-description">
                            <i data-lucide="align-left"></i> Descripción
                        </label>
                        <textarea
                            id="card-description"
                            rows="4"
                            placeholder="Describe tu carta..."
                            maxlength="120"
                        >Una criatura ancestral que domina la energía de las estrellas. Su presencia altera el equilibrio del universo.</textarea>
                        <small id="description-counter" class="description-counter">0 / 120</small>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="card-category">
                                <i data-lucide="layers"></i> Categoría
                            </label>
                            <select id="card-category">
                                <option value="magic">Magia</option>
                                <option value="fire">Fuego</option>
                                <option value="water">Agua</option>
                                <option value="nature">Naturaleza</option>
                                <option value="dark">Oscuridad</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="card-level">
                                <i data-lucide="zap"></i> Nivel
                            </label>
                            <input
                                type="number"
                                id="card-level"
                                min="1"
                                max="10"
                                value="5"
                                oninput="this.value = this.value > 10 ? 10 : this.value < 1 && this.value !== '' ? 1 : this.value;"
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="card-image">
                            <i data-lucide="image"></i> Imagen de la carta
                        </label>
                        <input type="file" id="card-image" accept="image/*">
                    </div>

                <div class="generate-actions">
                    <button type="button" class="generate-button generate-button--primary">
                        <span class="generate-tag">★ Recomendado</span>
                        <span class="generate-button__main">
                            <i data-lucide="sparkles"></i>
                            Obtener carta
                        </span>
                        <small>Genera una carta<br>con los datos ingresados</small>
                    </button>
                    <button type="button" class="generate-button generate-button--ai">
                        <span class="generate-button__main">
                            <i data-lucide="bot"></i>
                            Generar con IA
                        </span>
                        <small>Crea contenido dinámico<br>con inteligencia artificial</small>
                    </button>
                    <button type="button" class="generate-button generate-button--api">
                        <span class="generate-button__main">
                            <i data-lucide="globe"></i>
                            Obtener desde API
                        </span>
                        <small>Obtén información desde<br>una fuente externa</small>
                    </button>
                </div>
                </form>
            </section>

            {{-- DERECHA: RESULTADO --}}
            <section class="result-panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-kicker"><i data-lucide="bar-chart-3"></i> RESULTADO</span>
                        <h2>Tu carta obtenida</h2>
                    </div>
                    <div class="result-actions">
                        <select
                            id="collection-select"
                            class="collection-select"
                            aria-label="Colección destino"
                            disabled
                        >
                            <option value="">Sin colecciones</option>
                        </select>
                        <button
                            type="button"
                            id="add-to-collection"
                            class="add-to-collection-button"
                            disabled
                        >
                            Añadir a mi colección
                        </button>
                        <p id="collection-action-status" class="collection-action-status" aria-live="polite"></p>
                    </div>
                </div>

                <div class="card-result">
                    <div class="card-3d-stage">
                        <div class="generated-card card-3d">
                            <div class="generated-card-header">
                            <h3 id="preview-name">Dragón Astral</h3>
                            <span id="preview-category">MAGIA</span>
                        </div>

                        <div class="generated-card-image">
                            <img
                                id="preview-image"
                                src="{{ asset('images/cards/dragon-astral.webp') }}"
                                alt="Imagen de la carta"
                            >
                        </div>

                        <div class="generated-card-description">
                            <p id="preview-description">
                                Una criatura ancestral que domina la energía
                                de las estrellas. Su presencia altera el
                                equilibrio del universo.
                            </p>
                        </div>

                        <div class="generated-card-footer">
                            <span id="preview-number">#0042</span>

                            <div id="preview-level" class="card-level-dots">
                                ● ● ● ● ●
                            </div>
                        </div>
                    </div>
                </div>
<!--
                <div class="result-information">
                    <span>ESTADO DE LA CARTA</span>
                    <strong>Generada correctamente</strong>
                </div>
-->
            </section>

        </div>
    </main>

    <x-card-reveal />

    <div id="card-loading" class="card-loading" style="display:none;">
        <div class="card-loading-spinner"></div>
    </div>
@endsection
@push('scripts')
    @vite(['resources/js/cards/index.js'])
    
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) window.lucide.createIcons({ icons: window.lucide.icons });

    // Forzar tamaño de iconos en labels (Lucide setea 24x24 por defecto).
    document.querySelectorAll('.form-field label svg, .panel-kicker svg').forEach((svg) => {
        svg.setAttribute('width', '12');
        svg.setAttribute('height', '12');
        svg.style.width = '12px';
        svg.style.height = '12px';
    });

    const grid = document.querySelector('.generator-grid');
    if (grid) {
        const top = grid.getBoundingClientRect().top + window.scrollY - 20;
        window.scrollTo({ top, behavior: 'smooth' });
    }

    const form = document.getElementById('card-form');
    const generateButton = document.querySelector('.generate-button');
    const cardImageBaseUrl = @js(asset('images/cards'));
    const cardImageFallbackUrl = @js(asset('images/cards/dragon-astral.webp'));
    const POKEAPI_URL = 'https://pokeapi.co/api/v2';

    const categories = {
        magic: {
            label: 'MAGIA',
            color: '#8b5cf6'
        },
        fire: {
            label: 'FUEGO',
            color: '#ef4444'
        },
        water: {
            label: 'AGUA',
            color: '#0ea5e9'
        },
        nature: {
            label: 'NATURALEZA',
            color: '#22c55e'
        },
        dark: {
            label: 'OSCURIDAD',
            color: '#64748b'
        }
    };
    const pokemonCategories = {
        normal: 'NORMAL',
        fire: 'FUEGO',
        water: 'AGUA',
        electric: 'ELÉCTRICO',
        grass: 'PLANTA',
        ice: 'HIELO',
        fighting: 'LUCHA',
        poison: 'VENENO',
        ground: 'TIERRA',
        flying: 'VOLADOR',
        psychic: 'PSÍQUICO',
        bug: 'BICHO',
        rock: 'ROCA',
        ghost: 'FANTASMA',
        dragon: 'DRAGÓN',
        steel: 'ACERO',
        fairy: 'HADA'
    };
    const pokemonCategoryColors = {
        normal: '#a8a29e',
        fire: '#ef4444',
        water: '#0ea5e9',
        electric: '#eab308',
        grass: '#22c55e',
        ice: '#67e8f9',
        fighting: '#dc2626',
        poison: '#a855f7',
        ground: '#d97706',
        flying: '#818cf8',
        psychic: '#ec4899',
        bug: '#84cc16',
        rock: '#a16207',
        ghost: '#7c3aed',
        dragon: '#4f46e5',
        steel: '#94a3b8',
        fairy: '#f472b6'
    };

    const starwarsCategories = {
        jedi: {
            label: 'JEDI',
            color: '#0ea5e9'
        },
        sith: {
            label: 'SITH',
            color: '#ef4444'
        },
        bounty_hunter: {
            label: 'CAZARECOMPENSAS',
            color: '#22c55e'
        },
        imperial: {
            label: 'IMPERIAL',
            color: '#64748b'
        },
        rebel: {
            label: 'REBELDE',
            color: '#f59e0b'
        },
        droid: {
            label: 'DROIDE',
            color: '#eab308'
        }
    };
    const starwarsCategoryColors = {
        jedi: '#0ea5e9',
        sith: '#ef4444',
        bounty_hunter: '#22c55e',
        imperial: '#64748b',
        rebel: '#f59e0b',
        droid: '#eab308'
    };

    const DRAGONBALL_API_URL = 'https://dragonball-api.com/api';

    const dragonballCategories = {
        saiyan: {
            label: 'SAIYAN',
            color: '#eab308'
        },
        namekian: {
            label: 'NAMEKIANO',
            color: '#22c55e'
        },
        human: {
            label: 'HUMANO',
            color: '#0ea5e9'
        },
        majin: {
            label: 'MAJIN',
            color: '#f472b6'
        },
        frieza_race: {
            label: 'RAZA DE FREEZER',
            color: '#a855f7'
        },
        android: {
            label: 'ANDRÓIDE',
            color: '#64748b'
        },
        god: {
            label: 'DIOS',
            color: '#4f46e5'
        },
        angel: {
            label: 'ÁNGEL',
            color: '#67e8f9'
        },
        evil: {
            label: 'MALIGNO',
            color: '#dc2626'
        },
        nucleico: {
            label: 'NUCLÉICO',
            color: '#84cc16'
        },
        other: {
            label: 'OTRO',
            color: '#94a3b8'
        }
    };
    const dragonballCategoryColors = {
        saiyan: '#eab308',
        namekian: '#22c55e',
        human: '#0ea5e9',
        majin: '#f472b6',
        frieza_race: '#a855f7',
        android: '#64748b',
        god: '#4f46e5',
        angel: '#67e8f9',
        evil: '#dc2626',
        nucleico: '#84cc16',
        other: '#94a3b8'
    };

    const MINECRAFT_API_URL = 'https://api.mc-craft.com';

    const minecraftCategories = {
        passive: { label: 'PASIVO', color: '#22c55e' },
        neutral: { label: 'NEUTRAL', color: '#eab308' },
        hostile: { label: 'HOSTIL', color: '#ef4444' },
        boss: { label: 'JEFE', color: '#a855f7' }
    };
    const minecraftCategoryColors = {
        passive: '#22c55e',
        neutral: '#eab308',
        hostile: '#ef4444',
        boss: '#a855f7'
    };

    const randomCards = [
        {
            id: 'default-dragon-astral',
            name: 'Dragón Astral',
            description: 'Una criatura ancestral que domina la energía de las estrellas.',
            category: 'magic',
            image: 'dragon-astral.webp'
        },
        {
            id: 'default-phenix-carmesi',
            name: 'Fénix Carmesí',
            description: 'Renace de sus cenizas y transforma todo a su alrededor en llamas.',
            category: 'fire',
            image: 'fenix.webp'
        },
        {
            id: 'default-leviatan-azul',
            name: 'Leviatán Azul',
            description: 'Una bestia marina capaz de controlar las corrientes oceánicas.',
            category: 'water',
            image: 'leviathan.webp'
        },
        {
            id: 'default-guardian-bosque',
            name: 'Guardián del Bosque',
            description: 'Protector de la naturaleza que obtiene su fuerza de los árboles.',
            category: 'nature',
            image: 'guardian.webp'
        },
        {
            id: 'default-sombraeterna',
            name: 'Sombra Eterna',
            description: 'Una entidad misteriosa que habita entre las sombras.',
            category: 'dark',
            image: 'shadow.webp'
        }
    ];

    function categorizeStarWars(name) {
        const n = name.toLowerCase();
        if (n.includes('vader') || n.includes('sidious') || n.includes('emperor') || n.includes('sith')) return 'sith';
        if (n.includes('luke') || n.includes('yoda') || n.includes('skywalker') || n.includes('obi-wan') || n.includes('jedi')) return 'jedi';
        if (n.includes('fett') || n.includes('boba') || n.includes('assassin') || n.includes('hunter')) return 'bounty_hunter';
        if (n.includes('trooper') || n.includes('imperial') || n.includes('empire') || n.includes('storm') || n.includes('officer')) return 'imperial';
        if (n.includes('leia') || n.includes('organa') || n.includes('rebel') || n.includes('alliance')) return 'rebel';
        return 'droid';
    }

    const DRAGONBALL_RACE_CATEGORIES = {
        'saiyan': 'saiyan',
        'namekian': 'namekian',
        'human': 'human',
        'majin': 'majin',
        'frieza race': 'frieza_race',
        'android': 'android',
        'jiren race': 'other',
        'god': 'god',
        'angel': 'angel',
        'evil': 'evil',
        'nucleico': 'nucleico',
        'nucleico benigno': 'nucleico',
        'unknown': 'other'
    };

    function categorizeDragonBall(race) {
        const key = String(race ?? '').trim().toLowerCase();
        return DRAGONBALL_RACE_CATEGORIES[key] ?? 'other';
    }

    function categorizeMinecraft(type) {
        const key = String(type ?? '').trim().toLowerCase();
        return minecraftCategories[key] ? key : 'passive';
    }

    const elements = {
        name: document.getElementById('card-name'),
        description: document.getElementById('card-description'),
        category: document.getElementById('card-category'),
        level: document.getElementById('card-level'),
        image: document.getElementById('card-image'),

        previewName: document.getElementById('preview-name'),
        previewDescription: document.getElementById('preview-description'),
        previewCategory: document.getElementById('preview-category'),
        previewLevel: document.getElementById('preview-level'),
        previewImage: document.getElementById('preview-image'),
        imageAdjustments: document.getElementById('image-adjustments'),
        imageZoom: document.getElementById('image-zoom'),
        imageZoomValue: document.getElementById('image-zoom-value'),
        imageHorizontal: document.getElementById('image-horizontal'),
        imageVertical: document.getElementById('image-vertical'),
        resetImage: document.getElementById('reset-image'),
        pokemon: false,
        starwars: false,
        dragonball: false,
        minecraft: false,
        previewNumber: document.getElementById('preview-number'),
        cardLoading: document.getElementById('card-loading')
    };

    let previousIndex = -1;
    let usedStarWarsNumbers = new Set();
    let uploadedImageUrl = null;
    let cardBlobUrl = null;
    let dragStart = null;
    let imageLoadRequest = 0;
    let currentCard = {
        id: 'default-dragon-astral',
        type: 'default',
        name: 'Dragón Astral',
        description: 'Una criatura ancestral que domina la energía de las estrellas.',
        category: 'magic',
        level: 5,
        image: 'images/cards/dragon-astral.webp'
    };

    function dispatchCardReady() {
        document.dispatchEvent(new CustomEvent('infest:card-ready', {
            detail: { ...currentCard }
        }));
    }

    // Lanza la animación de revelación (overlay visual, no bloqueante).
    function playCardReveal() {
        if (!window.InfestCardReveal) return;
        const source = document.querySelector('.generated-card');
        window.InfestCardReveal.play({ card: { ...currentCard }, source });
    }

    function getRandomIndex() {
        let index;

        do {
            index = Math.floor(Math.random() * randomCards.length);
        } while (index === previousIndex && randomCards.length > 1);

        previousIndex = index;

        return index;
    }

    function clamp(value, minimum, maximum) {
        return Math.min(maximum, Math.max(minimum, value));
    }

    function getUniqueStarWarsNumber() {
        if (usedStarWarsNumbers.size >= 964) {
            usedStarWarsNumbers.clear();
        }
        let number;
        do {
            number = Math.floor(Math.random() * 964) + 1;
        } while (usedStarWarsNumbers.has(number));
        usedStarWarsNumbers.add(number);
        return number;
    }

    function applyImageAdjustment() {
        if (!elements.imageZoom) return;
        const zoom = Number(elements.imageZoom.value);
        const horizontal = Number(elements.imageHorizontal.value);
        const vertical = Number(elements.imageVertical.value);

        elements.imageZoomValue.textContent = `${zoom}%`;
        elements.previewImage.style.transform =
            `translate(${horizontal}px, ${vertical}px) scale(${zoom / 100})`;
    }

    function resetImageAdjustment() {
        if (!elements.imageZoom) return;
        elements.imageZoom.value = '100';
        elements.imageHorizontal.value = '0';
        elements.imageVertical.value = '0';
        applyImageAdjustment();
    }

    function preloadImage(url) {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.onload = () => resolve(image);
            image.onerror = () => reject(new Error('No se pudo cargar la imagen.'));
            image.src = url;
        });
    }

    function loadImageForCanvas(url) {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.crossOrigin = 'anonymous';
            image.onload = () => resolve(image);
            image.onerror = () => reject(new Error('No se pudo cargar la imagen para normalizar.'));
            image.src = url;
        });
    }

    async function normalizeCardImage(url, size = 512) {
        const image = await loadImageForCanvas(url);
        const canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        const context = canvas.getContext('2d');

        if (!context) {
            throw new Error('No se pudo preparar el lienzo de la imagen.');
        }

        const coverScale = Math.max(size / image.width, size / image.height);
        const backgroundWidth = image.width * coverScale;
        const backgroundHeight = image.height * coverScale;

        context.save();
        context.filter = 'blur(14px) brightness(0.5)';
        context.drawImage(
            image,
            (size - backgroundWidth) / 2,
            (size - backgroundHeight) / 2,
            backgroundWidth,
            backgroundHeight
        );
        context.restore();

        const containScale = Math.min(size / image.width, size / image.height);
        const foregroundWidth = image.width * containScale;
        const foregroundHeight = image.height * containScale;

        context.drawImage(
            image,
            (size - foregroundWidth) / 2,
            (size - foregroundHeight) / 2,
            foregroundWidth,
            foregroundHeight
        );

        return new Promise((resolve, reject) => {
            canvas.toBlob(
                (blob) => blob
                    ? resolve(blob)
                    : reject(new Error('No se pudo generar la imagen normalizada.')),
                'image/jpeg',
                0.85
            );
        });
    }

    async function setCardImage(url, onLoad) {
        const requestId = ++imageLoadRequest;

        try {
            await preloadImage(url);
            if (requestId === imageLoadRequest) {
                elements.previewImage.src = url;
            }
        } catch {
            await preloadImage(cardImageFallbackUrl);
            if (requestId === imageLoadRequest) {
                elements.previewImage.src = cardImageFallbackUrl;
            }
        } finally {
            if (typeof onLoad === 'function') {
                onLoad();
            }
        }
    }

    function updatePreview() {
        let category = categories[elements.category.value];
        if(elements.pokemon === true){
            const categoryKey = elements.category.value;
            category = {
                label: pokemonCategories[categoryKey] ?? categoryKey.toUpperCase(),
                color: pokemonCategoryColors[categoryKey] ?? '#8b5cf6'
            };
            elements.previewCategory.textContent = category.label;
        }
        else if(elements.starwars === true){
            const categoryKey = elements.category.value;
            category = {
                label: starwarsCategories[categoryKey]?.label ?? categoryKey.toUpperCase(),
                color: starwarsCategoryColors[categoryKey] ?? '#8b5cf6'
            };
            elements.previewCategory.textContent = category.label;
        }
        else if(elements.dragonball === true){
            const categoryKey = elements.category.value;
            category = {
                label: dragonballCategories[categoryKey]?.label ?? categoryKey.toUpperCase(),
                color: dragonballCategoryColors[categoryKey] ?? '#8b5cf6'
            };
            elements.previewCategory.textContent = category.label;
        }
        else if(elements.minecraft === true){
            const categoryKey = elements.category.value;
            category = {
                label: minecraftCategories[categoryKey]?.label ?? categoryKey.toUpperCase(),
                color: minecraftCategoryColors[categoryKey] ?? '#8b5cf6'
            };
            elements.previewCategory.textContent = category.label;
        }
        else{
            category = categories[elements.category.value];
            elements.previewCategory.textContent = category.label;
            elements.previewNumber.textContent = `#0052`;
        }
        elements.previewName.textContent = elements.name.value || 'Carta sin nombre';
        elements.previewDescription.textContent = elements.description.value || 'Sin descripción disponible.';

        const descriptionCounter = document.getElementById('description-counter');
        if (descriptionCounter) {
            descriptionCounter.textContent = `${(elements.description.value || '').length} / 120`;
        }

        elements.previewLevel.textContent = Array.from(
            { length: Math.min(50, Math.max(1, Number(elements.level.value) || 1)) },
            () => '●'
        ).join(' ');

        document.querySelector('.generated-card').style.setProperty(
            '--category-color',
            category.color
        );

        currentCard = {
            ...currentCard,
            name: elements.name.value || 'Carta sin nombre',
            description: elements.description.value || 'Sin descripción disponible.',
            category: elements.category.value,
            level: Math.min(50, Math.max(1, Number(elements.level.value) || 1))
        };
        dispatchCardReady();
    }
    async function translateToSpanish(text, maxLength = 500) {
        const source = String(text ?? '').trim();
        if (!source) return text;

        const query = source.length > maxLength ? source.slice(0, maxLength) : source;

        try {
            const response = await fetch(
                `https://api.mymemory.translated.net/get?q=${encodeURIComponent(query)}&langpair=en|es`
            );
            const data = await response.json();
            return data?.responseData?.translatedText || text;
        } catch {
            return text;
        }
    }
    async function getRandomPokemon() {
        // Pokédex nacional: primera generación hasta la novena
        const pokemonId = Math.floor(Math.random() * 1025) + 1;

        // Consultar Pokémon y su especie en paralelo
        const [pokemonResponse, speciesResponse] = await Promise.all([
            fetch(`${POKEAPI_URL}/pokemon/${pokemonId}`),
            fetch(`${POKEAPI_URL}/pokemon-species/${pokemonId}`)
        ]);

        if (!pokemonResponse.ok || !speciesResponse.ok) {
            throw new Error('No se pudo obtener el Pokémon');
        }

        const pokemon = await pokemonResponse.json();
        const species = await speciesResponse.json();
        // Descripción en español o inglés si no hay disponible
        const descriptionEntry = species.flavor_text_entries.find(
            entry => entry.language.name === 'es'
        ) || species.flavor_text_entries.find(
            entry => entry.language.name === 'en'
        );

        let description = descriptionEntry?.flavor_text ?? 'Descripción no disponible';

        // Traducir al español si viene en inglés
        if (descriptionEntry?.language.name === 'en') {
            description = await translateToSpanish(description);
        }
        // Calcular rareza
        let level;

        if (species.is_mythical) {
            level = 10;
        } else if (species.is_legendary) {
            level = 9;
        } else if (species.is_baby) {
            level = 1;
        } else {
            const rate = species.capture_rate;

            if (rate <= 30) level = 8;
            else if (rate <= 60) level = 7;
            else if (rate <= 100) level = 6;
            else if (rate <= 150) level = 5;
            else if (rate <= 200) level = 4;
            else if (rate <= 254) level = 3;
            else level = 2;
        }

        return {
            id: pokemon.id,
            name: pokemon.name,
            description: String(description)
                .replace(/[\n\f]/g, ' ') || 'Sin descripción disponible.',
            category: pokemon.types[0].type.name,
            level,
            image: pokemon.sprites.other?.['official-artwork']?.front_default
                || pokemon.sprites.front_default
        };
    }
    async function getRandomStarWars() {
        try {
            // Reintento por si el servicio (Render free) está despertando (503).
            let response;
            for (let attempt = 0; attempt < 3; attempt += 1) {
                response = await fetch('https://starwars-databank-server.onrender.com/api/v1/characters?page=1&limit=100');
                if (response.ok) break;
                if (attempt < 2) await new Promise(r => setTimeout(r, 1500));
            }
            if (!response.ok) throw new Error('No se pudo obtener personajes de Star Wars');
            const data = await response.json();
            const characters = data.data;
            if (!characters || characters.length === 0) throw new Error('Sin personajes disponibles');

            const char = characters[Math.floor(Math.random() * characters.length)];
            const category = categorizeStarWars(char.name);
            const level = Math.floor(Math.random() * 10) + 1;

            let description = char.description || `Personaje de Star Wars ${char.name}`;

            description = await translateToSpanish(description);

            if (description.length > 105) {
                description = description.slice(0, 105).trimEnd() + '...';
            }

            let image = char.image;
            try {
                image = await normalizeCardImage(char.image);
            } catch {
                // Mantener la URL original si falla la normalización.
            }

            return {
                id: `sw-${char._id}`,
                externalId: char._id,
                name: char.name,
                description,
                category,
                level,
                image
            };
        } catch {
            const fallback = {
                id: 'sw-fallback',
                name: 'Personaje Desconocido',
                description: 'Un habitante de la galaxia muy, muy lejana.',
                category: 'droid',
                level: 1,
                image: cardImageFallbackUrl
            };
            return fallback;
        }
    }
    async function getRandomDragonBall() {
        try {
            const response = await fetch(`${DRAGONBALL_API_URL}/characters?limit=100`);
            if (!response.ok) throw new Error('No se pudo obtener personajes de Dragon Ball');
            const data = await response.json();
            const characters = data.items;
            if (!characters || characters.length === 0) throw new Error('Sin personajes disponibles');

            const character = characters[Math.floor(Math.random() * characters.length)];
            const category = categorizeDragonBall(character.race);

            let description = character.description || `Personaje de Dragon Ball ${character.name}`;

            if (description.length > 105) {
                description = description.slice(0, 105).trimEnd() + '...';
            }

            return {
                id: `db-${character.id}`,
                externalId: character.id,
                name: character.name,
                description,
                category,
                level: Math.floor(Math.random() * 10) + 1,
                image: character.image
            };
        } catch {
            const fallback = {
                id: 'db-fallback',
                name: 'Personaje Desconocido',
                description: 'Un guerrero del universo Dragon Ball.',
                category: 'other',
                level: 1,
                image: cardImageFallbackUrl
            };
            return fallback;
        }
    }
    async function getRandomMinecraft() {
        try {
            const response = await fetch(`${MINECRAFT_API_URL}/mobs/en`);
            if (!response.ok) throw new Error('No se pudo obtener mobs de Minecraft');
            const data = await response.json();
            const mobs = data.mobs;
            if (!mobs || mobs.length === 0) throw new Error('Sin mobs disponibles');

            const mob = mobs[Math.floor(Math.random() * mobs.length)];
            const category = categorizeMinecraft(mob.type);

            let description = mob.description || `Mob de Minecraft ${mob.name}`;

            description = await translateToSpanish(description);

            if (description.length > 105) {
                description = description.slice(0, 105).trimEnd() + '...';
            }

            // La imagen se obtiene desde bisai (CORS habilitado); el icono de
            // MC-Craft bloquea la carga cross-origin con CORP same-origin.
            const imageSlug = String(mob.id ?? mob.name ?? '')
                .replace(/^minecraft:/, '')
                .trim()
                .toLowerCase()
                .replace(/\s+/g, '_');

            const cardSlug = String(mob.name ?? mob.id ?? '')
                .trim()
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9-]/g, '');

            return {
                id: `mc-${cardSlug || imageSlug}`,
                externalId: mob.id,
                name: mob.name,
                description,
                category,
                level: Math.floor(Math.random() * 10) + 1,
                image: `https://mc-api.bisai.dev/v1/mobs/${imageSlug}/image.png`
            };
        } catch {
            const fallback = {
                id: 'mc-fallback',
                name: 'Mob Desconocido',
                description: 'Un habitante del mundo de Minecraft.',
                category: 'passive',
                level: 1,
                image: cardImageFallbackUrl
            };
            return fallback;
        }
    }
    function setCategories(categories) {
        elements.category.innerHTML = Object.entries(categories)
            .map(([key, value]) => `
                <option value="${key}">
                    ${typeof value === 'string' ? value : value.label}
                </option>
            `)
            .join('');
    }
    
    generateButton.addEventListener('click', async () => {
        const normalizedName = elements.name.value.trim().toLowerCase();
        const isPokemon = normalizedName === 'pokemon';
        const isStarWars = normalizedName === 'starwars';
        const isDragonBall = normalizedName === 'dragonball';
        const isMinecraft = normalizedName === 'minecraft';

        if ((isPokemon && !apiStatus.pokemon) || (isStarWars && !apiStatus.starwars)
            || (isDragonBall && !apiStatus.dragonball) || (isMinecraft && !apiStatus.minecraft)) {
            window.Swal?.fire({
                toast: true, position: 'top-end', icon: 'error',
                title: 'API con problemas',
                text: 'Esta API está con problemas, vuelve a intentarlo más tarde.',
                showConfirmButton: false, timer: 4000, background: '#18181b', color: '#f4f4f5'
            });
            return;
        }

        elements.cardLoading.style.display = 'flex';
        try {
            let card;
            let type;
            let categoryMap;
            let categoryColors;
            let imageBase;

            if (isPokemon) {
                card = await getRandomPokemon();
                type = 'pokemon';
                categoryMap = pokemonCategories;
                categoryColors = pokemonCategoryColors;
                imageBase = null;
            } else if (isStarWars) {
                card = await getRandomStarWars();
                type = 'starwars';
                categoryMap = starwarsCategories;
                categoryColors = starwarsCategoryColors;
                imageBase = null;
            } else if (isDragonBall) {
                card = await getRandomDragonBall();
                type = 'dragonball';
                categoryMap = dragonballCategories;
                categoryColors = dragonballCategoryColors;
                imageBase = null;
            } else if (isMinecraft) {
                card = await getRandomMinecraft();
                type = 'minecraft';
                categoryMap = minecraftCategories;
                categoryColors = minecraftCategoryColors;
                imageBase = null;
            } else {
                card = randomCards[getRandomIndex()];
                type = 'default';
                categoryMap = categories;
                categoryColors = null;
                imageBase = cardImageBaseUrl;
            }

            const usesExternalApi = isPokemon || isStarWars || isDragonBall || isMinecraft;

            setCategories(categoryMap);
            elements.level.value = usesExternalApi ? card.level : Math.floor(Math.random() * 10) + 1;

            if (card.image instanceof Blob) {
                if (cardBlobUrl) {
                    URL.revokeObjectURL(cardBlobUrl);
                }
                cardBlobUrl = URL.createObjectURL(card.image);
                await setCardImage(cardBlobUrl, () => {
                    elements.cardLoading.style.display = 'none';
                });
            } else {
                await setCardImage(
                    usesExternalApi ? card.image : `${cardImageBaseUrl}/${encodeURIComponent(card.image)}`,
                    () => {
                        elements.cardLoading.style.display = 'none';
                    }
                );
            }

            currentCard = isPokemon ? {
                id: `pokemon-${card.id}`,
                externalId: card.id,
                type: 'pokemon',
                name: card.name,
                description: card.description,
                category: card.category,
                level: card.level,
                image: card.image
            } : isStarWars ? {
                id: card.id,
                externalId: card.id,
                type: 'starwars',
                name: card.name,
                description: card.description,
                category: card.category,
                level: card.level,
                image: card.image
            } : isDragonBall ? {
                id: card.id,
                externalId: card.id,
                type: 'dragonball',
                name: card.name,
                description: card.description,
                category: card.category,
                level: card.level,
                image: card.image
            } : isMinecraft ? {
                id: card.id,
                externalId: card.id,
                type: 'minecraft',
                name: card.name,
                description: card.description,
                category: card.category,
                level: card.level,
                image: card.image
            } : {
                id: card.id,
                type: 'default',
                name: card.name,
                description: card.description,
                category: card.category,
                level: Number(elements.level.value),
                image: `images/cards/${card.image}`
            };
            elements.pokemon = isPokemon;
            elements.starwars = isStarWars;
            elements.dragonball = isDragonBall;
            elements.minecraft = isMinecraft;
            elements.name.value = card.name;
            elements.description.value = card.description;
            elements.category.value = card.category;
            elements.previewNumber.textContent = isPokemon
                ? `#${String(card.id).padStart(4, '0')}`
                : isDragonBall
                    ? `#${String(card.id).replace('db-', '').padStart(4, '0')}`
                    : isStarWars
                        ? `#${getUniqueStarWarsNumber().toString().padStart(4, '0')}`
                        : isMinecraft
                            ? `#${String(Math.floor(Math.random() * 9000) + 1000)}`
                            : `#0052`;

            if (elements.imageAdjustments) elements.imageAdjustments.disabled = true;
            elements.previewImage.classList.remove('is-adjustable');
            elements.previewImage.classList.toggle(
                'is-api-image',
                usesExternalApi && !(card.image instanceof Blob)
            );
            if (isPokemon) {
                notifyAchievement('pokemon_collected');
            } else if (isStarWars) {
                notifyAchievement('starwars_collected');
            } else if (isDragonBall) {
                notifyAchievement('dragonball_collected');
            } else if (isMinecraft) {
                notifyAchievement('minecraft_collected');
            }
            resetImageAdjustment();
    // Efecto 3D interactivo en «Tu carta obtenida».
    function tryAttachTilt() {
        const card3d = document.querySelector('.generated-card.card-3d');
        if (card3d && window.InfestCardTilt) {
            window.InfestCardTilt.attach(card3d);
        } else {
            setTimeout(tryAttachTilt, 50);
        }
    }
    tryAttachTilt();

    updatePreview();
            playCardReveal();

        } catch (error) {
            console.error(error);
        }
    });

    // Actualizar la carta al modificar los campos manualmente.
    elements.name.addEventListener('input', updatePreview);
    elements.description.addEventListener('input', updatePreview);
    elements.category.addEventListener('change', updatePreview);
    elements.level.addEventListener('input', updatePreview);
    if (elements.imageZoom) elements.imageZoom.addEventListener('input', applyImageAdjustment);
    if (elements.imageHorizontal) elements.imageHorizontal.addEventListener('input', applyImageAdjustment);
    if (elements.imageVertical) elements.imageVertical.addEventListener('input', applyImageAdjustment);
    if (elements.resetImage) elements.resetImage.addEventListener('click', resetImageAdjustment);

    // Permitir seleccionar una imagen local.
    elements.image.addEventListener('change', () => {
        const file = elements.image.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Selecciona un archivo de imagen válido.');
            elements.image.value = '';
            return;
        }

        if (uploadedImageUrl) {
            URL.revokeObjectURL(uploadedImageUrl);
        }

        ++imageLoadRequest;
        if (cardBlobUrl) {
            URL.revokeObjectURL(cardBlobUrl);
            cardBlobUrl = null;
        }
        uploadedImageUrl = URL.createObjectURL(file);
        elements.previewImage.src = uploadedImageUrl;
        if (elements.imageAdjustments) elements.imageAdjustments.disabled = false;
        elements.previewImage.classList.add('is-adjustable');
        elements.previewImage.classList.remove('is-api-image');
        resetImageAdjustment();
        currentCard = {
            id: crypto.randomUUID(),
            type: 'personal',
            name: elements.name.value || 'Carta personal',
            description: elements.description.value || '',
            category: elements.category.value,
            level: Number(elements.level.value) || 1,
            image: file
        };
        dispatchCardReady();
    });

    elements.previewImage.addEventListener('pointerdown', (event) => {
        if (elements.imageAdjustments.disabled) {
            return;
        }

        dragStart = {
            pointerId: event.pointerId,
            startX: event.clientX,
            startY: event.clientY,
            horizontal: Number(elements.imageHorizontal.value),
            vertical: Number(elements.imageVertical.value)
        };

        elements.previewImage.classList.add('is-dragging');
        elements.previewImage.setPointerCapture(event.pointerId);
        event.preventDefault();
    });

    elements.previewImage.addEventListener('pointermove', (event) => {
        if (!dragStart || dragStart.pointerId !== event.pointerId) {
            return;
        }

        const horizontal = clamp(dragStart.horizontal + event.clientX - dragStart.startX, -100, 100);
        const vertical = clamp(dragStart.vertical + event.clientY - dragStart.startY, -100, 100);

        elements.imageHorizontal.value = String(Math.round(horizontal));
        elements.imageVertical.value = String(Math.round(vertical));
        applyImageAdjustment();
    });

    const finishImageDrag = (event) => {
        if (!dragStart || dragStart.pointerId !== event.pointerId) {
            return;
        }

        elements.previewImage.classList.remove('is-dragging');

        if (elements.previewImage.hasPointerCapture(event.pointerId)) {
            elements.previewImage.releasePointerCapture(event.pointerId);
        }

        dragStart = null;
    };

    elements.previewImage.addEventListener('pointerup', finishImageDrag);
    elements.previewImage.addEventListener('pointercancel', finishImageDrag);

    // Botón IA aún no funcional (placeholder).
    document.querySelectorAll('.generate-button--ai').forEach((button) => {
        button.addEventListener('click', () => {
            if (window.Swal) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Próximamente',
                    text: 'Esta función aún no está disponible.',
                    showConfirmButton: false,
                    timer: 3000,
                    background: '#18181b',
                    color: '#f4f4f5'
                });
            } else {
                alert('Esta función aún no está disponible.');
            }
        });
    });

    let lastApiSelection = [];

    const apiStatus = { pokemon: true, starwars: true, dragonball: true, minecraft: true };
    const apiCheckEndpoints = {
        pokemon: 'https://pokeapi.co/api/v2/pokemon/1',
        starwars: 'https://starwars-databank-server.onrender.com/api/v1/characters?page=1&limit=1',
        dragonball: 'https://dragonball-api.com/api/characters?limit=1',
        minecraft: 'https://api.mc-craft.com/mobs/en',
    };

    async function checkApis() {
        await Promise.all(Object.entries(apiCheckEndpoints).map(async ([key, url]) => {
            try {
                const controller = new AbortController();
                const timer = setTimeout(() => controller.abort(), 8000);
                const res = await fetch(url, { signal: controller.signal });
                clearTimeout(timer);
                apiStatus[key] = res.ok;
            } catch {
                apiStatus[key] = false;
            }
        }));
    }
    checkApis();

    // Botón "Obtener desde API": modal para elegir APIs y traer una imagen.
    document.querySelectorAll('.generate-button--api').forEach((button) => {
        button.addEventListener('click', async () => {
            const apiOptions = [
                { value: 'pokemon', label: 'Pokémon', icon: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><circle cx="12" cy="12" r="3" fill="#ef4444"/></svg>' },
                { value: 'starwars', label: 'Star Wars', icon: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#facc15" stroke-width="2"><path d="M12 2l2.4 7.4H22l-5.8 4.2 2.2 7.4L12 16.8 5.6 21l2.2-7.4L2 9.4h7.6z"/></svg>' },
                { value: 'dragonball', label: 'Dragon Ball', icon: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#f97316" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="9" r="1" fill="#f97316"/><circle cx="9" cy="13" r="1" fill="#f97316"/><circle cx="15" cy="13" r="1" fill="#f97316"/><circle cx="12" cy="15" r="1" fill="#f97316"/></svg>' },
                { value: 'minecraft', label: 'Minecraft', icon: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#22c55e" stroke-width="2"><path d="M12 2l9 5v10l-9 5-9-5V7z"/><path d="M12 2v10M3 7l9 5 9-5"/></svg>' },
            ];

            const result = await window.Swal.fire({
                title: 'Selecciona APIs',
                html: `<style>
                    .api-card{position:relative;display:flex;align-items:center;gap:.5rem;padding:.6rem .7rem;border:1px solid #393440;border-radius:10px;cursor:pointer;background:#211d27;transition:border-color .15s,background .15s}
                    .api-card:hover{border-color:#6c28d9}
                    .api-card:has(input:checked){border-color:#a855f7;background:#2a1f45;box-shadow:0 0 12px rgba(168,85,247,.4)}
                    .api-card input{position:absolute;opacity:0;pointer-events:none}
                    .api-card .api-checkmark{margin-left:auto;font-size:.9rem;color:#a855f7;opacity:0}
                    .api-card:has(input:checked) .api-checkmark{opacity:1}
                </style>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.6rem;text-align:left">${apiOptions
                    .map(o => {
                        const down = apiStatus[o.value] === false;
                        return `<label class="api-card${down ? ' api-card--down' : ''}" ${down ? 'style="opacity:.5;cursor:not-allowed"' : ''}>${o.icon}<input type="checkbox" class="api-check" value="${o.value}" ${down ? 'disabled' : ''} ${lastApiSelection.includes(o.value) && !down ? 'checked' : ''}> <span style="font-size:.9rem">${o.label}${down ? ' <small style="color:#ef4444">(no disponible)</small>' : ''}</span><span class="api-checkmark">✓</span></label>`;
                    }).join('')}</div>`,
                confirmButtonText: '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-3px;margin-right:6px"><path d="M5 12h14M13 6l6 6-6 6"/></svg> Obtener',
                cancelButtonText: '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-3px;margin-right:6px"><path d="M18 6L6 18M6 6l12 12"/></svg> Cancelar',
                showCancelButton: true,
                background: '#18181b',
                color: '#f4f4f5',
                preConfirm: () => {
                    const checked = [...document.querySelectorAll('.api-check:checked')].map(c => c.value);
                    if (!checked.length) {
                        window.Swal.showValidationMessage('Selecciona al menos una API');
                        return false;
                    }
                    return checked;
                },
            });

            if (!result.isConfirmed || !result.value) return;

            lastApiSelection = result.value;
            const type = lastApiSelection[Math.floor(Math.random() * lastApiSelection.length)];

            elements.name.value = type;
            updatePreview();
            generateButton.click();
        });
    });

    // Evitar el envío y la recarga del formulario.
    form.addEventListener('submit', event => {
        event.preventDefault();
    });

    function tryAttachTilt() {
        const card3d = document.querySelector('.generated-card.card-3d');
        if (card3d && window.InfestCardTilt) {
            window.InfestCardTilt.attach(card3d);
        } else {
            setTimeout(tryAttachTilt, 50);
        }
    }
    tryAttachTilt();

    updatePreview();
});
</script>

@endpush

@push('head')
    @vite('resources/js/album/cardToCollection.js')
@endpush