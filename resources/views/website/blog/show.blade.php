@php
    $primaryCategory = $blog->categories->first();
    $shareUrl = urlencode(route("blog.show", $blog->slug));
    $shareTitle = urlencode($blog->title);
    $toc = $content["toc"];
    $authorName = $blog->author?->name ?? $appName;
    $wasUpdated = $blog->updated_at && $blog->updated_at->gt($blog->published_date->copy()->addDay());

    $shareButton = "bg-surface-container-low font-label-sm text-label-sm text-on-surface hover:bg-surface-container inline-flex h-9 flex-1 items-center justify-center px-3 tracking-wider uppercase transition-colors";
@endphp

<x-website
    :title="$blog->seo?->meta_title ?: (mb_strlen($blog->title) > 55 ? $blog->title : $blog->title . ' | ' . $appName)"
    :description="$blog->seo?->meta_description ?: $blog->excerpt"
    :image="$blog->featured_image_url"
    og-type="article"
>
    @push("heads")
        <x-website.json-ld :data="\App\Support\StructuredData::blogPosting($blog, $authorName)" />
        <meta property="article:published_time" content="{{ $blog->published_date->toAtomString() }}" />
        @foreach ($blog->categories as $category)
            <meta property="article:tag" content="{{ $category->name }}" />
        @endforeach
    @endpush

    <section
        x-data="{
            active: @js($toc[0]["id"] ?? null),
            ids: @js(array_column($toc, "id")),
            update() {
                for (const id of [...this.ids].reverse()) {
                    const el = document.getElementById(id)
                    if (el && el.getBoundingClientRect().top < 160) {
                        this.active = id
                        return
                    }
                }
                this.active = this.ids[0] ?? null
            },
        }"
        x-init="update()"
        x-on:scroll.window.passive="update()"
        class="bg-surface py-space-xl w-full"
    >
        <div class="site-container gap-gutter grid grid-cols-1 lg:grid-cols-12">
            {{-- Left: the article --}}
            <div class="min-w-0 lg:col-span-8">
                <header>
                    @if ($blog->categories->isNotEmpty())
                        <div class="mb-space-md flex flex-wrap gap-2">
                            @foreach ($blog->categories as $category)
                                <a
                                    href="{{ route("blog.index", ["category" => $category->slug]) }}"
                                    class="bg-surface-container font-label-sm text-label-sm text-primary hover:bg-surface-container-high inline-block px-3 py-1 font-semibold tracking-widest uppercase transition-colors"
                                >
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <h1 class="font-display text-headline-lg-mobile text-on-surface md:text-headline-lg leading-tight tracking-tight">
                        {{ $blog->title }}
                    </h1>

                    @if ($blog->excerpt)
                        <p class="mt-space-md font-body-lg text-body-lg text-on-surface-variant">{{ $blog->excerpt }}</p>
                    @endif

                    <div
                        class="mt-space-md font-label-sm text-label-sm text-secondary flex flex-wrap items-center gap-x-3 gap-y-1 tracking-wider uppercase"
                    >
                        <span class="text-on-surface font-semibold">{{ $authorName }}</span>
                        <span aria-hidden="true">•</span>
                        <time datetime="{{ $blog->published_date->toDateString() }}">{{ $blog->published_date->format("M d, Y") }}</time>
                        <span aria-hidden="true">•</span>
                        <span>{{ $blog->reading_time }} min read</span>
                        @if ($wasUpdated)
                            <span aria-hidden="true">•</span>
                            <span>Updated {{ $blog->updated_at->format("M d, Y") }}</span>
                        @endif
                    </div>
                </header>

                @if ($blog->featured_image_url)
                    <figure class="mt-space-lg bg-surface-container-lowest overflow-hidden rounded-lg">
                        <img
                            src="{{ $blog->featured_image_url }}"
                            @if ($srcset = \App\Services\ImageProcessor::srcset($blog->featured_image, "blog"))
                                srcset="{{ $srcset }}"
                                sizes="(min-width: 1024px) 66vw, 100vw"
                            @endif
                            alt="{{ $blog->title }}"
                            width="1600"
                            height="1000"
                            class="aspect-16/10 w-full object-cover"
                            fetchpriority="high"
                        />
                    </figure>
                @endif

                {{-- Contents for small screens (the sidebar version is desktop-only) --}}
                @if (count($toc))
                    <details class="mt-space-lg bg-surface-container-lowest group border-line rounded-lg border lg:hidden">
                        <summary
                            class="font-label-sm text-label-sm text-on-surface flex cursor-pointer list-none items-center justify-between px-4 py-3 font-semibold tracking-widest uppercase"
                        >
                            Table of Contents
                            <span class="text-outline transition-transform group-open:rotate-180" aria-hidden="true">▾</span>
                        </summary>
                        <div class="border-line border-t p-2">
                            @include("website.blog.partials.toc")
                        </div>
                    </details>
                @endif

                <article class="article-prose bg-surface-container-lowest mt-space-lg rounded-lg p-4 shadow-sm md:p-6">
                    {!! $content["html"] !!}
                </article>
            </div>

            @include("website.blog.partials.sidebar")
        </div>
    </section>

    <x-website.faq />

    @if ($related->isNotEmpty())
        <section class="bg-surface-container-low py-space-2xl w-full">
            <div class="site-container">
                <div class="mb-space-lg flex items-end justify-between gap-4">
                    <h2
                        class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface md:font-headline-lg md:text-headline-lg tracking-tight"
                    >
                        Related Articles
                    </h2>
                    <a
                        href="{{ route("blog.index") }}"
                        class="font-label-md text-label-md text-primary shrink-0 tracking-wider uppercase hover:underline"
                    >
                        All Insights →
                    </a>
                </div>
                <div class="gap-gutter grid grid-cols-1 md:grid-cols-3">
                    @foreach ($related as $post)
                        @include("website.blog.partials.card", ["blog" => $post])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-website>
