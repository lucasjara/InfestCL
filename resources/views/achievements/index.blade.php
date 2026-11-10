@extends('layouts.base')

@section('title', 'Mis trofeos | INFEST Chile')

@section('meta_description', 'Consulta tus logros, experiencia y trofeos desbloqueados en INFEST Chile.')

@section('styles')
    .trophy-page {
        padding-block: 3rem 5rem;
    }

    .trophy-heading {
        margin-bottom: 2rem;
    }

    .trophy-eyebrow {
        color: var(--violet);
        font-size: .75rem;
        font-weight: 800;
        letter-spacing: .13em;
        text-transform: uppercase;
    }

    .trophy-title {
        margin: .5rem 0;
        font-size: clamp(2rem, 5vw, 2.8rem);
        font-weight: 850;
        letter-spacing: -.05em;
    }

    .trophy-subtitle {
        color: var(--muted);
        margin: 0;
    }

    .trophy-hero {
        display: grid;
        grid-template-columns: 1.5fr .7fr;
        gap: 2rem;
        align-items: center;
        padding: clamp(1.5rem, 4vw, 2.5rem);
        border: 1px solid var(--line);
        border-radius: 24px;
        background:
            radial-gradient(circle at 90% 10%, rgba(92,54,165,.14), transparent 40%),
            var(--surface);
    }

    .trophy-level {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem .9rem;
        border-radius: 999px;
        background: rgba(92,54,165,.12);
        color: var(--violet);
        font-size: .8rem;
        font-weight: 800;
    }

    .trophy-xp-heading {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: .75rem;
        font-size: .85rem;
    }

    .trophy-xp-heading strong {
        color: var(--violet);
    }

    .trophy-progress {
        height: 10px;
        overflow: hidden;
        border-radius: 20px;
        background: var(--line);
    }

    .trophy-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #7044b5, #b18aff);
        transition: width .4s ease;
    }

    .trophy-hero-art {
        text-align: center;
    }

    .trophy-hero-art span {
        display: block;
        font-size: clamp(5rem, 12vw, 8rem);
        filter: drop-shadow(0 10px 20px rgba(234,179,8,.2));
    }

    .trophy-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-block: 1.5rem 3rem;
    }

    .trophy-stat {
        padding: 1.4rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .trophy-stat-icon {
        font-size: 1.5rem;
        margin-bottom: .8rem;
    }

    .trophy-stat-value {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 850;
        letter-spacing: -.04em;
    }

    .trophy-stat-label {
        color: var(--muted);
        font-size: .8rem;
    }

    .trophy-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .trophy-section-heading h2 {
        font-size: 1.5rem;
        font-weight: 800;
    }

    .trophy-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .trophy-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 0;
        padding: 1.5rem 1rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        text-align: center;
        transition: transform .2s, border-color .2s;
    }

    .trophy-card:hover {
        transform: translateY(-4px);
        border-color: var(--violet);
    }

    .trophy-card.unlocked {
        border-color: rgba(202,138,4,.4);
    }

    .trophy-card.locked .trophy-card-icon {
        filter: grayscale(1);
        opacity: .35;
    }

    .trophy-card-icon {
        display: grid;
        width: 75px;
        height: 75px;
        place-items: center;
        margin-bottom: 1rem;
        border-radius: 22px;
        background: rgba(234,179,8,.1);
        font-size: 2.5rem;
    }

    .trophy-card h3 {
        font-size: .95rem;
        font-weight: 800;
        margin-bottom: .5rem;
    }

    .trophy-description {
        min-height: 42px;
        color: var(--muted);
        font-size: .78rem;
        line-height: 1.5;
    }
    .trophy-card-icon svg {
        width: 32px;
        height: 32px;
        stroke-width: 1.8;
    }

    .trophy-reward {
        margin-block: .5rem 1rem;
        color: #b77900;
        font-size: .85rem;
        font-weight: 800;
    }

    :root[data-theme="dark"] .trophy-reward {
        color: #facc15;
    }

    .trophy-status {
        display: inline-block;
        padding: .4rem .75rem;
        border-radius: 999px;
        background: rgba(22,163,74,.12);
        color: #15803d;
        font-size: .7rem;
        font-weight: 800;
    }

    :root[data-theme="dark"] .trophy-status {
        color: #4ade80;
    }

    .trophy-status.locked-status {
        background: var(--paper);
        color: var(--muted);
    }

    .trophy-card-progress {
        width: 100%;
        margin-top: 1rem;
    }

    .trophy-card-progress small {
        display: block;
        margin-top: .5rem;
        color: var(--muted);
        font-size: .7rem;
    }

    .trophy-empty {
        padding: 4rem 1rem;
        border: 1px dashed var(--line);
        border-radius: 20px;
        text-align: center;
        color: var(--muted);
    }

    @media(max-width: 900px) {
        .trophy-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media(max-width: 600px) {
        .trophy-page {
            padding-block: 2rem 3rem;
        }

        .trophy-hero {
            grid-template-columns: 1fr;
        }

        .trophy-hero-art {
            display: none;
        }

        .trophy-stats {
            gap: .6rem;
        }

        .trophy-stat {
            padding: 1rem .7rem;
        }

        .trophy-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .7rem;
        }

        .trophy-card {
            padding: 1.2rem .7rem;
        }
    }
@endsection

@section('content')

@php
    $totalXp = $stats->total_xp ?? 0;
    $level = $stats->level ?? 1;

    $totalAchievements = $achievements->count();

    $unlockedCount = $achievements->filter(
        fn($achievement) => $achievement->is_unlocked
    )->count();

    $completion = $totalAchievements > 0
        ? round(($unlockedCount / $totalAchievements) * 100)
        : 0;

    $nextLevelXp = match($level) {
        1 => 200,
        2 => 500,
        3 => 1000,
        4 => 2000,
        5 => 5000,
        default => null
    };
@endphp
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        } else {
            console.error('Lucide no se pudo cargar');
        }
    });
</script>
<div class="container trophy-page" style="margin-left:5%;margin-right:5%">

    {{-- Encabezado --}}
    <header class="trophy-heading">
        <div class="trophy-eyebrow">INFEST GAMING EXPERIENCE</div>

        <h1 class="trophy-title">Mis trofeos</h1>

        <p class="trophy-subtitle">
            Hola, {{ auth()->user()->name }}.
            Tu aventura continúa.
        </p>
    </header>

    {{-- Perfil --}}
    <section class="trophy-hero">

        <div>
            <div class="trophy-eyebrow mb-3">
                PERFIL DE JUGADOR
            </div>

            <h2 class="fw-bold mb-3">
                Nivel {{ $level }}
            </h2>

            <div class="trophy-level mb-4">
                ⚡ {{ number_format($totalXp) }} XP acumulados
            </div>

            <div class="trophy-xp-heading">
                <span>Progreso de nivel</span>

                <strong>
                    {{ $nextLevelXp
                        ? number_format($totalXp) . ' / ' . number_format($nextLevelXp) . ' XP'
                        : 'Nivel máximo alcanzado'
                    }}
                </strong>
            </div>

            <div class="trophy-progress">
                <div class="trophy-progress-bar"
                     style="width: {{ $levelProgress }}%">
                </div>
            </div>

            <small class="d-block mt-2" style="color:var(--muted)">
                @if($nextLevelXp)
                    {{ number_format(max(0, $nextLevelXp - $totalXp)) }}
                    XP para alcanzar el siguiente nivel.
                @else
                    ¡Has alcanzado el nivel máximo!
                @endif
            </small>
        </div>

        <div class="trophy-hero-art">
            <span>🏆</span>
            <strong>Tu historia en INFEST</strong>
            <p class="trophy-subtitle mt-2">
                Cada desafío cuenta.
            </p>
        </div>

    </section>

    {{-- Estadísticas --}}
    <section class="trophy-stats">

        <div class="trophy-stat">
            <div class="trophy-stat-icon">🏆</div>

            <div class="trophy-stat-value">
                {{ $unlockedCount }}
            </div>

            <div class="trophy-stat-label">
                Trofeos obtenidos
            </div>
        </div>

        <div class="trophy-stat">
            <div class="trophy-stat-icon">⚡</div>

            <div class="trophy-stat-value">
                {{ number_format($totalXp) }}
            </div>

            <div class="trophy-stat-label">
                Experiencia XP
            </div>
        </div>

        <div class="trophy-stat">
            <div class="trophy-stat-icon">🎯</div>

            <div class="trophy-stat-value">
                {{ $completion }}%
            </div>

            <div class="trophy-stat-label">
                Colección completada
            </div>
        </div>

    </section>

    {{-- Colección --}}
    <section>

        <div class="trophy-section-heading">
            <div>
                <h2 class="mb-1">Colección de trofeos</h2>

                <p class="trophy-subtitle small">
                    {{ $unlockedCount }} de {{ $totalAchievements }}
                    desafíos completados.
                </p>
            </div>
        </div>

        @if($achievements->isNotEmpty())

            <div class="trophy-grid">

                @foreach($achievements as $achievement)

                    <article class="trophy-card {{ $achievement->is_unlocked ? 'unlocked' : 'locked' }}">

                        <div class="trophy-card-icon">
                            @if($achievement->is_unlocked)
                                <i data-lucide="{{ $achievement->icon ?: 'trophy' }}"></i>
                            @else
                                <i data-lucide="lock"></i>
                            @endif
                        </div>

                        <h3>
                            {{ $achievement->name }}
                        </h3>

                        <p class="trophy-description">
                            @if($achievement->is_unlocked)
                                {{ $achievement->description }}
                            @else
                                <span class="trophy-hidden-description">
                                    🔒 Desbloquea este trofeo para descubrirlo.
                                </span>
                            @endif
                        </p>

                        <div class="trophy-reward">
                            +{{ $achievement->xp_reward }} XP
                        </div>

                        @if($achievement->is_unlocked)

                            <span class="trophy-status">
                                DESBLOQUEADO
                            </span>

                        @else

                            <span class="trophy-status locked-status">
                                BLOQUEADO
                            </span>

                            <div class="trophy-card-progress">

                                <div class="trophy-progress">
                                    <div class="trophy-progress-bar"
                                         style="width: {{ $achievement->requirement_value > 0
                                            ? min(100, ($achievement->progress / $achievement->requirement_value) * 100)
                                            : 0 }}%">
                                    </div>
                                </div>

                                <small>
                                    {{ $achievement->progress }}
                                    / {{ $achievement->requirement_value }}
                                </small>

                            </div>

                        @endif

                    </article>

                @endforeach

            </div>

        @else

            <div class="trophy-empty">
                <div style="font-size:3rem">🎮</div>

                <h3 class="mt-3">¡Tu aventura comienza aquí!</h3>

                <p class="mb-0">
                    Próximamente tendrás desafíos para completar.
                </p>
            </div>

        @endif

    </section>

</div>
@endsection