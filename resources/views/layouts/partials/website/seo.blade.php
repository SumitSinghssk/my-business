@php
    use App\Helpers\Settings;
    use App\Models\Seo;

    $path = request()->path();

    $pageSeo = Seo::forPath($path);
    $defaultSeo = Seo::forPath('default-seo');

    $isVariantUrl = collect(request()->query())
        ->reject(fn ($value, $key) => str_starts_with((string) $key, 'utm_') || in_array($key, ['gclid', 'fbclid', 'msclkid', 'ref'], true))
        ->isNotEmpty();
    $recordMeta = $isVariantUrl ? null : $pageSeo;

    $metaTitle = Seo::withSiteName($recordMeta?->meta_title ?: ($title ?? null ?: ($defaultSeo?->meta_title ?: $appName)));
    $metaDescription = Seo::withSiteName($recordMeta?->meta_description ?: ($description ?? null ?: $defaultSeo?->meta_description));

    $fallbackOgImage = $defaultSeo?->og_image ?: settings('basic_settings.logo.light');
    $ogImageUrl = match (true) {
        (bool) $pageSeo?->og_image => asset('storage/' . $pageSeo->og_image),
        (bool) ($image ?? null) => $image,
        (bool) $fallbackOgImage => asset('storage/' . $fallbackOgImage),
        default => asset('images/website/hero/dashboard.jpg'),
    };

    $page = (int) request()->query('page', 1);
    $canonicalUrl = $canonical ?? null ?: url()->to($path === '/' ? '' : $path) . ($page > 1 ? '?page=' . $page : '');

    $isIndexable = ! ($noindex ?? false) && ($pageSeo?->index ?? true);

    $favicon = Settings::favicon() ?? asset('favicon.ico');
    $scriptSettings = settings('script_settings') ?? [];
@endphp

@push('heads')
    <title>{{ $metaTitle }}</title>
    @if ($isIndexable)
        <link rel="canonical" href="{{ $canonicalUrl }}" />
    @endif

    <link rel="icon" href="{{ $favicon }}" />
    <meta name="robots" content="{{ $isIndexable ? 'index, follow' : 'noindex, follow' }}" />

    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}" />
    @endif

    <meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
    <meta property="og:site_name" content="{{ $appName }}" />
    <meta property="og:locale" content="en_US" />
    @if ($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}" />
    @endif

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $metaTitle }}" />
    @if ($metaDescription)
        <meta name="twitter:description" content="{{ $metaDescription }}" />
    @endif

    @if ($ogImageUrl)
        <meta property="og:image" content="{{ $ogImageUrl }}" />
        <meta property="og:image:alt" content="{{ $metaTitle }}" />
        <meta name="twitter:image" content="{{ $ogImageUrl }}" />
    @endif

    <x-website.json-ld :data="[\App\Support\StructuredData::organization(), \App\Support\StructuredData::website()]" />

    @if ($schemaJson = $pageSeo?->schemaJson())
        <script type="application/ld+json">
            {!! $schemaJson !!}
        </script>
    @endif

    @if ($pageSeo?->custom_css)
        <style>
            {!! $pageSeo->custom_css !!}
        </style>
    @endif

    @if (! empty($scriptSettings['header_css'] ?? null))
        <style>
            {!! $scriptSettings['header_css'] !!}
        </style>
    @endif

    @if (! empty($scriptSettings['header_scripts'] ?? null))
        {!! $scriptSettings['header_scripts'] !!}
    @endif
@endpush

@push('head-scripts')
    @if ($pageSeo?->header_scripts)
        {!! $pageSeo->header_scripts !!}
    @endif
@endpush

@push('scripts')
    @if ($pageSeo?->footer_scripts)
        {!! $pageSeo->footer_scripts !!}
    @endif

    @if (! empty($scriptSettings['footer_scripts'] ?? null))
        {!! $scriptSettings['footer_scripts'] !!}
    @endif

    @if (! empty($scriptSettings['footer_css'] ?? null))
        <style>
            {!! $scriptSettings['footer_css'] !!}
        </style>
    @endif
@endpush
