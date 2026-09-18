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

    @include('layouts.partials.website.header')
    <main class="grow">
        {{ $slot }}
    </main>
    @include('layouts.partials.website.footer')
</x-app>
