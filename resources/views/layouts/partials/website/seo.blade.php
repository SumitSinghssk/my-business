@php
    use App\Helpers\Settings;
    use App\Models\Seo;

    $path = request()->path();

    // Priority: an admin SEO record for this exact URL > the page's own defaults
    // (passed via <x-website title/description/image>) > the site-wide `default-seo` record.
    $pageSeo = Seo::forPath($path);
    $defaultSeo = Seo::forPath('default-seo');

    // A filtered or paginated listing (?category=…, ?service=…, ?page=2) is a different page from the
    // plain URL, so it keeps its own specific title/description instead of the record for the plain URL.
    // Tracking parameters (utm_*, gclid…) do not count.
    $isVariantUrl = collect(request()->query())
        ->reject(fn ($value, $key) => str_starts_with((string) $key, 'utm_') || in_array($key, ['gclid', 'fbclid', 'msclkid', 'ref'], true))
        ->isNotEmpty();
    $recordMeta = $isVariantUrl ? null : $pageSeo;

    $appName = Settings::appName();
    $metaTitle = Seo::withSiteName($recordMeta?->meta_title ?: ($title ?? null ?: ($defaultSeo?->meta_title ?: $appName)));
    $metaDescription = Seo::withSiteName($recordMeta?->meta_description ?: ($description ?? null ?: $defaultSeo?->meta_description));

    $fallbackOgImage = $defaultSeo?->og_image ?: settings('basic_settings.logo.light');
    $ogImageUrl = match (true) {
        (bool) $pageSeo?->og_image => asset('storage/' . $pageSeo->og_image),
        (bool) ($image ?? null) => $image,
        (bool) $fallbackOgImage => asset('storage/' . $fallbackOgImage),
        // Last resort so shared links always have a preview image.
        default => asset('images/website/hero/dashboard.jpg'),
    };

    // Canonical: explicit prop, else the clean path (keeping ?page=N so paginated pages are distinct).
    $page = (int) request()->query('page', 1);
    $canonicalUrl = $canonical ?? null ?: url()->to($path === '/' ? '' : $path) . ($page > 1 ? '?page=' . $page : '');

    $isIndexable = ! ($noindex ?? false) && ($pageSeo?->index ?? true);

    $favicon = Settings::favicon() ?? asset('favicon.ico');
    $scriptSettings = settings('script_settings') ?? [];

    // Organization + WebSite structured data (all pages).
    $logo = Settings::logoLight();
    $organization = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => url('/') . '#organization',
        'name' => $appName,
        'url' => url('/'),
        'logo' => $logo,
        'email' => Settings::emails()[0] ?? null,
        'telephone' => Settings::phones()[0] ?? null,
        'address' => collect(Settings::addresses())
            ->pluck('text')
            ->filter()
            ->map(fn ($text) => trim(preg_replace('/\s*\R\s*/', ', ', $text)))
            ->first(),
        'sameAs' => array_values(array_filter(array_column(Settings::socialLinks(), 'url'))) ?: null,
    ]);
    $website = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => url('/') . '#website',
        'name' => $appName,
        'url' => url('/'),
        'publisher' => ['@id' => url('/') . '#organization'],
    ];
@endphp

@push('heads')
    <title>{{ $metaTitle }}</title>
    {{-- No canonical on noindex pages (404s, excluded pages): the two signals would contradict each other. --}}
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

    <script type="application/ld+json">
        {!! json_encode([$organization, $website], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>

    {{-- Admin → SEO "Schema" for this URL, always output as valid JSON-LD. --}}
    @if ($schemaJson = $pageSeo?->schemaJson())
        <script type="application/ld+json">
            {!! $schemaJson !!}
        </script>
    @endif

    {{-- Page-specific CSS/scripts come only from this URL's own record; site-wide code lives in Settings → Scripts. --}}
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
