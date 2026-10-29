<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Conoce INFEST Chile: una marca con historia, nacida de los encuentros y abierta a distintas experiencias de entretenimiento.">
        <meta name="theme-color" content="#f5f2ed">
        <link rel="icon" type="image/png" href="{{ asset('images/brand/Infest_Borde s-fondo.png') }}">
        <meta property="og:title" content="INFEST Chile — Una historia de encuentros">
        <meta property="og:description" content="Conoce INFEST Chile, su historia y sus distintas experiencias de entretenimiento.">
        <meta property="og:image" content="{{ asset('images/brand/Infest_Logo completo.png') }}">

        <title>INFEST Chile — Una historia de encuentros</title>

        <script>
            (() => {
                let theme;

                try {
                    theme = localStorage.getItem('infest-theme');
                } catch (error) {
                    console.error('No fue posible leer la preferencia de tema.', error);
                }

                if (theme !== 'light' && theme !== 'dark') {
                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }

                document.documentElement.dataset.theme = theme;
            })();
        </script>

        <style>
            :root {
                color-scheme: light;
                --ink: #17151b;
                --muted: #65616a;
                --paper: #f5f2ed;
                --white: #fff;
                --surface: #fff;
                --facets: #e9e4dc;
                --line: #ded9d1;
                --contrast-bg: #17151b;
                --contrast-fg: #fff;
                --accent: #c4f05b;
                --violet: #5c36a5;
                --font: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            :root[data-theme="dark"] {
                color-scheme: dark;
                --ink: #f3eff7;
                --muted: #b8b2c2;
                --paper: #15121a;
                --surface: #211d27;
                --facets: #1f1b25;
                --line: #393440;
                --contrast-bg: #f3eff7;
                --contrast-fg: #17151b;
                --violet: #c3a5ff;
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: auto;
            }

            [data-scroll-target] {
                scroll-margin-top: 1.5rem;
            }

            body {
                margin: 0;
                background: var(--paper);
                color: var(--ink);
                font-family: var(--font);
                -webkit-font-smoothing: antialiased;
            }

            a {
                color: inherit;
            }

            a:focus-visible,
            button:focus-visible {
                outline: 3px solid var(--violet);
                outline-offset: 4px;
            }

            .skip-link {
                position: absolute;
                z-index: 10;
                top: 1rem;
                left: 1rem;
                padding: .75rem 1rem;
                transform: translateY(-160%);
                border: 0;
                border-radius: 999px;
                background: var(--contrast-bg);
                color: var(--contrast-fg);
                font: inherit;
                font-weight: 750;
                cursor: pointer;
            }

            .skip-link:focus {
                transform: translateY(0);
            }

            .shell {
                width: min(1180px, calc(100% - 3rem));
                margin-inline: auto;
            }

            .site-header {
                position: relative;
                z-index: 2;
                border-bottom: 1px solid var(--line);
                background: var(--paper);
            }

            .header-inner {
                display: flex;
                min-height: 82px;
                align-items: center;
                justify-content: space-between;
                gap: 2rem;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: .75rem;
                padding: 0;
                border: 0;
                background: transparent;
                color: inherit;
                text-decoration: none;
                cursor: pointer;
            }

            .brand-mark {
                display: grid;
                width: clamp(112px, 11vw, 126px);
                height: 42px;
                place-items: center;
                overflow: hidden;
            }

            .brand-mark img {
                display: block;
                width: 100%;
                max-width: 126px;
                height: 100%;
                max-height: 42px;
                object-fit: contain;
            }

            .brand-mark .logo-dark {
                display: none;
            }

            :root[data-theme="dark"] .brand-mark .logo-light {
                display: none;
            }

            :root[data-theme="dark"] .brand-mark .logo-dark {
                display: block;
            }

            .brand-name {
                display: block;
                font-size: 1.15rem;
                font-weight: 900;
                letter-spacing: -.06em;
                line-height: 1;
            }

            .brand-note {
                display: block;
                margin-top: .3rem;
                color: var(--muted);
                font-size: .61rem;
                font-weight: 650;
                letter-spacing: .08em;
                text-transform: uppercase;
            }

            .main-nav {
                display: flex;
                align-items: center;
                gap: clamp(1rem, 3vw, 2.5rem);
            }

            .main-nav a,
            .main-nav button:not(.theme-toggle) {
                font-size: .84rem;
                font-weight: 650;
                text-decoration: none;
                transition: opacity .2s ease;
            }

            .main-nav a:hover,
            .main-nav button:not(.theme-toggle):hover {
                opacity: .65;
            }

            .main-nav button:not(.theme-toggle) {
                padding: 0;
                border: 0;
                background: transparent;
                color: inherit;
                font-family: inherit;
                cursor: pointer;
            }

            .main-nav .nav-cta {
                padding: .8rem 1.1rem;
                border-radius: 999px;
                background: var(--contrast-bg);
                color: var(--contrast-fg);
            }

            .theme-toggle {
                display: grid;
                width: 42px;
                height: 42px;
                flex: 0 0 auto;
                place-items: center;
                padding: 0;
                border: 1px solid var(--line);
                border-radius: 50%;
                background: transparent;
                color: var(--ink);
                cursor: pointer;
            }

            .theme-toggle:hover {
                background: var(--surface);
            }

            .theme-toggle svg {
                width: 19px;
                height: 19px;
            }

            .theme-toggle .sun-icon {
                display: none;
            }

            :root[data-theme="dark"] .theme-toggle .moon-icon {
                display: none;
            }

            :root[data-theme="dark"] .theme-toggle .sun-icon {
                display: block;
            }

            .hero {
                display: grid;
                min-height: 610px;
                grid-template-columns: 1fr .95fr;
                align-items: center;
                gap: clamp(2.5rem, 6vw, 6rem);
                padding-block: 5.5rem;
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: .6rem;
                margin: 0 0 1.35rem;
                color: var(--muted);
                font-size: .75rem;
                font-weight: 750;
                letter-spacing: .13em;
                text-transform: uppercase;
            }

            .eyebrow::before {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--violet);
                content: "";
            }

            h1,
            h2,
            h3,
            p {
                margin-top: 0;
            }

            h1 {
                max-width: 700px;
                margin-bottom: 1.5rem;
                font-size: clamp(3.5rem, 8vw, 7.3rem);
                font-weight: 900;
                letter-spacing: -.085em;
                line-height: .88;
            }

            h1 span {
                color: var(--violet);
            }

            .hero-copy {
                max-width: 510px;
                margin-bottom: 2rem;
                color: var(--muted);
                font-size: clamp(1.05rem, 1.7vw, 1.22rem);
                line-height: 1.7;
            }

            .button-row {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: .85rem;
            }

            .button {
                display: inline-flex;
                min-height: 50px;
                align-items: center;
                justify-content: center;
                gap: .65rem;
                padding: .85rem 1.2rem;
                border: 1px solid var(--contrast-bg);
                border-radius: 999px;
                background: var(--contrast-bg);
                color: var(--contrast-fg);
                font-family: inherit;
                font-size: .9rem;
                font-weight: 750;
                text-decoration: none;
                cursor: pointer;
                transition: transform .2s ease, background .2s ease;
            }

            .button:hover {
                transform: translateY(-2px);
                background: var(--violet);
            }

            .button-secondary {
                background: transparent;
                color: var(--ink);
            }

            .button-secondary:hover {
                background: var(--surface);
                color: var(--ink);
            }

            .hero-visual {
                position: relative;
                min-height: 440px;
                overflow: hidden;
                border-radius: 1.4rem;
                background: #251b39;
            }

            .hero-visual::after {
                position: absolute;
                inset: 30% 0 0;
                background: linear-gradient(transparent, rgb(15 12 23 / 74%));
                content: "";
            }

            .hero-visual img {
                position: absolute;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center 38%;
            }

            .photo-label {
                position: absolute;
                z-index: 1;
                right: 1.25rem;
                bottom: 1.2rem;
                left: 1.25rem;
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 1rem;
                color: var(--white);
            }

            .photo-label p {
                max-width: 240px;
                margin: 0;
                font-size: 1.1rem;
                font-weight: 750;
                line-height: 1.3;
            }

            .photo-label span {
                flex: 0 0 auto;
                padding: .5rem .75rem;
                border: 1px solid rgb(255 255 255 / 55%);
                border-radius: 999px;
                font-size: .7rem;
                font-weight: 700;
                letter-spacing: .08em;
                text-transform: uppercase;
            }

            .intro {
                padding-block: 6rem;
                border-top: 1px solid var(--line);
                border-bottom: 1px solid var(--line);
                background: var(--surface);
            }

            .intro-grid {
                display: grid;
                grid-template-columns: .8fr 1.2fr;
                gap: clamp(2rem, 8vw, 8rem);
            }

            .section-kicker {
                margin-bottom: .85rem;
                color: var(--violet);
                font-size: .75rem;
                font-weight: 800;
                letter-spacing: .14em;
                text-transform: uppercase;
            }

            h2 {
                margin-bottom: 1.25rem;
                font-size: clamp(2.2rem, 4.5vw, 4.2rem);
                font-weight: 850;
                letter-spacing: -.07em;
                line-height: .98;
            }

            .intro-copy {
                max-width: 680px;
                color: var(--muted);
                font-size: clamp(1.05rem, 1.6vw, 1.25rem);
                line-height: 1.8;
            }

            .intro-copy strong {
                color: var(--ink);
            }

            .history {
                padding-block: 7rem;
            }

            .section-heading {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 2rem;
                margin-bottom: 3rem;
            }

            .section-heading h2 {
                max-width: 560px;
                margin-bottom: 0;
            }

            .section-heading > p {
                max-width: 350px;
                margin-bottom: .35rem;
                color: var(--muted);
                line-height: 1.7;
            }

            .timeline {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                border-top: 1px solid var(--line);
            }

            .timeline-item {
                position: relative;
                padding: 1.6rem 1.4rem 0 0;
            }

            .timeline-item::before {
                position: absolute;
                top: -.35rem;
                left: 0;
                width: 10px;
                height: 10px;
                border: 2px solid var(--paper);
                border-radius: 50%;
                background: var(--violet);
                box-shadow: 0 0 0 1px var(--violet);
                content: "";
            }

            .timeline-year {
                display: block;
                margin-bottom: 1.25rem;
                color: var(--violet);
                font-size: 1.1rem;
                font-weight: 850;
            }

            .timeline-item h3 {
                margin-bottom: .6rem;
                font-size: 1.1rem;
                letter-spacing: -.03em;
            }

            .timeline-item p {
                max-width: 250px;
                margin-bottom: 0;
                color: var(--muted);
                font-size: .9rem;
                line-height: 1.65;
            }

            .facets {
                padding-block: 6.5rem;
                background: var(--facets);
            }

            .facet-card {
                display: grid;
                min-height: 430px;
                grid-template-columns: 1fr .9fr;
                overflow: hidden;
                border-radius: 1.25rem;
                background: #17151b;
                color: var(--white);
            }

            .facet-content {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                padding: clamp(2rem, 5vw, 5rem);
            }

            .facet-content .section-kicker {
                color: var(--accent);
            }

            .facet-content h2 {
                max-width: 540px;
                font-size: clamp(2.5rem, 5.5vw, 5rem);
            }

            .facet-content p:not(.section-kicker) {
                max-width: 450px;
                margin-bottom: 1.8rem;
                color: #cbc6d1;
                line-height: 1.75;
            }

            .facet-content .button {
                border-color: var(--accent);
                background: var(--accent);
                color: #17151b;
            }

            .facet-content .button:hover {
                border-color: var(--white);
                background: var(--white);
            }

            .facet-image {
                min-height: 360px;
                background: #38244f;
            }

            .facet-image img {
                width: 100%;
                height: 100%;
                min-height: 360px;
                object-fit: cover;
            }

            .gallery {
                padding-block: 7rem;
                background: var(--surface);
            }

            .gallery-grid {
                display: grid;
                grid-template-columns: 1.15fr .85fr 1fr;
                gap: 1rem;
            }

            .gallery-card {
                position: relative;
                min-height: 330px;
                overflow: hidden;
                border-radius: 1rem;
                background: #292330;
            }

            .gallery-card img {
                width: 100%;
                height: 100%;
                min-height: 330px;
                object-fit: cover;
                transition: transform .5s ease;
            }

            .gallery-card:hover img {
                transform: scale(1.035);
            }

            .gallery-card::after {
                position: absolute;
                inset: 45% 0 0;
                background: linear-gradient(transparent, rgb(14 12 18 / 68%));
                content: "";
                pointer-events: none;
            }

            .gallery-card figcaption {
                position: absolute;
                z-index: 1;
                right: 1rem;
                bottom: 1rem;
                left: 1rem;
                color: var(--white);
                font-size: .9rem;
                font-weight: 750;
            }

            .social {
                padding-block: 6rem;
                text-align: center;
            }

            .social h2 {
                max-width: 700px;
                margin-inline: auto;
            }

            .social > p:not(.section-kicker) {
                max-width: 520px;
                margin: 0 auto 1.75rem;
                color: var(--muted);
                line-height: 1.7;
            }

            .social .button-row {
                justify-content: center;
            }

            .site-footer {
                padding-block: 1.5rem;
                border-top: 1px solid var(--line);
            }

            .footer-inner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                color: var(--muted);
                font-size: .78rem;
            }

            .footer-inner p {
                margin: 0;
            }

            .footer-inner button {
                padding: 0;
                border: 0;
                background: transparent;
                color: inherit;
                font-family: inherit;
                font-size: inherit;
                font-weight: 700;
                cursor: pointer;
            }

            @media (max-width: 850px) {
                .hero {
                    min-height: auto;
                    grid-template-columns: 1fr 1fr;
                    gap: 2rem;
                    padding-block: 4rem;
                }

                .hero-visual {
                    min-height: 400px;
                }

                .intro-grid {
                    grid-template-columns: 1fr 1.3fr;
                    gap: 2rem;
                }

                .timeline {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    row-gap: 2.5rem;
                }

                .facet-card {
                    grid-template-columns: 1fr 1fr;
                }

                .gallery-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .gallery-card:last-child {
                    display: none;
                }
            }

            @media (max-width: 600px) {
                .shell {
                    width: min(100% - 2rem, 460px);
                }

                .header-inner {
                    min-height: 72px;
                }

                .brand-note {
                    font-size: .53rem;
                }

                .main-nav {
                    gap: .9rem;
                }

                .main-nav a,
                .main-nav button:not(.theme-toggle) {
                    font-size: .76rem;
                }

                .main-nav button[data-scroll="#facetas"] {
                    display: none;
                }

                .main-nav .nav-cta {
                    padding: .7rem .85rem;
                }

                .theme-toggle {
                    width: 38px;
                    height: 38px;
                }

                .hero {
                    grid-template-columns: 1fr;
                    gap: 2.25rem;
                    padding-block: 4rem 3rem;
                }

                h1 {
                    font-size: clamp(3.5rem, 19vw, 5.6rem);
                }

                .hero-visual {
                    min-height: 350px;
                }

                .intro,
                .history,
                .gallery,
                .social {
                    padding-block: 4.5rem;
                }

                .intro-grid,
                .facet-card {
                    grid-template-columns: 1fr;
                }

                .section-heading {
                    display: block;
                }

                .section-heading > p {
                    margin-top: 1rem;
                }

                .timeline {
                    grid-template-columns: 1fr;
                    row-gap: 1.5rem;
                    margin-left: .3rem;
                    border-top: 0;
                    border-left: 1px solid var(--line);
                }

                .timeline-item {
                    padding: 0 0 0 1.5rem;
                }

                .timeline-item::before {
                    top: .2rem;
                    left: -.35rem;
                }

                .timeline-year {
                    margin-bottom: .4rem;
                }

                .facet-content {
                    padding: 2rem;
                }

                .facet-image,
                .facet-image img {
                    min-height: 300px;
                    max-height: 380px;
                }

                .gallery-grid {
                    grid-template-columns: 1fr;
                }

                .gallery-card,
                .gallery-card img {
                    min-height: 300px;
                }

                .gallery-card:last-child {
                    display: block;
                }

                .footer-inner {
                    align-items: flex-start;
                    flex-direction: column;
                }
            }

            @media (max-width: 400px) {
                .brand-note {
                    display: none;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                *,
                *::before,
                *::after {
                    scroll-behavior: auto !important;
                    transition-duration: .01ms !important;
                }
            }
            .titles{
                user-select: none;
                -webkit-user-select: none;
            }
        </style>
    </head>
    <body>
        <button class="skip-link" type="button" data-scroll="#contenido">Saltar al contenido</button>

        <header class="site-header">
            <div class="shell header-inner">
                <button class="brand" type="button" data-scroll="#inicio" aria-label="INFEST Chile, inicio">
                    <span class="brand-mark" aria-hidden="true">
                        <img class="logo-light" src="{{ asset('images/brand/Logotipo-Infest-trazado-removebg-preview.png') }}" alt="" width="126" height="42">
                        <img class="logo-dark" src="{{ asset('images/brand/Infest_Logo_completo.png') }}" alt="" width="126" height="42">
                    </span>
                </button>

                <nav class="main-nav" aria-label="Navegación principal">
                    <button type="button" data-scroll="#historia">Historia</button>
                    <button type="button" data-scroll="#facetas">Facetas</button>
                    <button type="button" data-scroll="#gallery">Experiencias</button>
                    <a class="nav-cta" href="https://www.instagram.com/infestchile/" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span></a>
                    <button class="theme-toggle" type="button" aria-label="Activar modo oscuro" aria-pressed="false">
                        <svg class="moon-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M20.2 15.1A8.4 8.4 0 0 1 8.9 3.8 8.5 8.5 0 1 0 20.2 15.1Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg class="sun-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                </nav>
            </div>
        </header>

        <main id="contenido">
            <section class="shell hero" id="inicio" data-scroll-target="hero" aria-labelledby="hero-title">
                <div>
                    <p class="eyebrow">INFEST Chile · Presentación de marca</p>
                    <h1 id="hero-title" class="titles">Una historia de <span>encuentros.</span></h1>
                    <p class="hero-copy">INFEST es una marca vinculada a la creación de eventos y experiencias de entretenimiento. Nació desde una comunidad y con el tiempo fue abriendo nuevas formas de reunirse.</p>
                    <div class="button-row">
                        <button class="button" type="button" data-scroll="#historia">Conoce su historia <span aria-hidden="true">↓</span></button>
                        <a class="button button-secondary" href="https://www.instagram.com/infestchile/" target="_blank" rel="noopener noreferrer">Visitar Instagram <span aria-hidden="true">↗</span></a>
                    </div>
                </div>

                <div class="hero-visual">
                    <img src="{{ asset('images/INFEST/2023-10-14_13-19-03_UTC_2.webp') }}" alt="Participantes junto a una zona de juegos y el espacio de INFEST" fetchpriority="high">
                    <div class="photo-label">
                        <p>Experiencias que se viven en comunidad.</p>
                        <span>Archivo visual · 2023</span>
                    </div>
                </div>
            </section>

            <section class="intro" aria-labelledby="about-title">
                <div class="shell intro-grid">
                    <div>
                        <p class="section-kicker">Más que un solo formato</p>
                        <h2 id="about-title" class="titles">¿Qué es INFEST?</h2>
                    </div>
                    <div class="intro-copy">
                        <p><strong>INFEST es el concepto principal:</strong> una marca con trayectoria en la organización de encuentros y experiencias de entretenimiento.</p>
                        <p>Sus orígenes están ligados a los videojuegos y la comunidad gamer en Temuco. Esa historia es parte de su recorrido, no un límite para todo lo que INFEST puede representar hoy.</p>
                    </div>
                </div>
            </section>

            <section class="history shell" id="historia" aria-labelledby="history-title">
                <div class="section-heading" data-scroll-target="history-heading">
                    <div>
                        <p class="section-kicker">Un recorrido en el tiempo</p>
                        <h2 id="history-title" class="titles">De los tarreos a nuevas experiencias.</h2>
                    </div>
                    <p>Una mirada breve a algunos hitos documentados. Las actividades mencionadas pertenecen a ediciones históricas.</p>
                </div>

                <div class="timeline">
                    <article class="timeline-item">
                        <span class="timeline-year">2016</span>
                        <h3>El comienzo</h3>
                        <p>Una iniciativa estudiantil vinculada a videojuegos y cultura gamer da origen a los encuentros INFEST en Temuco.</p>
                    </article>
                    <article class="timeline-item">
                        <span class="timeline-year">2017</span>
                        <h3>Crece la comunidad</h3>
                        <p>Una nueva edición reúne torneos, cosplay, talleres y zonas de consolas.</p>
                    </article>
                    <article class="timeline-item">
                        <span class="timeline-year">2019</span>
                        <h3>Más formas de participar</h3>
                        <p>El evento incorpora actividades como realidad virtual y juegos de mesa, junto a otras experiencias.</p>
                    </article>
                    <article class="timeline-item">
                        <span class="timeline-year">2021</span>
                        <h3>Una nueva edición</h3>
                        <p>La prensa regional documenta el regreso de INFEST en Temuco, con actividades programadas durante la noche.</p>
                    </article>
                </div>
            </section>

            <section class="facets" id="facetas" aria-labelledby="facet-title">
                <div class="shell facet-card" data-scroll-target="facets-card">
                    <div class="facet-content">
                        <p class="section-kicker">Una faceta de INFEST</p>
                        <h2 id="facet-title" class="titles">INFEST Night Party</h2>
                        <p>INFEST Night Party es una faceta nocturna del universo INFEST. Sus publicaciones y registros muestran una expresión propia dentro de la historia de la marca.</p>
                        <a class="button" href="https://www.instagram.com/infest_nightparty/" target="_blank" rel="noopener noreferrer">Ver archivo en Instagram <span aria-hidden="true">↗</span></a>
                    </div>
                    <div class="facet-image">
                        <img src="{{ asset('images/INFEST_Night_Party/2024-08-06_19-29-45_UTC_1.webp') }}" alt="Registro visual de una producción nocturna de INFEST Night Party" loading="lazy">
                    </div>
                </div>
            </section>

            <section class="gallery" id="gallery" aria-labelledby="gallery-title">
                <div class="shell">
                    <div class="section-heading" data-scroll-target="gallery-heading">
                        <div>
                            <p class="section-kicker">Momentos de archivo</p>
                            <h2 id="gallery-title" class="titles">Una marca, distintas experiencias.</h2>
                        </div>
                        <p>Selección visual de materiales locales disponibles. Los registros corresponden a eventos anteriores.</p>
                    </div>

                    <div class="gallery-grid">
                        <figure class="gallery-card">
                            <img src="{{ asset('images/INFEST/2023-10-14_00-06-15_UTC_1.webp') }}" alt="Participante caracterizada en una actividad de INFEST" loading="lazy">
                            <figcaption>Comunidad y creatividad</figcaption>
                        </figure>
                        <figure class="gallery-card">
                            <img src="{{ asset('images/INFEST/2023-10-14_13-19-03_UTC_1.webp') }}" alt="Espacio de encuentro y actividades en un evento INFEST" loading="lazy">
                            <figcaption>Espacios para compartir</figcaption>
                        </figure>
                        <figure class="gallery-card">
                            <img src="{{ asset('images/INFEST_Night_Party/2024-08-07_01-08-03_UTC.jpg') }}" alt="Escenario iluminado y público en un evento de INFEST Night Party" loading="lazy">
                            <figcaption>INFEST Night Party</figcaption>
                        </figure>
                    </div>
                </div>
            </section>

            <section class="social shell" aria-labelledby="social-title">
                <p class="section-kicker">Sigue el recorrido</p>
                <h2 id="social-title" class="titles">Conoce más sobre INFEST en sus canales.</h2>
                <p>Visita el Instagram de INFEST Chile para encontrar publicaciones y novedades compartidas por la marca.</p>
                <div class="button-row">
                    <a class="button" href="https://www.instagram.com/infestchile/" target="_blank" rel="noopener noreferrer">Instagram INFEST Chile <span aria-hidden="true">↗</span></a>
                    <a class="button button-secondary" href="https://web.facebook.com/infesttemuco" target="_blank" rel="noopener noreferrer">Facebook <span aria-hidden="true">↗</span></a>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <div class="shell footer-inner">
                <p>© {{ date('Y') }} INFEST Chile · Propuesta visual preliminar</p>
                <button type="button" data-scroll="#inicio">Volver arriba ↑</button>
            </div>
        </footer>
        <script>
            const scrollTargets = {
                '#inicio': document.querySelector('[data-scroll-target="hero"]'),
                '#historia': document.querySelector('[data-scroll-target="history-heading"]'),
                '#facetas': document.querySelector('[data-scroll-target="facets-card"]'),
                '#gallery': document.querySelector('[data-scroll-target="gallery-heading"]'),
                '#contenido': document.querySelector('#contenido'),
            };

            async function navigateToSection(hash, updateUrl = true) {
                await new Promise(resolve => requestAnimationFrame(resolve));

                const target = scrollTargets[hash] || document.querySelector(hash);
                if (!target) return;

                const offset = hash === '#historia'
                    ? (window.innerWidth <= 600 ? 12 : 16)
                    : (window.innerWidth <= 600 ? 16 : 24);
                const top = Math.max(0, window.scrollY + target.getBoundingClientRect().top - offset);

                window.scrollTo({
                    top,
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                });

                if (updateUrl) {
                    history.pushState(null, '', hash);
                }
            }

            document.querySelectorAll('[data-scroll]').forEach(control => {
                control.addEventListener('click', () => {
                    navigateToSection(control.dataset.scroll);
                });
            });

            window.addEventListener('popstate', () => {
                navigateToSection(window.location.hash || '#inicio', false);
            });

            if (window.location.hash && scrollTargets[window.location.hash]) {
                window.addEventListener('load', () => {
                    requestAnimationFrame(() => navigateToSection(window.location.hash, false));
                }, { once: true });
            }

            const themeToggle = document.querySelector('.theme-toggle');
            const themeColor = document.querySelector('meta[name="theme-color"]');

            function applyTheme(theme) {
                document.documentElement.dataset.theme = theme;
                themeToggle.setAttribute('aria-pressed', String(theme === 'dark'));
                themeToggle.setAttribute('aria-label', theme === 'dark' ? 'Activar modo claro' : 'Activar modo oscuro');
                themeColor.content = theme === 'dark' ? '#15121a' : '#f5f2ed';
            }

            applyTheme(document.documentElement.dataset.theme);

            themeToggle.addEventListener('click', () => {
                const theme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';

                applyTheme(theme);

                try {
                    localStorage.setItem('infest-theme', theme);
                } catch (error) {
                    console.error('No fue posible guardar la preferencia de tema.', error);
                }
            });
        </script>
    </body>
</html>
