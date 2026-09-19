@php
    use App\Helpers\Settings;
    use App\Models\Seo;
    use Illuminate\Support\Facades\Cache;

    $path = request()->path();

    $seoCollection = Cache::remember('seo_all', now()->addHours(24), fn () => Seo::all()->keyBy('slug'));

    // Priority: an admin SEO record for this exact URL > the page's own defaults
    // (passed via <x-website title/description/image>) > the site-wide `default-seo` record.
    $pageSeo = $seoCollection[$path] ?? null;
    $defaultSeo = $seoCollection['default-seo'] ?? null;
    $seo = $pageSeo ?? $defaultSeo;

    $appName = Settings::appName();
    $metaTitle = $pageSeo?->meta_title ?: ($title ?? null ?: ($defaultSeo?->meta_title ?: $appName));
    $metaDescription = $pageSeo?->meta_description ?: ($description ?? null ?: $defaultSeo?->meta_description);

    $fallbackOgImage = $defaultSeo?->og_image ?: settings('basic_settings.logo.light');
    $ogImageUrl = match (true) {
        (bool) $pageSeo?->og_image => asset('storage/' . $pageSeo->og_image),
        (bool) ($image ?? null) => $image,
        (bool) $fallbackOgImage => asset('storage/' . $fallbackOgImage),
        default => null,
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
    <link rel="canonical" href="{{ $canonicalUrl }}" />
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
        <meta name="twitter:image" content="{{ $ogImageUrl }}" />
    @endif

    <script type="application/ld+json">
        {!! json_encode([$organization, $website], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>

    @if ($seo?->custom_css)
        <style>
            {!! $seo->custom_css !!}
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
    @if ($seo?->header_scripts)
        {!! $seo->header_scripts !!}
    @endif
@endpush

@push('scripts')
    @if ($seo?->schema)
        {!! $seo->schema !!}
    @endif

    @if ($seo?->footer_scripts)
        {!! $seo->footer_scripts !!}
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
