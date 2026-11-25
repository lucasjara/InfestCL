{{--
    Componente reutilizable: overlay de revelación de cartas.
    Uso: <x-card-reveal />
    La lógica vive en resources/js/cards/CardReveal.js (window.InfestCardReveal).
--}}
<div id="card-reveal" class="card-reveal" hidden aria-hidden="true">
    <div class="card-reveal__backdrop"></div>
    <div class="card-reveal__portal" aria-hidden="true"></div>
    <canvas class="card-reveal__particles" aria-hidden="true"></canvas>

    <div class="card-reveal__stage">
        <div class="card-reveal__card-wrap">
            <div class="card-reveal__glow" aria-hidden="true"></div>

            <div class="card-reveal__card">
                <div class="card-reveal__face card-reveal__face--back" aria-hidden="true">
                    <div class="card-reveal__back-mark">
                        <span class="card-reveal__back-emblem">
                            <img src="{{ asset('images/brand/Infest_Logo_completo.png') }}" alt="INFEST">
                        </span>
                        <span class="card-reveal__back-title">INFEST</span>
                        <span class="card-reveal__back-sub">Card</span>
                    </div>
                </div>
                <div class="card-reveal__face card-reveal__face--front"></div>
            </div>

            <div class="card-reveal__holographic" aria-hidden="true"></div>
            <div class="card-reveal__flash" aria-hidden="true"></div>
        </div>
    </div>

    <button type="button" class="card-reveal__skip">Saltar animación</button>
    <button type="button" class="card-reveal__close">Continuar</button>
</div>