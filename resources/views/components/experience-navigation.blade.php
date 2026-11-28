@props([
    'current' => null,
])

<a href="{{ route('experiences.index') }}" @if($current === 'experiences') aria-current="page" @endif>
    <i data-lucide="home"></i>
    Experiencias
</a>
