<x-app>
    @include('layouts.partials.website.seo')

    @push('head-scripts')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endpush

    <main>
        {{ $slot }}
    </main>
</x-app>
