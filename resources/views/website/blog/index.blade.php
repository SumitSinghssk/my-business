<x-website
    :title="($activeCategory ? $activeCategory->name . ' Articles' : 'Insights & Essays') . ($blogs->currentPage() > 1 ? ' – Page ' . $blogs->currentPage() : '') . ' | ' . \App\Helpers\Settings::appName()"
    :description="$activeCategory?->description ?: 'Perspectives on engineering, systems design, and product craftsmanship from our engineering and design teams.'"
    :noindex="$search !== '' || $sort !== 'latest'"
>
    @php
        $pillBase = 'font-label-sm text-label-sm border px-3.5 py-2 tracking-wider whitespace-nowrap uppercase transition-colors';
        $pillIdle = 'text-on-surface-variant hover:bg-surface-container border-[#E1E5EA] bg-white';
        $pillActive = 'border-[#0A0A0A] bg-[#0A0A0A] text-white';
        $latestPost = $featured ?? $blogs->first();
    @endphp

    <section class="bg-surface-container-lowest w-full border-b border-[#E1E5EA]">
        <div class="site-container py-space-xl">
            <div class="gap-space-md pb-space-lg flex flex-col justify-between border-b border-[#E1E5EA] md:flex-row md:items-center">
                <x-website.breadcrumbs
                    :items="$activeCategory
                        ? [['label' => 'Insights', 'url' => route('blog.index')], ['label' => $activeCategory->name]]
                    : [['label' => 'Insights']]"
                />
                <a
                    href="#cta"
                    class="font-label-sm text-label-sm text-primary-container hover:text-primary flex items-center gap-1 font-semibold tracking-wider uppercase transition-colors"
                >
                    Talk to our engineers ↗
                </a>
            </div>

            <div class="gap-gutter pt-space-xl grid grid-cols-1 items-end lg:grid-cols-12">
                <div class="space-y-space-md lg:col-span-8">
                    <h1 class="font-display text-headline-lg-mobile text-on-surface md:text-display leading-[1.05] tracking-tight">
                        Perspectives on engineering, systems design, and product craftsmanship.
                    </h1>
                    <p class="font-body-lg text-body-lg text-secondary max-w-2xl">
                        Deep technical dissections, architectural post-mortems, and design system philosophy straight from our engineering pods.
                    </p>
                </div>
                <div class="gap-space-md lg:pl-gutter flex flex-col justify-end lg:col-span-4 lg:border-l lg:border-[#E1E5EA]">
                    <div class="font-label-sm text-label-sm text-secondary flex items-center justify-between tracking-wider uppercase">
                        <span>Total Published</span>
                        <span class="text-on-surface font-semibold">{{ $totalPublished }} {{ Str::plural('Article', $totalPublished) }}</span>
                    </div>
                    <div class="font-label-sm text-label-sm text-secondary flex items-center justify-between tracking-wider uppercase">
                        <span>Topics Covered</span>
                        <span class="text-on-surface font-semibold">
                            {{ $categories->count() }} {{ Str::plural('Category', $categories->count()) }}
                        </span>
                    </div>
                    @if ($latestPost)
                        <div class="font-label-sm text-label-sm text-secondary flex items-center justify-between tracking-wider uppercase">
                            <span>Latest Issue</span>
                            <span class="text-primary-container font-semibold">{{ $latestPost->published_date->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div
                class="mt-space-xl gap-space-md pt-space-xl flex flex-col items-stretch justify-between border-t border-[#E1E5EA] lg:flex-row lg:items-center"
            >
                <nav class="flex items-center gap-1 overflow-x-auto py-1" aria-label="Filter by category">
                    <a
                        href="{{ route('blog.index', array_filter(['search' => $search, 'sort' => $sort !== 'latest' ? $sort : null])) }}"
                        class="{{ $pillBase }} {{ $activeCategory ? $pillIdle : $pillActive }}"
                        @unless ($activeCategory) aria-current="page" @endunless
                    >
                        All Insights ({{ $totalPublished }})
                    </a>
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('blog.index', array_filter(['category' => $category->slug, 'search' => $search, 'sort' => $sort !== 'latest' ? $sort : null])) }}"
                            class="{{ $pillBase }} {{ $activeCategory?->is($category) ? $pillActive : $pillIdle }}"
                            @if ($activeCategory?->is($category)) aria-current="page" @endif
                        >
                            {{ $category->name }} ({{ $category->blogs_count }})
                        </a>
                    @endforeach
                </nav>

                <form method="GET" action="{{ route('blog.index') }}" class="flex items-center gap-2" role="search">
                    @if ($activeCategory)
                        <input type="hidden" name="category" value="{{ $activeCategory->slug }}" />
                    @endif

                    <div class="relative flex-1 sm:w-64">
                        <svg
                            class="text-outline pointer-events-none absolute top-1/2 left-2.5 h-4 w-4 -translate-y-1/2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="square" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                        <label for="blog-search" class="sr-only">Search articles</label>
                        <input
                            id="blog-search"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search articles..."
                            class="font-label-md text-label-md text-on-surface placeholder:text-outline focus:border-primary-container w-full border border-[#E1E5EA] bg-white py-2 pr-3 pl-8 focus:outline-none"
                        />
                    </div>
                    <label for="blog-sort" class="sr-only">Sort articles</label>
                    <select
                        id="blog-sort"
                        name="sort"
                        onchange="this.form.submit()"
                        class="font-label-sm text-label-sm text-on-surface focus:border-primary-container cursor-pointer border border-[#E1E5EA] bg-white py-2 pr-7 pl-3 tracking-wider uppercase focus:outline-none"
                    >
                        <option value="latest" @selected($sort === 'latest')>Sort: Newest</option>
                        <option value="oldest" @selected($sort === 'oldest')>Sort: Oldest</option>
                    </select>
                    <button
                        type="submit"
                        class="font-label-sm text-label-sm hover:border-primary-container hover:bg-primary-container border border-[#0A0A0A] bg-[#0A0A0A] px-3 py-2 tracking-wider text-white uppercase transition-colors"
                    >
                        Go
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if ($featured)
        @php($featuredCategory = $featured->categories->first())
        <section class="pt-space-xl w-full">
            <div class="site-container">
                <article
                    class="group bg-surface-container-lowest hover:border-primary-container relative overflow-hidden rounded-lg border border-[#E1E5EA] transition-colors duration-300"
                >
                    <div class="grid grid-cols-1 lg:grid-cols-12">
                        <div
                            class="relative min-h-[300px] overflow-hidden border-b border-[#E1E5EA] bg-[#0A0A0A] lg:col-span-7 lg:min-h-[480px] lg:border-r lg:border-b-0"
                        >
                            @if ($featured->featured_image_url)
                                <img
                                    src="{{ $featured->featured_image_url }}"
                                    alt="{{ $featured->title }}"
                                    class="absolute inset-0 h-full w-full object-cover opacity-90 transition-transform duration-700 ease-out group-hover:scale-105"
                                    fetchpriority="high"
                                />
                            @endif

                            <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                <span
                                    class="bg-primary-container font-label-sm text-label-sm px-2 py-1 font-semibold tracking-widest text-white uppercase"
                                >
                                    Featured Essay
                                </span>
                                @if ($featuredCategory)
                                    <span
                                        class="font-label-sm text-label-sm border border-white/20 bg-[#0A0A0A]/90 px-2 py-1 tracking-widest text-white uppercase backdrop-blur-sm"
                                    >
                                        {{ $featuredCategory->name }}
                                    </span>
                                @endif
                            </div>
                            <div
                                class="font-label-sm text-label-sm absolute right-4 bottom-4 left-4 flex items-center justify-between border border-white/10 bg-[#0A0A0A]/85 p-3 text-white backdrop-blur-md"
                            >
                                <span class="flex items-center gap-1 font-semibold text-[#10B981]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#10B981]"></span>
                                    Latest
                                </span>
                                <span class="font-mono text-white/80 uppercase">{{ $featured->reading_time }} min read</span>
                            </div>
                        </div>

                        <div class="p-space-lg lg:p-space-xl flex flex-col justify-between lg:col-span-5">
                            <div>
                                <div
                                    class="gap-space-sm pb-space-md font-label-sm text-label-sm text-secondary flex flex-wrap items-center justify-between border-b border-[#E1E5EA] tracking-widest uppercase"
                                >
                                    <span class="text-primary-container font-semibold">{{ $featuredCategory?->name ?? 'Insight' }}</span>
                                    <span>{{ $featured->published_date->format('F Y') }} • {{ $featured->reading_time }} min read</span>
                                </div>
                                <h2
                                    class="pt-space-lg font-headline-lg-mobile text-headline-lg-mobile text-on-surface group-hover:text-primary-container lg:font-headline-lg lg:text-headline-lg leading-tight transition-colors"
                                >
                                    <a href="{{ route('blog.show', $featured->slug) }}" class="after:absolute after:inset-0">
                                        {{ $featured->title }}
                                    </a>
                                </h2>
                                @if ($featured->excerpt)
                                    <p class="pt-space-md font-body-md text-body-md text-on-surface-variant leading-relaxed">
                                        {{ $featured->excerpt }}
                                    </p>
                                @endif

                                @if ($featured->categories->count())
                                    <div class="mt-space-lg flex flex-wrap gap-1.5">
                                        @foreach ($featured->categories as $category)
                                            <span
                                                class="bg-surface-container-low font-label-sm text-label-sm border border-[#E1E5EA] px-2 py-0.5 tracking-wider text-[#434655] uppercase"
                                            >
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="mt-space-lg gap-space-md pt-space-xl flex items-center justify-between border-t border-[#E1E5EA]">
                                <div class="flex items-center gap-3">
                                    <x-website.avatar :user="$featured->author" />
                                    <div>
                                        <span class="font-label-md text-label-md text-on-surface block font-semibold">
                                            {{ $featured->author?->name ?? 'Editorial Team' }}
                                        </span>
                                        <span class="font-label-sm text-label-sm text-secondary block">
                                            {{ $featured->published_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <span
                                    class="font-label-md text-label-md group-hover:bg-primary-container inline-flex items-center gap-2 bg-[#0A0A0A] px-4 py-2.5 tracking-wider text-white uppercase transition-colors"
                                >
                                    Read Article →
                                </span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    @endif

    {{-- Spotlight --}}
    @if ($spotlight->isNotEmpty())
        <section class="pt-space-xl w-full">
            <div class="site-container">
                <div class="pb-space-md flex items-center justify-between border-b border-[#E1E5EA]">
                    <div class="flex items-center gap-2">
                        <span class="bg-on-surface h-2.5 w-2.5"></span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold tracking-tight uppercase">Spotlight</h2>
                    </div>
                    <span class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">Deep Dives</span>
                </div>

                <div class="mt-space-lg gap-gutter grid grid-cols-1 md:grid-cols-2">
                    @foreach ($spotlight as $post)
                        @php($postCategory = $post->categories->first())
                        <article
                            class="group bg-surface-container-lowest hover:border-primary-container relative flex flex-col justify-between overflow-hidden rounded-lg border border-[#E1E5EA] transition-colors"
                        >
                            <div>
                                <div class="relative h-64 overflow-hidden border-b border-[#E1E5EA] bg-[#0A0A0A]">
                                    @if ($post->featured_image_url)
                                        <img
                                            src="{{ $post->featured_image_url }}"
                                            alt="{{ $post->title }}"
                                            class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                            loading="lazy"
                                        />
                                    @endif

                                    <div class="absolute inset-0 bg-linear-to-t from-black/70 to-transparent"></div>
                                    @if ($postCategory)
                                        <span
                                            class="font-label-sm text-label-sm absolute top-3 left-3 border border-white/20 bg-[#0A0A0A]/90 px-2 py-0.5 tracking-wider text-white uppercase"
                                        >
                                            {{ $postCategory->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="p-space-lg">
                                    <div
                                        class="pb-space-sm font-label-sm text-label-sm text-secondary flex items-center justify-between border-b border-[#E1E5EA] tracking-widest uppercase"
                                    >
                                        <span class="text-primary-container font-semibold">{{ $postCategory?->name ?? 'Insight' }}</span>
                                        <span>{{ $post->published_date->format('F Y') }} • {{ $post->reading_time }} min read</span>
                                    </div>
                                    <h3
                                        class="pt-space-md font-headline-md text-headline-md text-on-surface group-hover:text-primary-container leading-snug transition-colors"
                                    >
                                        <a href="{{ route('blog.show', $post->slug) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
                                    </h3>
                                    @if ($post->excerpt)
                                        <p class="pt-space-sm font-body-md text-body-md text-secondary">{{ $post->excerpt }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="px-space-lg pt-space-md pb-space-lg mt-auto flex items-center justify-between border-t border-[#E1E5EA]">
                                <div class="flex items-center gap-2.5">
                                    <x-website.avatar :user="$post->author" size="h-7 w-7" text="font-label-sm text-label-sm" />
                                    <span class="font-label-sm text-label-sm text-on-surface font-semibold">
                                        {{ $post->author?->name ?? 'Editorial Team' }}
                                    </span>
                                </div>
                                <span
                                    class="font-label-sm text-label-sm text-on-surface group-hover:text-primary-container flex items-center gap-1 font-semibold tracking-wider uppercase transition-colors"
                                >
                                    Read →
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Archive grid --}}
    <section class="py-space-xl w-full">
        <div class="site-container">
            <div class="gap-space-sm pb-space-md flex flex-col justify-between border-b border-[#E1E5EA] md:flex-row md:items-center">
                <div class="flex items-center gap-2">
                    <span class="bg-primary-container h-2.5 w-2.5"></span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold tracking-tight uppercase">
                        @if ($search !== '')
                            Results for “{{ $search }}”
                        @elseif ($activeCategory)
                            {{ $activeCategory->name }}
                        @else
                            Latest Articles
                        @endif
                    </h2>
                </div>
                <div class="gap-space-lg font-label-sm text-label-sm text-secondary flex items-center tracking-wider uppercase">
                    <span>{{ $blogs->total() }} {{ Str::plural('Article', $blogs->total()) }}</span>
                    @if ($activeCategory || $search !== '' || $sort !== 'latest')
                        <span class="text-outline">|</span>
                        <a href="{{ route('blog.index') }}" class="text-on-surface hover:text-primary-container font-semibold transition-colors">
                            Clear Filters ×
                        </a>
                    @endif
                </div>
            </div>

            @if ($archive->isNotEmpty())
                <div class="mt-space-lg gap-gutter grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($archive as $blog)
                        @include('website.blog.partials.card', ['blog' => $blog])
                    @endforeach
                </div>
            @elseif (! $featured)
                <div class="mt-space-lg px-space-lg py-space-2xl rounded-lg border border-dashed border-[#C3C6D8] bg-white text-center">
                    <p class="font-headline-sm text-headline-sm text-on-surface font-semibold">No articles found.</p>
                    <p class="mt-space-xs font-body-md text-body-md text-secondary">Try a different search term or browse all insights.</p>
                    <a
                        href="{{ route('blog.index') }}"
                        class="mt-space-lg px-space-lg font-label-md text-label-md hover:bg-primary-container inline-flex items-center bg-[#0A0A0A] py-3 tracking-wider text-white uppercase transition-colors"
                    >
                        View All Insights →
                    </a>
                </div>
            @endif

            @if ($blogs->hasPages())
                <div class="pt-space-xl">
                    {{ $blogs->onEachSide(1)->links('website.partials.pagination') }}
                </div>
            @endif
        </div>
    </section>

    @include(
        'website.blog.partials.cta',
        [
            'ctaTitle' => 'Have a system that needs this level of engineering?',
            'ctaText' => 'Tell us what you are building. Our pods help teams audit, design and ship reliable software at scale.',
        ]
    )
</x-website>
