@php
    $pageTitle = fn ($category, $page = 1) => ($category ? $category->name . ' Articles' : 'Insights') . ($page > 1 ? ' – Page ' . $page : '') . ' | ' . $appName;
    $titles = $categories->mapWithKeys(fn ($category) => [$category->slug => $pageTitle($category)])->put('', $pageTitle(null));
    $tabClass = 'blog-tab font-label-sm text-label-sm shrink-0 border px-3.5 py-2 tracking-wider whitespace-nowrap uppercase transition-colors';
@endphp

<x-website
    :title="$pageTitle($activeCategory, $blogs->currentPage())"
    :description="$activeCategory?->description ?: 'Articles on engineering, systems design and product craftsmanship.'"
    :canonical="route('blog.index', array_filter(['category' => $activeCategory?->slug, 'page' => $blogs->currentPage() > 1 ? $blogs->currentPage() : null]))"
    :image="$blogs->first()?->featured_image_url"
>
    <section x-data="blogListing({ titles: @js($titles) })" class="py-space-xl w-full">
        <div class="site-container">
            <div class="pb-space-lg flex flex-col justify-between gap-2 md:flex-row md:items-end">
                <h1 class="font-display text-headline-lg-mobile text-on-surface md:text-headline-lg leading-tight tracking-tight">Insights</h1>
                <span class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">
                    {{ $totalPublished }} {{ Str::plural('Article', $totalPublished) }}
                </span>
            </div>

            @if ($categories->isNotEmpty())
                <nav class="border-line flex gap-1.5 overflow-x-auto border-y py-3" aria-label="Blog categories">
                    <a
                        href="{{ route('blog.index') }}"
                        data-category=""
                        x-on:click.prevent="select($el)"
                        :class="{ 'is-active': active === '' }"
                        :aria-current="active === '' ? 'page' : null"
                        @class([$tabClass, 'is-active' => ! $activeCategory])
                    >
                        All ({{ $totalPublished }})
                    </a>
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('blog.index', ['category' => $category->slug]) }}"
                            data-category="{{ $category->slug }}"
                            x-on:click.prevent="select($el)"
                            :class="{ 'is-active': active === @js($category->slug) }"
                            :aria-current="active === @js($category->slug) ? 'page' : null"
                            @class([$tabClass, 'is-active' => $activeCategory?->is($category)])
                        >
                            {{ $category->name }} ({{ $category->blogs_count }})
                        </a>
                    @endforeach
                </nav>
            @endif

            <div
                x-ref="listing"
                x-on:click="paginate($event)"
                :class="{ 'pointer-events-none opacity-50': loading }"
                :aria-busy="loading"
                aria-live="polite"
                class="pt-space-lg transition-opacity"
            >
                @include('website.blog.partials.listing')
            </div>
        </div>
    </section>

    <x-website.faq class="bg-white" />
</x-website>
