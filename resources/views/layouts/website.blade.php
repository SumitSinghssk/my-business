<x-app>
    @include('layouts.partials.website.seo')

    @push('head-scripts')
        @vite(['resources/css/website.css', 'resources/js/app.js'])

        <style>
            body {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
        </style>
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
