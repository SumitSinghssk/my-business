@php
    $primaryCategory = $blog->categories->first();
    $shareUrl = urlencode(route('blog.show', $blog->slug));
    $shareTitle = urlencode($blog->title);
    $toc = $content['toc'];

    $articleSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => route('blog.show', $blog->slug),
        'headline' => \Illuminate\Support\Str::limit($blog->title, 110, ''),
        'description' => $blog->excerpt,
        'image' => $blog->featured_image_url,
        'datePublished' => $blog->published_date->toAtomString(),
        'dateModified' => $blog->updated_at?->toAtomString(),
        'wordCount' => str_word_count(strip_tags((string) $blog->content)),
        'articleSection' => $primaryCategory?->name,
        'author' => ['@type' => 'Person', 'name' => $blog->author?->name ?? \App\Helpers\Settings::appName()],
        'publisher' => ['@id' => url('/') . '#organization'],
    ]);
@endphp

<x-website
    :title="$blog->seo?->meta_title ?: $blog->title . ' | ' . \App\Helpers\Settings::appName()"
    :description="$blog->seo?->meta_description ?: $blog->excerpt"
    :image="$blog->featured_image_url"
    og-type="article"
>
    @push('heads')
        <script type="application/ld+json">
            {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
        </script>
        <meta property="article:published_time" content="{{ $blog->published_date->toAtomString() }}" />
        @foreach ($blog->categories as $category)
            <meta property="article:tag" content="{{ $category->name }}" />
        @endforeach
    @endpush

    {{-- Reading progress (sits directly under the sticky header) --}}
    <div
        x-data="{ progress: 0 }"
        x-on:scroll.window.passive="
            progress = Math.min(
                100,
                Math.max(
                    0,
                    (window.scrollY /
                        Math.max(
                            1,
                            document.documentElement.scrollHeight - window.innerHeight,
                        )) *
                        100,
                ),
            )
        "
        class="bg-surface-variant sticky top-19 z-40 h-0.5 w-full"
        aria-hidden="true"
    >
        <div class="bg-primary h-full transition-[width] duration-75" :style="`width: ${progress}%`"></div>
    </div>

    {{-- Article header --}}
    <section class="bg-surface-container-lowest py-space-xl w-full">
        <div class="site-container">
            <div class="gap-space-sm pb-space-lg flex flex-wrap items-center justify-between">
                <x-website.breadcrumbs :items="[['label' => 'Insights', 'url' => route('blog.index')], ['label' => $blog->title]]" />
                <span class="font-label-sm text-label-sm text-outline shrink-0 tracking-widest uppercase">{{ $blog->reading_time }} min read</span>
            </div>

            @if ($blog->categories->isNotEmpty())
                <div class="pt-space-sm pb-space-md flex flex-wrap gap-2">
                    @foreach ($blog->categories as $category)
                        <a
                            href="{{ route('blog.index', ['category' => $category->slug]) }}"
                            class="bg-surface-container font-label-sm text-label-sm text-primary hover:bg-surface-container-high inline-block px-3 py-1 font-semibold tracking-widest uppercase transition-colors"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <h1 class="font-display text-headline-lg-mobile text-on-surface md:text-headline-lg xl:text-display max-w-5xl tracking-tight uppercase">
                {{ $blog->title }}
            </h1>

            @if ($blog->excerpt)
                <p class="mt-space-lg font-body-lg text-body-lg text-on-surface-variant max-w-4xl">{{ $blog->excerpt }}</p>
            @endif

            {{-- Meta row --}}
            <div
                class="mt-space-xl gap-space-md bg-surface-container-low p-space-lg flex flex-col items-start justify-between rounded-lg md:flex-row md:items-center"
            >
                <div class="gap-space-md flex items-center">
                    <x-website.avatar :user="$blog->author" size="h-12 w-12" text="font-headline-sm text-headline-sm" />
                    <div>
                        <span class="font-headline-sm text-headline-sm text-on-surface block font-medium">
                            {{ $blog->author?->name ?? 'Editorial Team' }}
                        </span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant tracking-wider uppercase">
                            Author • {{ \App\Helpers\Settings::appName() }}
                        </span>
                    </div>
                </div>

                <div class="gap-space-lg font-label-md text-label-md text-on-surface-variant flex flex-wrap items-center tracking-wider uppercase">
                    <time datetime="{{ $blog->published_date->toDateString() }}">{{ $blog->published_date->format('F d, Y') }}</time>
                    <span>{{ $blog->reading_time }} min read</span>
                </div>

                <div x-data="{ copied: false }" class="gap-space-xs flex items-center">
                    <button
                        type="button"
                        x-on:click="
                            navigator.clipboard?.writeText(window.location.href)
                            copied = true
                            setTimeout(() => (copied = false), 2000)
                        "
                        class="bg-surface-container-lowest font-label-sm text-label-sm text-on-surface hover:bg-surface-container inline-flex h-10 items-center justify-center gap-1.5 px-3 tracking-wider uppercase transition-colors"
                    >
                        <span x-text="copied ? 'Copied ✓' : 'Copy Link'">Copy Link</span>
                    </button>
                    <a
                        href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="bg-surface-container-lowest font-label-sm text-label-sm text-on-surface hover:bg-surface-container inline-flex h-10 items-center justify-center px-3 tracking-wider uppercase transition-colors"
                        aria-label="Share on X"
                    >
                        X
                    </a>
                    <a
                        href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="bg-surface-container-lowest font-label-sm text-label-sm text-on-surface hover:bg-surface-container inline-flex h-10 items-center justify-center px-3 tracking-wider uppercase transition-colors"
                        aria-label="Share on LinkedIn"
                    >
                        in
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Lead visual --}}
    @if ($blog->featured_image_url)
        <section class="bg-surface pt-space-xl w-full">
            <div class="site-container">
                <figure class="bg-surface-container-lowest p-space-xs overflow-hidden rounded-lg shadow-sm">
                    <img
                        src="{{ $blog->featured_image_url }}"
                        alt="{{ $blog->title }}"
                        class="h-[260px] w-full rounded object-cover sm:h-[420px] lg:h-[560px]"
                        fetchpriority="high"
                    />
                    <figcaption class="gap-space-sm p-space-md text-on-surface-variant flex flex-col justify-between md:flex-row md:items-center">
                        <span class="font-label-sm text-label-sm tracking-widest uppercase">{{ $blog->title }}</span>
                        @if ($primaryCategory)
                            <span class="font-label-sm text-label-sm text-outline shrink-0 tracking-wider uppercase">
                                {{ $primaryCategory->name }}
                            </span>
                        @endif
                    </figcaption>
                </figure>
            </div>
        </section>
    @endif

    {{-- Body: sticky sidebar + article --}}
    <section class="bg-surface py-space-xl w-full">
        <div class="site-container gap-gutter grid grid-cols-1 lg:grid-cols-12">
            <aside class="order-2 lg:order-1 lg:col-span-4">
                <div class="space-y-space-lg lg:sticky lg:top-28">
                    @if (count($toc))
                        <div
                            x-data="{
                                active: '{{ $toc[0]['id'] }}',
                                ids: @js(array_column($toc, 'id')),
                                update() {
                                    for (const id of [...this.ids].reverse()) {
                                        const el = document.getElementById(id)
                                        if (el && el.getBoundingClientRect().top < 160) {
                                            this.active = id
                                            return
                                        }
                                    }
                                    this.active = this.ids[0]
                                },
                            }"
                            x-init="update()"
                            x-on:scroll.window.passive="update()"
                            class="bg-surface-container-lowest p-space-lg rounded-lg shadow-sm"
                        >
                            <div class="mb-space-md pb-space-sm flex items-center justify-between">
                                <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Table of Contents</span>
                                <span class="font-label-sm text-label-sm text-primary">
                                    {{ count($toc) }} {{ Str::plural('Section', count($toc)) }}
                                </span>
                            </div>
                            <nav class="space-y-space-xs" aria-label="Table of contents">
                                @foreach ($toc as $item)
                                    <a
                                        href="#{{ $item['id'] }}"
                                        :class="active === '{{ $item['id'] }}' ? 'bg-surface-container text-on-surface' : 'text-on-surface-variant'"
                                        class="gap-space-sm px-space-xs font-label-md text-label-md hover:bg-surface-container hover:text-primary flex items-baseline py-2 tracking-wider uppercase transition-all"
                                    >
                                        <span :class="active === '{{ $item['id'] }}' ? 'text-primary' : 'text-outline'" class="font-semibold">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}.
                                        </span>
                                        <span>{{ $item['title'] }}</span>
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    @endif

                    <div class="bg-surface-container-lowest p-space-lg rounded-lg shadow-sm">
                        <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Article Details</span>
                        <dl class="mt-space-md gap-space-sm grid grid-cols-1">
                            <div class="bg-surface-container-low p-space-md flex items-baseline justify-between">
                                <dt class="font-label-sm text-label-sm text-outline tracking-wider uppercase">Published</dt>
                                <dd class="font-label-md text-label-md text-on-surface font-semibold">
                                    {{ $blog->published_date->format('M d, Y') }}
                                </dd>
                            </div>
                            <div class="bg-surface-container-low p-space-md flex items-baseline justify-between">
                                <dt class="font-label-sm text-label-sm text-outline tracking-wider uppercase">Reading Time</dt>
                                <dd class="font-label-md text-label-md text-on-surface font-semibold">{{ $blog->reading_time }} min</dd>
                            </div>
                            @if ($blog->updated_at && $blog->updated_at->gt($blog->published_date->copy()->addDay()))
                                <div class="bg-surface-container-low p-space-md flex items-baseline justify-between">
                                    <dt class="font-label-sm text-label-sm text-outline tracking-wider uppercase">Updated</dt>
                                    <dd class="font-label-md text-label-md text-on-surface font-semibold">
                                        {{ $blog->updated_at->format('M d, Y') }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="bg-inverse-surface p-space-lg text-inverse-on-surface rounded-lg">
                        <span class="font-label-sm text-label-sm tracking-widest text-[#A0A0A0] uppercase">Working on something similar?</span>
                        <p class="mt-space-sm font-body-md text-body-md text-[#D1D5DB]">
                            Our engineers can review your architecture and share what has worked for teams like yours.
                        </p>
                        <a
                            href="{{ route('contact') }}"
                            class="mt-space-md font-label-md text-label-md hover:text-primary-fixed-dim inline-flex items-center font-semibold tracking-wider text-white uppercase transition-colors"
                        >
                            Talk to an Engineer →
                        </a>
                    </div>
                </div>
            </aside>

            <div class="space-y-space-xl order-1 min-w-0 lg:order-2 lg:col-span-8">
                <article class="article-prose bg-surface-container-lowest p-space-lg md:p-space-xl rounded-lg shadow-sm">
                    {!! $content['html'] !!}
                </article>

                {{-- Author --}}
                <div class="bg-surface-container-lowest p-space-lg md:p-space-xl rounded-lg shadow-sm">
                    <div class="gap-space-lg flex flex-col items-start sm:flex-row">
                        <x-website.avatar :user="$blog->author" size="h-20 w-20" text="font-headline-md text-headline-md" />
                        <div class="space-y-space-xs">
                            <div class="gap-space-sm flex flex-wrap items-center">
                                <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold uppercase">
                                    {{ $blog->author?->name ?? 'Editorial Team' }}
                                </h2>
                                <span
                                    class="bg-surface-container font-label-sm text-label-sm text-primary px-2 py-0.5 font-semibold tracking-widest uppercase"
                                >
                                    Author
                                </span>
                            </div>
                            <p class="font-label-sm text-label-sm text-outline tracking-wider uppercase">{{ \App\Helpers\Settings::appName() }}</p>
                            <p class="pt-space-xs font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                {{ $blog->author?->bio ?: 'Writes about engineering, systems design and the craft of building reliable digital products.' }}
                            </p>
                            <div class="pt-space-sm">
                                <a
                                    href="{{ route('blog.index') }}"
                                    class="font-label-sm text-label-sm text-on-surface hover:text-primary tracking-wider uppercase transition-colors"
                                >
                                    More Insights →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="bg-surface-container-low py-space-2xl w-full">
            <div class="site-container">
                <div class="mb-space-xl gap-space-md flex flex-col justify-between md:flex-row md:items-end">
                    <div>
                        <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Related Reading</span>
                        <h2
                            class="mt-space-xs font-headline-lg-mobile text-headline-lg-mobile text-on-surface md:font-headline-lg md:text-headline-lg tracking-tight uppercase"
                        >
                            Further Reading
                        </h2>
                    </div>
                    <a
                        href="{{ route('blog.index') }}"
                        class="font-label-md text-label-md text-primary flex items-center gap-1 tracking-wider uppercase hover:underline"
                    >
                        Explore All Insights →
                    </a>
                </div>
                <div class="gap-gutter grid grid-cols-1 md:grid-cols-3">
                    @foreach ($related as $post)
                        @include('website.blog.partials.card', ['blog' => $post])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include(
        'website.blog.partials.cta',
        [
            'ctaTitle' => 'Discuss this architecture with our engineering team.',
        ]
    )
</x-website>
