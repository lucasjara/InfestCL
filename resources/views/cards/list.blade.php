@extends('layouts.base')

@section('title', 'Carta random | INFEST Chile')

@section('meta_description', 'Visualización de la carta random.')


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
        padding-block: clamp(1.5rem, 5vw, 3rem);
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

    .panel-heading .generate-button {
        width: auto;
        min-height: 42px;
        padding-inline: 1.25rem;
        white-space: nowrap;
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

    .generate-button {
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
        background: var(--paper);
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
        box-shadow: 0 0 25px rgb(139 92 246 / 25%);
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
        transform-origin: center;
        touch-action: none;
        user-select: none;
    }

    .generated-card-image img.is-adjustable {
        cursor: grab;
    }

    .generated-card-image img.is-dragging {
        cursor: grabbing;
        transition: none;
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
<main class="card-generator-page">
        <div class="generator-heading">
            <div>
                <p class="generator-kicker">INFEST · CARD GENERATOR</p>
                <h1>Generador de cartas</h1>
                <p class="generator-description">
                    Completa los datos y descubre la carta que has creado.
                </p>
            </div>
        </div>

        <div class="generator-grid">

            {{-- IZQUIERDA: FORMULARIO --}}
            <section class="generator-panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-kicker">CONFIGURACIÓN</span>
                        <h2>Datos de la carta</h2>
                    </div>
                </div>

                <form id="card-form">
                    <div class="form-field">
                        <label for="card-name">Nombre de la carta</label>
                        <input
                            type="text"
                            id="card-name"
                            value="Dragón Astral"
                            placeholder="Nombre de la carta"
                        >
                    </div>

                    <div class="form-field">
                        <label for="card-description">Descripción</label>
                        <textarea
                            id="card-description"
                            rows="4"
                            placeholder="Describe tu carta..."
                            maxlength="120"
                        >Una criatura ancestral que domina la energía de las estrellas. Su presencia altera el equilibrio del universo.</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="card-category">Categoría</label>
                            <select id="card-category">
                                <option value="magic">Magia</option>
                                <option value="fire">Fuego</option>
                                <option value="water">Agua</option>
                                <option value="nature">Naturaleza</option>
                                <option value="dark">Oscuridad</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="card-level">Nivel</label>
                            <input
                                type="number"
                                id="card-level"
                                min="1"
                                max="10"
                                value="5"
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="card-image">Imagen de la carta</label>
                        <input type="file" id="card-image" accept="image/*">
                        <small>Selecciona una imagen para tu carta.</small>
                    </div>

                    <fieldset class="image-adjustments" id="image-adjustments" disabled>
                        <legend>Ajustes de imagen</legend>
                        <div class="image-adjustment-grid">
                            <label for="image-zoom">
                                Zoom <output id="image-zoom-value">100%</output>
                                <input type="range" id="image-zoom" min="100" max="300" value="100" step="1">
                            </label>
                            <label for="image-horizontal">
                                Horizontal
                                <input type="range" id="image-horizontal" min="-100" max="100" value="0" step="1">
                            </label>
                            <label for="image-vertical">
                                Vertical
                                <input type="range" id="image-vertical" min="-100" max="100" value="0" step="1">
                            </label>
                        </div>
                        <button type="button" class="reset-image-button" id="reset-image">
                            Restablecer imagen
                        </button>
                    </fieldset>
                </form>
            </section>

            {{-- DERECHA: RESULTADO --}}
            <section class="result-panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-kicker">RESULTADO</span>
                        <h2>Tu carta obtenida</h2>
                    </div>
                    <div>
                        <button type="button" class="generate-button">
                            Obtener carta
                        </button>
                    </div> 
                </div>

                <div class="card-result">
                    <div class="generated-card">
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
                            <span>#0042</span>

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
@endsection
@push('scripts')
    
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('card-form');
    const generateButton = document.querySelector('.generate-button');
    const cardImageBaseUrl = @js(asset('images/cards'));
    const cardImageFallbackUrl = @js(asset('images/cards/dragon-astral.webp'));

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

    const randomCards = [
        {
            name: 'Dragón Astral',
            description: 'Una criatura ancestral que domina la energía de las estrellas.',
            category: 'magic',
            image: 'dragon-astral.webp'
        },
        {
            name: 'Fénix Carmesí',
            description: 'Renace de sus cenizas y transforma todo a su alrededor en llamas.',
            category: 'fire',
            image: 'fenix.webp'
        },
        {
            name: 'Leviatán Azul',
            description: 'Una bestia marina capaz de controlar las corrientes oceánicas.',
            category: 'water',
            image: 'leviathan.webp'
        },
        {
            name: 'Guardián del Bosque',
            description: 'Protector de la naturaleza que obtiene su fuerza de los árboles.',
            category: 'nature',
            image: 'guardian.webp'
        },
        {
            name: 'Sombra Eterna',
            description: 'Una entidad misteriosa que habita entre las sombras.',
            category: 'dark',
            image: 'shadow.webp'
        }
    ];

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
        resetImage: document.getElementById('reset-image')
    };

    let previousIndex = -1;
    let uploadedImageUrl = null;
    let dragStart = null;
    let imageLoadRequest = 0;

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

    function applyImageAdjustment() {
        const zoom = Number(elements.imageZoom.value);
        const horizontal = Number(elements.imageHorizontal.value);
        const vertical = Number(elements.imageVertical.value);

        elements.imageZoomValue.textContent = `${zoom}%`;
        elements.previewImage.style.transform =
            `translate(${horizontal}px, ${vertical}px) scale(${zoom / 100})`;
    }

    function resetImageAdjustment() {
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

    async function setCardImage(url) {
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
        }
    }

    function updatePreview() {
        const category = categories[elements.category.value];

        elements.previewName.textContent = elements.name.value || 'Carta sin nombre';
        elements.previewDescription.textContent =
            elements.description.value || 'Sin descripción disponible.';

        elements.previewCategory.textContent = category.label;

        elements.previewLevel.textContent = Array.from(
            { length: Math.min(50, Math.max(1, Number(elements.level.value) || 1)) },
            () => '●'
        ).join(' ');

        document.querySelector('.generated-card').style.setProperty(
            '--category-color',
            category.color
        );
    }

    generateButton.addEventListener('click', () => {
     
        const card = randomCards[getRandomIndex()];

        elements.name.value = card.name;
        elements.description.value = card.description;
        elements.category.value = card.category;
        elements.level.value = Math.floor(Math.random() * 10) + 1;

        elements.imageAdjustments.disabled = true;
        elements.previewImage.classList.remove('is-adjustable');
        resetImageAdjustment();
        setCardImage(`${cardImageBaseUrl}/${encodeURIComponent(card.image)}`);

        updatePreview();
    });

    // Actualizar la carta al modificar los campos manualmente.
    elements.name.addEventListener('input', updatePreview);
    elements.description.addEventListener('input', updatePreview);
    elements.category.addEventListener('change', updatePreview);
    elements.level.addEventListener('input', updatePreview);
    elements.imageZoom.addEventListener('input', applyImageAdjustment);
    elements.imageHorizontal.addEventListener('input', applyImageAdjustment);
    elements.imageVertical.addEventListener('input', applyImageAdjustment);
    elements.resetImage.addEventListener('click', resetImageAdjustment);

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
        uploadedImageUrl = URL.createObjectURL(file);
        elements.previewImage.src = uploadedImageUrl;
        elements.imageAdjustments.disabled = false;
        elements.previewImage.classList.add('is-adjustable');
        resetImageAdjustment();
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

    // Evitar el envío y la recarga del formulario.
    form.addEventListener('submit', event => {
        event.preventDefault();
    });

    updatePreview();
});
</script>

@endpush