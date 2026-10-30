<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta_description', 'INFEST Chile')">
        <meta name="theme-color" content="#f5f2ed">
        <link rel="icon" type="image/png" href="{{ asset('images/brand/Infest_Borde s-fondo.png') }}">

        <title>@yield('title', 'INFEST Chile')</title>

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
                --line: #ded9d1;
                --contrast-bg: #17151b;
                --contrast-fg: #fff;
                --violet: #5c36a5;
                --font: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            :root[data-theme="dark"] {
                color-scheme: dark;
                --ink: #f3eff7;
                --muted: #b8b2c2;
                --paper: #15121a;
                --surface: #211d27;
                --line: #393440;
                --contrast-bg: #f3eff7;
                --contrast-fg: #17151b;
                --violet: #c3a5ff;
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                min-height: 100vh;
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
            button:focus-visible,
            input:focus-visible,
            select:focus-visible,
            textarea:focus-visible {
                outline: 3px solid var(--violet);
                outline-offset: 3px;
            }

            .skip-link {
                position: absolute;
                z-index: 10;
                top: 1rem;
                left: 1rem;
                padding: .75rem 1rem;
                transform: translateY(-160%);
                border-radius: 999px;
                background: var(--contrast-bg);
                color: var(--contrast-fg);
                font-weight: 750;
                text-decoration: none;
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

            .main-nav {
                display: flex;
                align-items: center;
                gap: clamp(1rem, 3vw, 2rem);
            }

            .main-nav a:not(.nav-cta) {
                font-size: .84rem;
                font-weight: 650;
                text-decoration: none;
            }

            .nav-cta {
                padding: .8rem 1.1rem;
                border-radius: 999px;
                background: var(--contrast-bg);
                color: var(--contrast-fg);
                font-size: .84rem;
                font-weight: 650;
                text-decoration: none;
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

            .site-main {
                min-height: calc(100vh - 150px);
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

            @media (max-width: 600px) {
                .shell {
                    width: min(100% - 2rem, 460px);
                }

                .header-inner {
                    min-height: 72px;
                    gap: 1rem;
                }

                .main-nav {
                    gap: .8rem;
                }

                .nav-cta {
                    padding: .7rem .85rem;
                    font-size: .76rem;
                }

                .theme-toggle {
                    width: 38px;
                    height: 38px;
                }

                .footer-inner {
                    align-items: flex-start;
                }
            }

            @yield('styles')
        </style>
        @stack('head')
    </head>
    <body>
        <a class="skip-link" href="#contenido">Saltar al contenido</a>

        <header class="site-header">
            <div class="shell header-inner">
                <a class="brand" href="{{ url('/') }}" aria-label="INFEST Chile, inicio">
                    <span class="brand-mark" aria-hidden="true">
                        <img class="logo-light" src="{{ asset('images/brand/Logotipo-Infest-trazado-removebg-preview.png') }}" alt="" width="126" height="42">
                        <img class="logo-dark" src="{{ asset('images/brand/Infest_Logo_completo.png') }}" alt="" width="126" height="42">
                    </span>
                </a>

                <nav class="main-nav" aria-label="Navegación principal">
                    @hasSection('navigation')
                        @yield('navigation')
                    @else
                        <a class="nav-cta" href="https://www.instagram.com/infestchile/" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span></a>
                    @endif

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

        <main class="site-main" id="contenido">
            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="shell footer-inner">
                <p>© {{ date('Y') }} INFEST Chile</p>
                <a href="#contenido">Volver arriba ↑</a>
            </div>
        </footer>

        <script>
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
        @stack('scripts')
    </body>
</html>
