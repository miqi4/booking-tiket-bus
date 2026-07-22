<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Bus Akas' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    primary: '#004782', 'primary-container': '#185fa5', 'on-primary': '#ffffff', 'on-primary-container': '#c1d9ff',
                    secondary: '#3e6922', 'secondary-container': '#bbee97', 'on-secondary-container': '#426d26',
                    tertiary: '#683c0a', surface: '#fcf9f3', background: '#fcf9f3', 'surface-container-lowest': '#ffffff',
                    'surface-container-low': '#f6f3ee', 'surface-container': '#f1ede8', 'surface-container-high': '#ebe8e2',
                    'surface-container-highest': '#e5e2dd', 'surface-variant': '#e5e2dd', 'on-surface': '#1c1c19',
                    'on-background': '#1c1c19', 'on-surface-variant': '#424751', outline: '#727782', 'outline-variant': '#c2c6d2',
                    error: '#ba1a1a', 'error-container': '#ffdad6', 'on-error-container': '#93000a', 'primary-fixed': '#d4e3ff',
                    'on-primary-fixed': '#001c39', 'inverse-surface': '#31302d', 'inverse-on-surface': '#f3f0eb'
                },
                spacing: { xs: '4px', base: '8px', sm: '12px', md: '24px', lg: '48px', xl: '64px', gutter: '20px', 'container-max': '1200px' },
                borderRadius: { DEFAULT: '0.125rem', lg: '0.25rem', xl: '0.5rem', full: '9999px' },
                fontFamily: { body: ['Work Sans', 'sans-serif'], h1: ['Work Sans'], h2: ['Work Sans'], h3: ['Work Sans'], caption: ['Work Sans'], 'label-form': ['Work Sans'] },
                fontSize: {
                    h1: ['32px', { lineHeight: '1.2', fontWeight: '700' }], 'h1-mobile': ['26px', { lineHeight: '1.2', fontWeight: '700' }],
                    h2: ['24px', { lineHeight: '1.3', fontWeight: '600' }], h3: ['20px', { lineHeight: '1.4', fontWeight: '600' }],
                    body: ['16px', { lineHeight: '1.5', fontWeight: '400' }], caption: ['13px', { lineHeight: '1.4', fontWeight: '400' }],
                    'label-form': ['14px', { lineHeight: '1.2', fontWeight: '500' }]
                }
            }}
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .seat-btn { transition: background-color .18s ease, color .18s ease, border-color .18s ease, transform .18s ease; }
        .seat-btn:hover:not(:disabled) { transform: translateY(-1px); }

        /* Animation Classes */
        .page-enter { opacity: 0; transform: translate3d(0, 28px, 0) scale(0.985); will-change: transform, opacity; }
        .page-ready .page-enter { animation: page-enter-fade 1200ms cubic-bezier(0.16, 1, 0.3, 1) both; animation-delay: var(--enter-delay, 0ms); }
        [data-reveal] { opacity: 0; transform: translate3d(0, 36px, 0); transition: opacity 1000ms cubic-bezier(0.25, 1, 0.5, 1), transform 1000ms cubic-bezier(0.25, 1, 0.5, 1); transition-delay: var(--reveal-delay, 0ms); will-change: transform, opacity; }
        [data-reveal].is-visible { opacity: 1; transform: translate3d(0, 0, 0); }
        @keyframes page-enter-fade {
            0% { opacity: 0; transform: translate3d(0, 28px, 0) scale(0.985); }
            100% { opacity: 1; transform: translate3d(0, 0, 0) scale(1); }
        }
        @media (prefers-reduced-motion: reduce) {
            .page-enter, [data-reveal] { opacity: 1 !important; transform: none !important; animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body class="bg-background text-on-background font-body text-body antialiased min-h-screen flex flex-col">
    @include('passenger.partials.nav')
    @if (session('success') || session('error'))
        <div class="w-full px-gutter pt-sm">
            <div class="rounded-lg border px-sm py-sm {{ session('success') ? 'bg-secondary-container text-on-secondary-container border-secondary-container' : 'bg-error-container text-on-error-container border-error-container' }}">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    @endif
    {{ $slot }}
    @include('passenger.partials.footer')
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            const homePage = document.querySelector('[data-page-home]');
            
            if (prefersReducedMotion.matches) {
                document.body.classList.add('page-ready');
                if (homePage) {
                    const revealItems = Array.from(homePage.querySelectorAll('[data-reveal]'));
                    revealItems.forEach(item => item.classList.add('is-visible'));
                }
                return;
            }

            requestAnimationFrame(() => document.body.classList.add('page-ready'));

            if (homePage) {
                const revealItems = Array.from(homePage.querySelectorAll('[data-reveal]'));
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.18, rootMargin: '0px 0px -8% 0px' });
                
                revealItems.forEach(item => observer.observe(item));
            }
        });
    </script>
</body>
</html>
