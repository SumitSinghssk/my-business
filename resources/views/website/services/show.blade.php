@php
    $highlights = $service->highlights ?? [];
    $tags = $service->tags ?? [];
    $contactUrl = route("contact", ["service" => $service->slug]);
    $sideLabel = "font-label-sm text-label-sm text-outline tracking-widest uppercase";
@endphp

<x-website
    :title="$service->seo?->meta_title ?: $service->title . ' Services | ' . $appName"
    :description="$service->seo?->meta_description ?: $service->excerpt"
    :image="$service->featured_image_url"
>
    @push("heads")
        <x-website.json-ld :data="\App\Support\StructuredData::service($service)" />
    @endpush

    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home and about heroes) --}}
        <x-website.page-hero
            :back="['url' => route('services'), 'label' => 'Services']"
            title-class="text-[38px] sm:text-[56px] lg:text-[52px] xl:text-[62px] 2xl:text-[68px]"
        >
            <x-slot:title>{{ $service->title }}</x-slot>

            <x-slot:text>{{ $service->excerpt }}</x-slot>

            <x-slot:actions>
                <x-website.button :href="$contactUrl">Discuss This Project →</x-website.button>
            </x-slot>

            <x-slot:media>
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
            </x-slot>
        </x-website.page-hero>

        @include("website.services.sections.content")

        <x-website.faq :title="'Questions about ' . $service->title . '.'" class="bg-white" />

        @if ($projects->isNotEmpty())
            <section class="section-y border-line w-full border-b">
                <div class="site-container">
                    <x-website.section-heading title="Related Work" split>
                        <x-website.text-link :href="route('work.index', ['service' => $service->slug])">
                            All {{ $service->title }} Work →
                        </x-website.text-link>
                    </x-website.section-heading>
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
                    <x-website.section-heading title="Other Services" split>
                        <x-website.text-link :href="route('services')">All Services →</x-website.text-link>
                    </x-website.section-heading>
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
