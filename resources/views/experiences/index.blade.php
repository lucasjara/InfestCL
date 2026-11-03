@extends('layouts.base')

@section('title', 'Experiencias | INFEST Chile')

@section('meta_description', 'Explora las experiencias de colecciones, torneos y creación de cartas de INFEST Chile.')

@section('navigation')
    <a href="{{ route('album.index') }}">Colecciones</a>
    <a href="{{ route('tournaments.create') }}">Torneos</a>
    <a href="{{ route('cards.list') }}">Cartas</a>
@endsection

@section('styles')
    .experiences-page {
        padding-block: clamp(1.5rem, 5vw, 3rem);
    }

    .experiences-heading {
        max-width: 760px;
        margin-bottom: 2rem;
    }

    .experiences-heading p {
        margin: 0 0 .45rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .experiences-heading h1 {
        margin: 0;
        font-size: clamp(2.25rem, 7vw, 4.75rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .experience-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .experience-card {
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 20px;
        background: var(--surface);
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .experience-card:hover {
        transform: translateY(-4px);
        border-color: var(--violet);
        box-shadow: 0 18px 40px rgb(23 21 27 / 10%);
    }

    .experience-card-link {
        display: block;
        height: 100%;
        text-decoration: none;
    }

    .experience-card-image {
        aspect-ratio: 16 / 10;
        overflow: hidden;
        border-bottom: 1px solid var(--line);
        background: var(--facets);
    }

    .experience-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .3s ease;
    }

    .experience-card:hover img {
        transform: scale(1.03);
    }

    .experience-card-content {
        padding: 1.25rem;
    }

    .experience-card-content h2 {
        margin: 0 0 .55rem;
        font-size: 1.25rem;
        letter-spacing: -.03em;
    }

    .experience-card-content p {
        margin: 0 0 1rem;
        color: var(--muted);
        line-height: 1.55;
    }

    .experience-card-action {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        color: var(--violet);
        font-size: .82rem;
        font-weight: 800;
    }

    @media (max-width: 900px) {
        .experience-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .experience-grid {
            grid-template-columns: 1fr;
        }
    }
@endsection

@section('content')
    <div class="shell experiences-page">
        <header class="experiences-heading">
            <p>INFEST Chile</p>
            <h1>Experiencias</h1>
        </header>

        <div class="experience-grid">

            <article class="experience-card">
                <a class="experience-card-link" href="{{ route('tournaments.create') }}">
                    <div class="experience-card-image">
                        <img src="{{ asset('images/experiences/infest_tournament.png') }}" alt="Torneo INFEST">
                    </div>
                    <div class="experience-card-content">
                        <h2>Torneos</h2>
                        <p>Crea torneos, configura participantes y administra el torneo.</p>
                        <span class="experience-card-action">Crear torneo <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </article>

            <article class="experience-card">
                <a class="experience-card-link" href="{{ route('cards.list') }}">
                    <div class="experience-card-image">
                        <img src="{{ asset('images/experiences/infest_creation_card.png') }}" alt="Creación de cartas INFEST">
                    </div>
                    <div class="experience-card-content">
                        <h2>Creación de cartas</h2>
                        <p>Diseña cartas personalizadas o descubre cartas aleatorias.</p>
                        <span class="experience-card-action">Crear carta <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </article>

            <article class="experience-card">
                <a class="experience-card-link" href="{{ route('album.index') }}">
                    <div class="experience-card-image">
                        <img src="{{ asset('images/experiences/infest_album.png') }}" alt="Album de cartas INFEST">
                    </div>
                    <div class="experience-card-content">
                        <h2>Mi colección</h2>
                        <p>Organiza tus cartas, crea colecciones y conserva tu álbum personalizado.</p>
                        <span class="experience-card-action">Abrir colección <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </article>

            <article class="experience-card">
                <a class="experience-card-link" href="{{ route('roulette.index') }}">
                    <div class="experience-card-image">
                        <img src="{{ asset('images/brand/Infest_Logo completo.png') }}" alt="INFEST Ultimate Challenge">
                    </div>
                    <div class="experience-card-content">
                        <h2>Ultimate Challenge</h2>
                        <p>Supera los cuatro desafíos, descubre la ruleta y desbloquea recompensas especiales.</p>
                        <span class="experience-card-action">Iniciar desafío <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </article>
        </div>
    </div>
@endsection
