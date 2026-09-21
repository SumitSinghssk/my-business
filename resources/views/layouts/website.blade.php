<x-app>
    @include('layouts.partials.website.seo')

    @push('head-scripts')
        <link rel="preload" href="{{ asset('fonts/geist-latin-600-normal.woff2') }}" as="font" type="font/woff2" crossorigin />
        <link rel="preload" href="{{ asset('fonts/hanken-grotesk-latin-400-normal.woff2') }}" as="font" type="font/woff2" crossorigin />

        <style>
            @font-face {
                font-family: 'Geist';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url('{{ asset('fonts/geist-latin-400-normal.woff2') }}') format('woff2');
            }

            @font-face {
                font-family: 'Geist';
                font-style: normal;
                font-weight: 500;
                font-display: swap;
                src: url('{{ asset('fonts/geist-latin-500-normal.woff2') }}') format('woff2');
            }

            @font-face {
                font-family: 'Geist';
                font-style: normal;
                font-weight: 600;
                font-display: swap;
                src: url('{{ asset('fonts/geist-latin-600-normal.woff2') }}') format('woff2');
            }

            @font-face {
                font-family: 'Hanken Grotesk';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url('{{ asset('fonts/hanken-grotesk-latin-400-normal.woff2') }}') format('woff2');
            }

            @font-face {
                font-family: 'Hanken Grotesk';
                font-style: normal;
                font-weight: 500;
                font-display: swap;
                src: url('{{ asset('fonts/hanken-grotesk-latin-500-normal.woff2') }}') format('woff2');
            }

            body {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
        </style>

        @vite(['resources/css/website.css', 'resources/js/app.js'])
    @endpush

    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:bg-[#0A0A0A] focus:px-4 focus:py-3 focus:text-sm focus:text-white"
    >
        Skip to content
    </a>

    @include('layouts.partials.website.header')
    <main id="main-content" tabindex="-1" class="bg-surface w-full grow focus:outline-none">
        {{ $slot }}
    </main>
    @include('layouts.partials.website.footer')
</x-app>
