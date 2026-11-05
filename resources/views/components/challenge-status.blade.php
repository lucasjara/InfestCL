@props([
    'id',
    'name',
])

<li class="roulette-status-item" data-challenge-status="{{ $id }}">
    <span>{{ $name }}</span>
    <strong data-challenge-state-label>Pendiente</strong>
</li>
