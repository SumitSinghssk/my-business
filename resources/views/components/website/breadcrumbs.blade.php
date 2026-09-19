{{--
    Visible breadcrumb trail + BreadcrumbList structured data.
    Usage: <x-website.breadcrumbs :items="[['label' => 'Insights', 'url' => route('blog.index')], ['label' => $blog->title]]" />
    "Home" is prepended automatically; the last item is the current page (no link needed).
--}}

@props(['items' => []])

@php
    $trail = array_merge([['label' => 'Home', 'url' => route('home')]], $items);
    $lastIndex = count($trail) - 1;

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($trail)
            ->map(
                fn ($item, $i) => array_filter([
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $item['label'],
                    'item' => $item['url'] ?? ($i === $lastIndex ? url()->current() : null),
                ]),
            )
            ->all(),
    ];
@endphp

<nav aria-label="Breadcrumb" {{ $attributes->class('min-w-0') }}>
    <ol class="gap-x-space-sm font-label-sm text-label-sm flex flex-wrap items-center gap-y-1 tracking-widest uppercase">
        @foreach ($trail as $item)
            <li class="gap-space-sm flex min-w-0 items-center">
                @if ($loop->last)
                    <span class="text-on-surface truncate font-semibold" aria-current="page">{{ Str::limit($item['label'], 60) }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="text-outline hover:text-primary transition-colors">{{ $item['label'] }}</a>
                    <span class="text-outline-variant" aria-hidden="true">/</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

@push('heads')
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
@endpush
