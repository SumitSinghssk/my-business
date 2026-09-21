@php
    $appName = \App\Helpers\Settings::appName();
    $highlights = $service->highlights ?? [];
    $tags = $service->tags ?? [];
    $contactUrl = route("contact", ["service" => $service->slug]);
    $sideLabel = "font-label-sm text-label-sm text-outline tracking-widest uppercase";

    $serviceSchema = array_filter([
        "@context" => "https://schema.org",
        "@type" => "Service",
        "name" => $service->title,
        "description" => $service->excerpt,
        "url" => route("services.show", $service->slug),
        "image" => $service->featured_image_url,
        "serviceType" => $service->title,
        "provider" => ["@id" => url("/") . "#organization"],
        "hasOfferCatalog" => $highlights
            ? [
                "@type" => "OfferCatalog",
                "name" => $service->title,
                "itemListElement" => array_map(fn ($item) => ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => $item]], $highlights),
            ]
            : null,
    ]);
@endphp

<x-website
    :title="$service->seo?->meta_title ?: $service->title . ' Services | ' . $appName"
    :description="$service->seo?->meta_description ?: $service->excerpt"
    :image="$service->featured_image_url"
>
    @push("heads")
        <script type="application/ld+json">
            {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
        </script>
    @endpush

    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home and about heroes) --}}
        <section class="w-full border-b border-[#E1E5EA] pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20">
            <div class="site-container">
                <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
                    <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                        <a
                            href="{{ route("services") }}"
                            class="font-label-sm text-label-sm text-primary hover:text-primary-container mb-4 inline-flex items-center gap-1.5 self-start font-semibold tracking-widest uppercase transition-colors"
                        >
                            <span aria-hidden="true">←</span>
                            Services
                        </a>

                        <h1
                            class="sm:mb-space-lg font-display mb-5 text-[38px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[56px] lg:text-[52px] xl:text-[62px] 2xl:text-[68px]"
                        >
                            {{ $service->title }}
                        </h1>

                        @if ($service->excerpt)
                            <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">{{ $service->excerpt }}</p>
                        @endif

                        <div>
                            <a
                                href="{{ $contactUrl }}"
                                class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors sm:w-auto"
                            >
                                Discuss This Project →
                            </a>
                        </div>
                    </div>

                    @if ($service->featured_image_url)
                        <div class="relative aspect-16/10 w-full overflow-hidden rounded-lg">
                            <img
                                src="{{ $service->featured_image_url }}"
                                @if ($srcset = \App\Services\ImageProcessor::srcset($service->featured_image, "service"))
                                    srcset="{{ $srcset }}"
                                    sizes="(min-width: 1024px) 50vw, 100vw"
                                @endif
                                width="1600"
                                height="1000"
                                alt="{{ $service->title }}"
                                class="absolute inset-0 h-full w-full object-cover"
                                fetchpriority="high"
                            />
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Content + sticky summary --}}
        <section class="section-y w-full border-b border-[#E1E5EA]">
            <div class="site-container gap-section grid grid-cols-1 items-start lg:grid-cols-12">
                <article class="article-prose is-plain min-w-0 rounded-lg border border-[#E1E5EA] bg-white p-4 shadow-sm md:p-6 lg:col-span-8 lg:p-8">
                    {!! $service->content !!}
                </article>

                <aside class="lg:sticky lg:top-24 lg:col-span-4">
                    <div class="divide-y divide-[#E1E5EA] rounded-lg border border-[#E1E5EA] bg-white shadow-sm">
                        @if ($highlights)
                            <div class="p-4 lg:p-5">
                                <span class="{{ $sideLabel }}">What's included</span>
                                <ul class="mt-3 space-y-2.5">
                                    @foreach ($highlights as $point)
                                        <li class="font-body-sm text-body-sm text-on-surface flex items-start gap-2.5">
                                            <x-icons.check
                                                class="text-primary-container mt-0.5 h-4 w-4 shrink-0"
                                                aria-hidden="true"
                                                stroke-width="2.25"
                                            />
                                            <span>{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($tags)
                            <div class="p-4 lg:p-5">
                                <span class="{{ $sideLabel }}">Technologies</span>
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($tags as $tag)
                                        <span
                                            class="font-label-sm rounded border border-[#E1E5EA] bg-[#F7F8FA] px-2 py-0.5 text-xs tracking-wider text-[#434655] uppercase"
                                        >
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="p-4 lg:p-5">
                            <a
                                href="{{ $contactUrl }}"
                                class="font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-[#0A0A0A] px-4 py-3 tracking-wider text-white uppercase transition-colors"
                            >
                                Discuss This Project →
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <x-website.faq :title="'Questions about ' . $service->title . '.'" class="bg-white" />

        @if ($projects->isNotEmpty())
            <section class="section-y w-full border-b border-[#E1E5EA]">
                <div class="site-container">
                    <div class="section-head flex items-end justify-between gap-4">
                        <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                            Related Work
                        </h2>
                        <a
                            href="{{ route("work.index", ["service" => $service->slug]) }}"
                            class="font-label-md text-label-md hover:text-primary inline-flex shrink-0 items-center gap-1 border-b border-[#0A0A0A] pb-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                        >
                            All {{ $service->title }} Work →
                        </a>
                    </div>
                    <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($projects as $project)
                            @include("website.work.partials.card", ["project" => $project])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($others->isNotEmpty())
            <section class="section-y w-full bg-white">
                <div class="site-container">
                    <div class="section-head flex items-end justify-between gap-4">
                        <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                            Other Services
                        </h2>
                        <a
                            href="{{ route("services") }}"
                            class="font-label-md text-label-md hover:text-primary inline-flex shrink-0 items-center gap-1 border-b border-[#0A0A0A] pb-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                        >
                            All Services →
                        </a>
                    </div>
                    <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($others->take(3) as $other)
                            @include("website.services.partials.card", ["service" => $other])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-website>
