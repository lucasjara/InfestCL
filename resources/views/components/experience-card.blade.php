{{--
    Tarjeta de experiencia reutilizable.
    Uso:
    <x-experience-card
        title="Torneos"
        description="..."
        :image="asset('images/experiences/infest_tournament.png')"
        alt="Torneo INFEST"
        :href="route('tournaments.create')"
        action="Crear torneo" />
--}}
@props([
    'title',
    'description',
    'image',
    'alt' => '',
    'href',
    'action',
    'icon' => null,
    'requiresAuth' => false,
])

<article class="experience-card">
    <a class="experience-card-link" href="{{ $href }}">
        <div class="experience-card-image">
            <img src="{{ $image }}" alt="{{ $alt }}" loading="lazy" width="16" height="10">
        </div>
        <div class="experience-card-content">
            <h2>{{ $title }}</h2>
            <p>{{ $description }}</p>
            <span class="experience-card-action">
                @if($requiresAuth)
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    Requiere iniciar sesión
                @elseif($icon)
                    <i data-lucide="{{ $icon }}" aria-hidden="true"></i>
                @endif
                {{ $action }} <span aria-hidden="true">→</span>
            </span>
        </div>
    </a>
</article>