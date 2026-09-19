<x-app>
    @include('layouts.partials.website.seo')

    @push('head-scripts')
        <link
            href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Hanken+Grotesk:wght@400;500&display=swap"
            rel="stylesheet"
        />

        @vite(['resources/css/website.css', 'resources/js/app.js'])

        <style>
            body {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
        </style>
    @endpush

    @include('layouts.partials.website.header')
    <main class="bg-surface w-full grow">
        {{ $slot }}
    </main>
    @include('layouts.partials.website.footer')
</x-app>
