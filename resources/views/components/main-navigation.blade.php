{{--
    Navegación principal reutilizable.
    Uso: <x-main-navigation current="experiences|album|cards" />
--}}
@props([
    'current' => null,
])

<a href="{{ route('experiences.index') }}" @if($current === 'experiences') aria-current="page" @endif>
    <i data-lucide="home" aria-hidden="true"></i>
    Experiencias
</a>
<a href="{{ route('album.index') }}" @if($current === 'album') aria-current="page" @endif>
    <i data-lucide="layers" aria-hidden="true"></i>
    Colecciones
</a>
<a href="{{ route('cards.list') }}" @if($current === 'cards') aria-current="page" @endif>
    <i data-lucide="sparkles" aria-hidden="true"></i>
    Generador
</a>