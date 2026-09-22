@php
    $results = $project->results ?? [];
    $tags = $project->tags ?? [];
    $contactUrl = route("contact", array_filter(["service" => $service?->slug]));
    $sideLabel = "font-label-sm text-label-sm text-outline tracking-widest uppercase";

    $details = array_filter([
        "Client" => $project->client,
        "Industry" => $project->industry,
        "Year" => $project->year,
    ]);
@endphp

<x-website
    :title="$project->seo?->meta_title ?: $project->title . ($project->industry ? ' – ' . $project->industry . ' Case Study' : ' Case Study') . ' | ' . $appName"
    :description="$project->seo?->meta_description ?: $project->excerpt"
    :image="$project->featured_image_url"
    og-type="article"
>
    @push("heads")
        <x-website.json-ld :data="\App\Support\StructuredData::project($project, $service)" />
    @endpush

    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home and about heroes) --}}
        <x-website.page-hero
            :back="['url' => route('work.index'), 'label' => 'Work']"
            title-class="text-[38px] sm:text-[56px] lg:text-[52px] xl:text-[62px] 2xl:text-[68px]"
        >
            <x-slot:before-title>
                @if ($project->meta_label)
                    <span class="font-label-sm text-label-sm text-secondary mb-3 block tracking-widest uppercase">{{ $project->meta_label }}</span>
                @endif
            </x-slot>

            <x-slot:title>{{ $project->title }}</x-slot>

            <x-slot:text>{{ $project->excerpt }}</x-slot>

            <x-slot:actions>
                <x-website.button :href="$contactUrl">Start a Similar Project →</x-website.button>
                @if ($project->project_url)
                    <x-website.button :href="$project->project_url" variant="outline" target="_blank" rel="noopener">
                        Visit Live Site ↗
                    </x-website.button>
                @endif
            </x-slot>

            <x-slot:media>
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
            </x-slot>

            {{-- Key results --}}
            @if ($results)
                <ul
                    @class([
                        "border-line bg-line mt-10 grid grid-cols-2 gap-px overflow-hidden rounded-lg border md:mt-14 lg:mt-16",
                        "lg:grid-cols-4" => count($results) === 4,
                        "lg:grid-cols-3" => count($results) === 3,
                    ])
                    aria-label="Key results"
                >
                    @foreach ($results as $result)
                        <li class="flex flex-col gap-1 bg-white p-4 lg:p-5">
                            <span
                                class="{{ $loop->first ? "text-primary" : "text-ink" }} font-display text-[28px] leading-tight font-semibold tracking-[-0.03em] md:text-[34px] lg:text-[40px]"
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
        </x-website.page-hero>

        @include("website.work.sections.case-study")

        <x-website.faq />

        @if ($more->isNotEmpty())
            <section class="section-y w-full bg-white">
                <div class="site-container">
                    <x-website.section-heading title="More Work" split>
                        <x-website.text-link :href="route('work.index')">All Projects →</x-website.text-link>
                    </x-website.section-heading>
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
