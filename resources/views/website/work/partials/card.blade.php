{{-- Project (case study) card. Expects: $project (App\Models\Project); optional $eager for the first, above-the-fold image and $heading (h2 when the card sits directly under the page h1). --}}
@php($url = route("work.show", $project->slug))
@php($heading = $heading ?? "h3")

<article
    class="group hover:border-primary-container border-line relative flex flex-col overflow-hidden rounded-lg border bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
>
    <div class="bg-surface-container-low border-line relative aspect-16/10 overflow-hidden border-b">
        @if ($project->featured_image_url)
            <img
                src="{{ $project->featured_image_url }}"
                @if ($srcset = \App\Services\ImageProcessor::srcset($project->featured_image, "project"))
                    srcset="{{ $srcset }}"
                    sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                @endif
                width="1600"
                height="1000"
                alt="{{ $project->title }}{{ $project->industry ? " – " . $project->industry . " case study" : "" }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="{{ $eager ?? false ? "eager" : "lazy" }}"
                @if ($priority ?? false) fetchpriority="high" @endif
                decoding="async"
            />
        @endif
    </div>

    <div class="flex flex-1 flex-col justify-between p-4">
        <div class="space-y-2">
            @if ($project->meta_label)
                <span class="font-label-sm text-primary block text-[11px] font-semibold tracking-widest uppercase">{{ $project->meta_label }}</span>
            @endif

            <{{ $heading }} class="text-on-surface group-hover:text-primary-container text-[17px] leading-snug font-semibold transition-colors">
                <a href="{{ $url }}" class="after:absolute after:inset-0">{{ $project->title }}</a>
            </{{ $heading }}>
            @if ($project->excerpt)
                <p class="text-secondary line-clamp-2 text-sm leading-relaxed">{{ $project->excerpt }}</p>
            @endif

            @if ($project->tags)
                <div class="flex flex-wrap gap-1.5 pt-1">
                    @foreach (array_slice($project->tags, 0, 4) as $tag)
                        <span
                            class="font-label-sm border-line text-on-surface-variant rounded border bg-white px-2 py-0.5 text-[10px] tracking-wider uppercase"
                        >
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div
            class="font-label-sm text-on-surface border-line mt-3 flex items-center justify-between border-t pt-3 text-[11px] font-semibold tracking-wider uppercase"
        >
            <span class="group-hover:text-primary-container transition-colors">View Case Study</span>
            <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
        </div>
    </div>
</article>
