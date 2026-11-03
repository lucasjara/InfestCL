@extends('layouts.base')

@section('title', 'Ultimate Challenge | INFEST Chile')

@section('meta_description', 'Supera cuatro desafíos en INFEST Ultimate Challenge.')

@section('navigation')
    <a href="{{ route('experiences.index') }}">Experiencias</a>
    <a href="{{ route('roulette.index') }}" aria-current="page">Ultimate Challenge</a>
@endsection

@section('styles')
    .roulette-page {
        min-height: calc(100vh - 150px);
        padding-block: clamp(1.25rem, 4vw, 2.5rem);
        background:
            radial-gradient(circle at 12% 15%, rgb(196 240 91 / 10%), transparent 24rem),
            radial-gradient(circle at 88% 75%, rgb(92 54 165 / 12%), transparent 28rem);
    }

    .roulette-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .roulette-heading p,
    .roulette-instructions-kicker {
        margin: 0 0 .4rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 850;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .roulette-heading h1 {
        max-width: 680px;
        margin: 0;
        font-size: clamp(2rem, 6vw, 4rem);
        letter-spacing: -.07em;
        line-height: .92;
    }

    .roulette-layout {
        display: grid;
        grid-template-columns: minmax(320px, 1fr) minmax(280px, 380px);
        align-items: center;
        gap: clamp(1.25rem, 5vw, 4rem);
    }

    .roulette-stage {
        display: grid;
        justify-items: center;
        gap: 1rem;
    }

    .roulette-wheel {
        position: relative;
        width: min(440px, 80vw);
        aspect-ratio: 1;
        border: 10px solid var(--surface);
        border-radius: 50%;
        background: conic-gradient(
            from -45deg,
            #ef4444 0deg 90deg,
            #5c36a5 90deg 180deg,
            #22c55e 180deg 270deg,
            #eab308 270deg 360deg
        );
        box-shadow: 0 22px 60px rgb(23 21 27 / 18%);
        transform: rotate(0deg);
        transition: transform 1.8s cubic-bezier(.12, .65, .16, 1);
    }

    .roulette-wheel.is-spinning {
        transform: rotate(var(--roulette-rotation));
    }

    .roulette-wheel::before {
        position: absolute;
        inset: 28%;
        border: 7px solid var(--surface);
        border-radius: 50%;
        background: var(--paper);
        box-shadow: 0 0 0 1px var(--line);
        content: '';
    }

    .roulette-wheel::after {
        position: absolute;
        top: -24px;
        left: 50%;
        width: 24px;
        height: 38px;
        transform: translateX(-50%);
        border-radius: 4px 4px 12px 12px;
        background: var(--accent, #c4f05b);
        box-shadow: 0 4px 12px rgb(0 0 0 / 20%);
        content: '';
        clip-path: polygon(50% 100%, 0 0, 100% 0);
    }

    .roulette-label {
        position: absolute;
        z-index: 1;
        display: grid;
        width: 24%;
        height: 24%;
        place-items: center;
        border-radius: 50%;
        color: #fff;
        font-size: clamp(.62rem, 1.5vw, .85rem);
        font-weight: 900;
        text-align: center;
    }

    .roulette-label:nth-child(1) { top: 7%; left: 38%; }
    .roulette-label:nth-child(2) { top: 38%; right: 7%; }
    .roulette-label:nth-child(3) { bottom: 7%; left: 38%; }
    .roulette-label:nth-child(4) { top: 38%; left: 7%; }

    .roulette-control,
    .roulette-primary-button,
    .roulette-secondary-button {
        min-height: 48px;
        padding-inline: 1.1rem;
        border-radius: 999px;
        font: inherit;
        font-weight: 850;
        cursor: pointer;
    }

    .roulette-control,
    .roulette-primary-button {
        border: 0;
        background: var(--contrast-bg);
        color: var(--contrast-fg);
    }

    .roulette-control:disabled {
        cursor: wait;
        opacity: .65;
    }

    .roulette-secondary-button {
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink);
        text-decoration: none;
    }

    .roulette-panel {
        display: grid;
        gap: 1rem;
    }

    .roulette-selected-card,
    .roulette-status-card,
    .roulette-instructions-card {
        border: 1px solid var(--line);
        border-radius: 20px;
        background: var(--surface);
        box-shadow: 0 16px 50px rgb(23 21 27 / 8%);
    }

    .roulette-selected-card {
        padding: 1.25rem;
    }

    .roulette-selected-card span {
        color: var(--muted);
        font-size: .7rem;
        font-weight: 850;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .roulette-selected-card h2 {
        margin: .35rem 0 1rem;
        font-size: clamp(1.35rem, 4vw, 2rem);
        letter-spacing: -.04em;
    }

    .roulette-status-card {
        padding: 1rem;
    }

    .roulette-status-list {
        display: grid;
        gap: .55rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .roulette-status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-block: .45rem;
        border-bottom: 1px solid var(--line);
        font-size: .78rem;
    }

    .roulette-status-item:last-child {
        border-bottom: 0;
    }

    .roulette-status-item strong {
        color: var(--muted);
        font-size: .68rem;
        text-transform: uppercase;
    }

    .roulette-status-item[data-status="completed"] strong {
        color: #15803d;
    }

    .roulette-hud {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .65rem;
        margin-bottom: 1rem;
        padding: .7rem;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .roulette-hud div {
        display: grid;
        gap: .15rem;
        padding: .35rem;
    }

    .roulette-hud span {
        color: var(--muted);
        font-size: .68rem;
        font-weight: 750;
        text-transform: uppercase;
    }

    .roulette-hud strong {
        font-variant-numeric: tabular-nums;
        font-size: 1.25rem;
    }

    .roulette-sound-button {
        grid-column: 1 / -1;
        min-height: 40px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--paper);
        color: var(--ink);
        font: inherit;
        font-size: .76rem;
        font-weight: 800;
        cursor: pointer;
    }

    .roulette-instructions-card {
        max-width: 760px;
        margin-inline: auto;
        padding: clamp(1.25rem, 4vw, 2rem);
        text-align: center;
    }

    .roulette-instructions-card h1 {
        margin: 0 0 .75rem;
        font-size: clamp(2rem, 7vw, 4rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .roulette-instructions-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .75rem;
        margin-block: 1.5rem;
    }

    .roulette-instruction {
        min-height: 110px;
        padding: 1rem;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--paper);
    }

    .roulette-instruction span {
        display: block;
        margin-bottom: .45rem;
        color: var(--violet);
        font-size: .68rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .roulette-instruction p {
        margin: 0;
        font-size: .82rem;
        line-height: 1.45;
    }

    .roulette-instructions-actions {
        display: flex;
        justify-content: center;
        gap: .75rem;
    }

    .roulette-countdown {
        display: grid;
        min-height: 220px;
        place-items: center;
        color: var(--violet);
        font-size: clamp(4rem, 15vw, 9rem);
        font-weight: 950;
        line-height: 1;
    }

    @media (max-width: 850px) {
        .roulette-heading {
            align-items: start;
            flex-direction: column;
        }

        .roulette-layout {
            grid-template-columns: 1fr;
        }

        .roulette-panel {
            width: min(100%, 520px);
            margin-inline: auto;
        }
    }

    @media (max-width: 600px) {
        .roulette-wheel {
            width: min(340px, 84vw);
            border-width: 7px;
        }

        .roulette-instructions-grid {
            grid-template-columns: 1fr;
        }

        .roulette-instructions-actions {
            flex-direction: column;
        }
    }
@endsection

@push('head')
    @vite('resources/js/roulette/index.js')
@endpush

@section('content')
    <div class="roulette-page">
        <section class="shell" data-roulette-screen="roulette" aria-labelledby="roulette-title">
            <header class="roulette-heading">
                <div data-countdown-panel hidden>
                    <p>INFEST Ultimate Challenge</p>
                    <h1 id="roulette-title">Elige tu próximo desafío</h1>
                </div>
                <a class="roulette-secondary-button" href="{{ route('experiences.index') }}">← Experiencias</a>
            </header>

            <x-game-hud show-sound />

            <div class="roulette-layout">
                <div class="roulette-stage">
                    <div class="roulette-wheel" data-roulette aria-label="Ruleta de cuatro experiencias">
                        <span class="roulette-label">Piedra<br>papel tijera</span>
                        <span class="roulette-label">Space<br>Defender</span>
                        <span class="roulette-label">Trivia<br>Express</span>
                        <span class="roulette-label">Laberinto</span>
                    </div>
                    <button class="roulette-control" type="button" data-spin-roulette>
                        Girar ruleta
                    </button>
                </div>

                <aside class="roulette-panel" aria-label="Seleccionado y progreso">
                    <div class="roulette-selected-card" data-selected-challenge hidden>
                        <span>Desafío seleccionado</span>
                        <h2 data-selected-name>---</h2>
                        <p>Prepárate para iniciar la experiencia.</p>
                        <button class="roulette-primary-button" type="button" data-start-challenge>
                            Iniciar experiencia
                        </button>
                    </div>

                    <div class="roulette-status-card">
                        <ul class="roulette-status-list">
                            <x-challenge-status id="rock-paper-scissors" name="Piedra, papel o tijera" />
                            <x-challenge-status id="space-defender" name="INFEST Space Defender" />
                            <x-challenge-status id="trivia" name="Trivia Express" />
                            <x-challenge-status id="maze" name="Laberinto" />
                        </ul>
                    </div>
                </aside>
            </div>
        </section>

        <section class="shell" data-roulette-screen="instructions" data-instructions-panel hidden>
            <div class="roulette-instructions-card">
                <div data-instructions-content>
                    <p class="roulette-instructions-kicker">Instrucciones previas</p>
                    <h1 data-instruction-name>Experiencia</h1>
                    <div class="roulette-instructions-grid">
                        <div class="roulette-instruction">
                            <span>Objetivo</span>
                            <p>Completa la condición principal antes de que termine el tiempo.</p>
                        </div>
                        <div class="roulette-instruction">
                            <span>Controles</span>
                            <p>Los controles se mostrarán cuando el minijuego esté disponible.</p>
                        </div>
                        <div class="roulette-instruction">
                            <span>Tiempo</span>
                            <p>El cronómetro comenzará después de la cuenta regresiva.</p>
                        </div>
                    </div>
                    <div class="roulette-instructions-actions">
                        <button class="roulette-secondary-button" type="button" data-back-to-roulette>
                            Volver a la ruleta
                        </button>
                        <button class="roulette-primary-button" type="button" data-begin-challenge>
                            Comenzar
                        </button>
                    </div>
                </div>
                <div>
                    <div class="roulette-countdown" data-countdown aria-live="assertive" hidden></div>
                    <button class="roulette-secondary-button" type="button" data-back-to-roulette>
                        Volver a la ruleta
                    </button>
                </div>
            </div>
        </section>
    </div>
@endsection
