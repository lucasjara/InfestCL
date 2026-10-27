<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Conoce INFEST Chile: una marca con historia, nacida de los encuentros y abierta a distintas experiencias de entretenimiento.">
        <meta name="theme-color" content="#f5f2ed">

        <title>INFEST Chile — Una historia de encuentros</title>

        <style>
            :root {
                color-scheme: light;
                --ink: #17151b;
                --muted: #65616a;
                --paper: #f5f2ed;
                --white: #fff;
                --line: #ded9d1;
                --accent: #c4f05b;
                --violet: #5c36a5;
                --font: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
                scroll-padding-top: 6rem;
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

            a:focus-visible {
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
                border-radius: 999px;
                background: var(--ink);
                color: var(--white);
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
                text-decoration: none;
            }

            .brand-mark {
                display: grid;
                width: 42px;
                height: 42px;
                place-items: center;
                border-radius: 50%;
                background: var(--accent);
                color: var(--ink);
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

            .main-nav a {
                font-size: .84rem;
                font-weight: 650;
                text-decoration: none;
                transition: opacity .2s ease;
            }

            .main-nav a:hover {
                opacity: .65;
            }

            .main-nav .nav-cta {
                padding: .8rem 1.1rem;
                border-radius: 999px;
                background: var(--ink);
                color: var(--white);
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
                border: 1px solid var(--ink);
                border-radius: 999px;
                background: var(--ink);
                color: var(--white);
                font-size: .9rem;
                font-weight: 750;
                text-decoration: none;
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
                background: var(--white);
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
                background: var(--white);
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
                background: #e9e4dc;
            }

            .facet-card {
                display: grid;
                min-height: 430px;
                grid-template-columns: 1fr .9fr;
                overflow: hidden;
                border-radius: 1.25rem;
                background: var(--ink);
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
                color: var(--ink);
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
                background: var(--white);
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

            .footer-inner a {
                font-weight: 700;
                text-decoration: none;
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

                .main-nav a {
                    font-size: .76rem;
                }

                .main-nav a:nth-child(2) {
                    display: none;
                }

                .main-nav .nav-cta {
                    padding: .7rem .85rem;
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

            @media (prefers-reduced-motion: reduce) {
                *,
                *::before,
                *::after {
                    scroll-behavior: auto !important;
                    transition-duration: .01ms !important;
                }
            }
        </style>
    </head>
    <body>
        <a class="skip-link" href="#contenido">Saltar al contenido</a>

        <header class="site-header">
            <div class="shell header-inner">
                <a class="brand" href="#inicio" aria-label="INFEST Chile, inicio">
                    <span class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 32 32" width="25" height="25" fill="none">
                            <path d="M16 3.5 19.1 12l8.4-3.1-3.1 8.4 8.1 3.2-8.1 3.1 3.1 8.4-8.4-3.1L16 37l-3.1-8.1-8.4 3.1 3.1-8.4-8.1-3.1 8.1-3.2-3.1-8.4 8.4 3.1L16 3.5Z" transform="translate(0 -4)" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
                            <circle cx="16" cy="16" r="3.5" fill="currentColor"/>
                        </svg>
                    </span>
                    <span>
                        <span class="brand-name">INFEST</span>
                        <span class="brand-note">Ícono temporal · identidad por definir</span>
                    </span>
                </a>

                <nav class="main-nav" aria-label="Navegación principal">
                    <a href="#historia">Historia</a>
                    <a href="#facetas">Facetas</a>
                    <a class="nav-cta" href="https://www.instagram.com/infestchile/" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span></a>
                </nav>
            </div>
        </header>

        <main id="contenido">
            <section class="shell hero" id="inicio" aria-labelledby="hero-title">
                <div>
                    <p class="eyebrow">INFEST Chile · Presentación de marca</p>
                    <h1 id="hero-title">Una historia de <span>encuentros.</span></h1>
                    <p class="hero-copy">INFEST es una marca vinculada a la creación de eventos y experiencias de entretenimiento. Nació desde una comunidad y con el tiempo fue abriendo nuevas formas de reunirse.</p>
                    <div class="button-row">
                        <a class="button" href="#historia">Conoce su historia <span aria-hidden="true">↓</span></a>
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
                        <h2 id="about-title">¿Qué es INFEST?</h2>
                    </div>
                    <div class="intro-copy">
                        <p><strong>INFEST es el concepto principal:</strong> una marca con trayectoria en la organización de encuentros y experiencias de entretenimiento.</p>
                        <p>Sus orígenes están ligados a los videojuegos y la comunidad gamer en Temuco. Esa historia es parte de su recorrido, no un límite para todo lo que INFEST puede representar hoy.</p>
                    </div>
                </div>
            </section>

            <section class="history shell" id="historia" aria-labelledby="history-title">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">Un recorrido en el tiempo</p>
                        <h2 id="history-title">De los tarreos a nuevas experiencias.</h2>
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
                <div class="shell facet-card">
                    <div class="facet-content">
                        <p class="section-kicker">Una faceta de INFEST</p>
                        <h2 id="facet-title">INFEST Night Party</h2>
                        <p>INFEST Night Party es una faceta nocturna del universo INFEST. Sus publicaciones y registros muestran una expresión propia dentro de la historia de la marca.</p>
                        <a class="button" href="https://www.instagram.com/infest_nightparty/" target="_blank" rel="noopener noreferrer">Ver archivo en Instagram <span aria-hidden="true">↗</span></a>
                    </div>
                    <div class="facet-image">
                        <img src="{{ asset('images/INFEST_Night_Party/2024-08-06_19-29-45_UTC_1.webp') }}" alt="Registro visual de una producción nocturna de INFEST Night Party" loading="lazy">
                    </div>
                </div>
            </section>

            <section class="gallery" aria-labelledby="gallery-title">
                <div class="shell">
                    <div class="section-heading">
                        <div>
                            <p class="section-kicker">Momentos de archivo</p>
                            <h2 id="gallery-title">Una marca, distintas experiencias.</h2>
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
                <h2 id="social-title">Conoce más sobre INFEST en sus canales.</h2>
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
                <a href="#inicio">Volver arriba ↑</a>
            </div>
        </footer>
    </body>
</html>
