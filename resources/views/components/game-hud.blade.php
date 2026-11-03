@props([
    'showSound' => false,
])

<div class="roulette-hud" aria-label="Estado de la experiencia">
    <div>
        <span>Progreso</span>
        <strong data-roulette-progress>0/4</strong>
    </div>
    <div>
        <span>Tiempo efectivo</span>
        <strong data-global-time>00:00</strong>
    </div>
    @if ($showSound)
        <button class="roulette-sound-button" type="button" data-sound-toggle aria-pressed="true">
            Sonido activo
        </button>
    @endif
</div>
