{{-- Service card. Expects: $service (App\Models\Service) --}}
@php($url = route("services.show", $service->slug))

<article
    class="group hover:border-primary-container relative flex flex-col overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
>
    <div class="relative aspect-16/10 overflow-hidden border-b border-[#E1E5EA] bg-[#0A0A0A]">
        @if ($service->featured_image_url)
            <img
                src="{{ $service->featured_image_url }}"
                @if ($srcset = \App\Services\ImageProcessor::srcset($service->featured_image, "service"))
                    srcset="{{ $srcset }}"
                    sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                @endif
                width="1600"
                height="1000"
                alt="{{ $service->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
                decoding="async"
            />
        @endif
    </div>

    <div class="flex flex-1 flex-col justify-between p-4">
        <div class="space-y-2">
            <h3 class="text-on-surface group-hover:text-primary-container text-[17px] leading-snug font-semibold transition-colors">
                <a href="{{ $url }}" class="after:absolute after:inset-0">{{ $service->title }}</a>
            </h3>
            @if ($service->excerpt)
                <p class="text-secondary line-clamp-2 text-sm leading-relaxed">{{ $service->excerpt }}</p>
            @endif
        </div>

        <div
            class="font-label-sm text-on-surface mt-3 flex items-center justify-between border-t border-[#E1E5EA] pt-3 text-[11px] font-semibold tracking-wider uppercase"
        >
            <span class="group-hover:text-primary-container transition-colors">View Service</span>
            <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
        </div>
    </div>
</article>
