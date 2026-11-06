@extends('layouts.base')

@section('title', 'App Movil | INFEST Chile')

@section('meta_description', 'Explora la experiencia movil de Infest.')

@section('navigation')
    <x-experience-navigation />
@endsection

@section('styles')

    .app-download {
        position: relative;
        min-height: 750px;
        padding: 5rem 1.5rem;
        padding-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 75%, rgba(92, 54, 165, .35), transparent 55%),
            radial-gradient(ellipse at 10% 10%, rgba(92, 54, 165, .15), transparent 45%);
    }

    .app-download::before,
    .app-download::after {
        content: "";
        position: absolute;
        width: 350px;
        height: 350px;
        border: 1px solid rgba(140, 90, 220, .12);
        border-radius: 50%;
        pointer-events: none;
    }

    .app-download::before {
        top: -220px;
        left: -150px;
    }

    .app-download::after {
        bottom: -220px;
        right: -150px;
    }

    .app-content {
        width: 100%;
        max-width: 620px;
        position: relative;
        z-index: 1;
        text-align: center;
    }

    /* TEXTO */

    .app-title {
        margin: 0 0 2rem;
        font-size: clamp(1.7rem, 5vw, 2.5rem);
        font-weight: 800;
        line-height: 1.35;
        letter-spacing: -.04em;
    }

    .app-title span {
        display: block;
        color: #9b6be8;
    }

    /* QR */

    .app-qr-wrapper {
        width: 290px;
        height: 290px;
        max-width: 100%;
        margin: 0 auto 1.5rem;
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #9560df;
        border-radius: 24px;
        background: #fff;
        box-shadow:
            0 0 18px rgba(149, 96, 223, .5),
            0 0 50px rgba(149, 96, 223, .2);
    }

    #app-qrcode {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
    }

    #app-qrcode img,
    #app-qrcode canvas {
        max-width: 100%;
        height: auto;
    }

    .app-qr-description {
        margin-bottom: 2rem;
        color: var(--muted);
        font-size: .85rem;
    }

    /* BOTONES */

    .app-download-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .app-download-button {
        min-height: 88px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .8rem;
        padding: 1rem;
        border: 1px solid #9560df;
        border-radius: 16px;
        background: var(--surface);
        color: var(--ink);
        text-decoration: none;
        transition: .25s ease;
    }

    .app-download-button:hover {
        transform: translateY(-4px);
        border-color: #9560df;
        box-shadow: 0 8px 30px rgba(149, 96, 223, .2);
    }

    .app-download-button svg {
        width: 35px;
        height: 35px;
        flex-shrink: 0;
    }

    .app-button-text {
        display: flex;
        flex-direction: column;
        text-align: left;
        font-size: .75rem;
        color: var(--muted);
    }

    .app-button-text strong {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 800;
    }

    /* FOOTER */

    .app-download-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        color: var(--muted);
        font-size: .8rem;
        font-weight: 700;
    }

    .app-download-footer::before,
    .app-download-footer::after {
        content: "";
        width: 55px;
        height: 1px;
        background: #9560df;
    }

    @media (max-width: 600px) {
        .app-download {
            min-height: auto;
            padding: 3.5rem 1rem;
        }

        .app-logo img {
            max-width: 300px;
            height: 105px;
        }

        .app-qr-wrapper {
            width: 270px;
            height: 270px;
        }

        .app-download-options {
            grid-template-columns: 1fr;
            gap: .8rem;
        }

        .app-download-button {
            min-height: 75px;
        }
    }

@endsection

@section('content')

    <section class="app-download" id="qr-section">

        <div class="app-content">

            {{-- DESCRIPCIÓN --}}
            <h1 class="app-title">
                Tu colección de cartas,
                <span>ahora en tu móvil.</span>
            </h1>

            {{-- CÓDIGO QR --}}
            <div class="app-qr-wrapper">
                <div id="app-qrcode"></div>
            </div>

            <p class="app-qr-description">
                Escanea el código QR para acceder a las opciones de descarga.
            </p>

            {{-- OPCIONES DE DESCARGA --}}
            <div class="app-download-options">

                {{-- GOOGLE PLAY --}}
                <a
                    href="{{ config('infest.google_play_url') }}"
                    class="app-download-button"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#00D6FF" d="M7 4L27 24L7 44Z"/>
                        <path fill="#00E676" d="M7 4L32 17L27 24Z"/>
                        <path fill="#FFCA28" d="M27 24L32 17L43 23Q47 25 43 28L32 31Z"/>
                        <path fill="#FF5252" d="M7 44L32 31L27 24Z"/>
                    </svg>

                    <span class="app-button-text">
                        Descargar desde
                        <strong>Google Play</strong>
                    </span>
                </a>

                {{-- APK --}}
                <a
                    href="{{ route('app.apk') }}"
                    class="app-download-button"
                >
                    <svg viewBox="0 0 48 48" fill="none"
                         xmlns="http://www.w3.org/2000/svg">

                        <path
                            d="M24 5V29M14 19L24 29L34 19"
                            stroke="currentColor"
                            stroke-width="5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                        <path
                            d="M9 39H39"
                            stroke="currentColor"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                    </svg>

                    <span class="app-button-text">
                        Descargar
                        <strong>APK Android</strong>
                    </span>
                </a>

            </div>

            {{-- PIE DE PÁGINA --}}
            <div class="app-download-footer">
                INFEST Chile
            </div>

        </div>

    </section>

@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const qrContainer = document.getElementById('app-qrcode');

            if (typeof QRCode === 'undefined' || !qrContainer) {
                console.error('No fue posible cargar la librería QR.');
                return;
            }

            const downloadUrl = @json(route('app.apk'));
            new QRCode(qrContainer, {
                text: downloadUrl,
                width: 250,
                height: 250,
                colorDark: '#090516',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
            document.querySelector('.app-qr-wrapper')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
    </script>

@endpush