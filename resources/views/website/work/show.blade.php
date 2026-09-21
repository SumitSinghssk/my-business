@php
    $appName = \App\Helpers\Settings::appName();
    $results = $project->results ?? [];
    $tags = $project->tags ?? [];
    $service = $project->service?->status === \App\Enums\CommonStatusEnum::ACTIVE ? $project->service : null;
    $contactUrl = route("contact", array_filter(["service" => $service?->slug]));
    $sideLabel = "font-label-sm text-label-sm text-outline tracking-widest uppercase";

    $details = array_filter([
        "Client" => $project->client,
        "Industry" => $project->industry,
        "Year" => $project->year,
    ]);

    $projectSchema = array_filter([
        "@context" => "https://schema.org",
        "@type" => "CreativeWork",
        "name" => $project->title,
        "headline" => $project->title,
        "description" => $project->excerpt,
        "url" => route("work.show", $project->slug),
        "image" => $project->featured_image_url,
        "dateCreated" => $project->year,
        "dateModified" => $project->updated_at?->toAtomString(),
        "genre" => $project->industry,
        "keywords" => $tags ? implode(", ", $tags) : null,
        "about" => $service ? ["@type" => "Service", "name" => $service->title, "url" => route("services.show", $service->slug)] : null,
        "sourceOrganization" => $project->client ? ["@type" => "Organization", "name" => $project->client] : null,
        "creator" => ["@id" => url("/") . "#organization"],
    ]);
@endphp

<x-website
    :title="$project->seo?->meta_title ?: $project->title . ($project->industry ? ' – ' . $project->industry . ' Case Study' : ' Case Study') . ' | ' . $appName"
    :description="$project->seo?->meta_description ?: $project->excerpt"
    :image="$project->featured_image_url"
    og-type="article"
>
    @push("heads")
        <script type="application/ld+json">
            {!! json_encode($projectSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
        </script>
    @endpush

    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home and about heroes) --}}
        <section class="w-full border-b border-[#E1E5EA] pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20">
            <div class="site-container">
                <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
                    <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                        <a
                            href="{{ route("work.index") }}"
                            class="font-label-sm text-label-sm text-primary hover:text-primary-container mb-4 inline-flex items-center gap-1.5 self-start font-semibold tracking-widest uppercase transition-colors"
                        >
                            <span aria-hidden="true">←</span>
                            Work
                        </a>

                        @if ($project->meta_label)
                            <span class="font-label-sm text-label-sm text-secondary mb-3 block tracking-widest uppercase">
                                {{ $project->meta_label }}
                            </span>
                        @endif

                        <h1
                            class="sm:mb-space-lg font-display mb-5 text-[38px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[56px] lg:text-[52px] xl:text-[62px] 2xl:text-[68px]"
                        >
                            {{ $project->title }}
                        </h1>

                        @if ($project->excerpt)
                            <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">{{ $project->excerpt }}</p>
                        @endif

                        <div class="sm:gap-space-md flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                            <a
                                href="{{ $contactUrl }}"
                                class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors sm:w-auto"
                            >
                                Start a Similar Project →
                            </a>
                            @if ($project->project_url)
                                <a
                                    href="{{ $project->project_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="px-space-xl font-label-md text-label-md inline-flex w-full items-center justify-center border border-[#E1E5EA] bg-white py-4 tracking-wider text-[#0A0A0A] uppercase transition-all hover:border-[#0A0A0A] sm:w-auto"
                                >
                                    Visit Live Site ↗
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($project->featured_image_url)
                        <div class="relative aspect-16/10 w-full overflow-hidden rounded-lg">
                            <img
                                src="{{ $project->featured_image_url }}"
                                @if ($srcset = \App\Services\ImageProcessor::srcset($project->featured_image, "project"))
                                    srcset="{{ $srcset }}"
                                    sizes="(min-width: 1024px) 50vw, 100vw"
                                @endif
                                width="1600"
                                height="1000"
                                alt="{{ $project->title }}{{ $project->industry ? " – " . $project->industry . " case study" : "" }}"
                                class="absolute inset-0 h-full w-full object-cover"
                                fetchpriority="high"
                            />
                        </div>
                    @endif
                </div>

                {{-- Key results --}}
                @if ($results)
                    <ul
                        @class([
                            "mt-10 grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-[#E1E5EA] bg-[#E1E5EA] md:mt-14 lg:mt-16",
                            "lg:grid-cols-4" => count($results) === 4,
                            "lg:grid-cols-3" => count($results) === 3,
                        ])
                        aria-label="Key results"
                    >
                        @foreach ($results as $result)
                            <li class="flex flex-col gap-1 bg-white p-4 lg:p-5">
                                <span
                                    class="{{ $loop->first ? "text-primary" : "text-[#0A0A0A]" }} font-display text-[28px] leading-tight font-semibold tracking-[-0.03em] md:text-[34px] lg:text-[40px]"
                                >
                                    {{ $result["value"] }}
                                </span>
                                @if (filled($result["label"] ?? null))
                                    <span class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">{{ $result["label"] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        {{-- Case study + sticky project details --}}
        <section class="section-y w-full border-b border-[#E1E5EA]">
            <div class="site-container gap-section grid grid-cols-1 items-start lg:grid-cols-12">
                <article class="article-prose is-plain min-w-0 rounded-lg border border-[#E1E5EA] bg-white p-4 shadow-sm md:p-6 lg:col-span-8 lg:p-8">
                    {!! $project->content !!}
                </article>

                <aside class="lg:sticky lg:top-24 lg:col-span-4">
                    <div class="divide-y divide-[#E1E5EA] rounded-lg border border-[#E1E5EA] bg-white shadow-sm">
                        @if ($details || $service || $project->project_url)
                            <div class="p-4 lg:p-5">
                                <span class="{{ $sideLabel }}">Project details</span>
                                <dl class="mt-3 space-y-3">
                                    @foreach ($details as $label => $value)
                                        <div class="flex items-baseline justify-between gap-4">
                                            <dt class="font-body-sm text-body-sm text-secondary">{{ $label }}</dt>
                                            <dd class="font-body-sm text-body-sm text-right font-semibold text-[#0A0A0A]">{{ $value }}</dd>
                                        </div>
                                    @endforeach

                                    @if ($service)
                                        <div class="flex items-baseline justify-between gap-4">
                                            <dt class="font-body-sm text-body-sm text-secondary">Service</dt>
                                            <dd class="font-body-sm text-body-sm text-right font-semibold">
                                                <a
                                                    href="{{ route("services.show", $service->slug) }}"
                                                    class="text-primary hover:text-primary-container transition-colors"
                                                >
                                                    {{ $service->title }}
                                                </a>
                                            </dd>
                                        </div>
                                    @endif

                                    @if ($project->project_url)
                                        <div class="flex items-baseline justify-between gap-4">
                                            <dt class="font-body-sm text-body-sm text-secondary">Website</dt>
                                            <dd class="font-body-sm text-body-sm min-w-0 truncate text-right font-semibold">
                                                <a
                                                    href="{{ $project->project_url }}"
                                                    target="_blank"
                                                    rel="noopener"
                                                    class="text-primary hover:text-primary-container transition-colors"
                                                >
                                                    {{ preg_replace("#^https?://(www\.)?#", "", rtrim($project->project_url, "/")) }} ↗
                                                </a>
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
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
                                Start a Similar Project →
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <x-website.faq />

        @if ($more->isNotEmpty())
            <section class="section-y w-full bg-white">
                <div class="site-container">
                    <div class="section-head flex items-end justify-between gap-4">
                        <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">More Work</h2>
                        <a
                            href="{{ route("work.index") }}"
                            class="font-label-md text-label-md hover:text-primary inline-flex shrink-0 items-center gap-1 border-b border-[#0A0A0A] pb-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                        >
                            All Projects →
                        </a>
                    </div>
                    <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($more as $other)
                            @include("website.work.partials.card", ["project" => $other])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-website>
