{{-- Blog post card. Expects: $blog (App\Models\Blog with categories loaded); optional $heading (h2 when the card sits directly under the page h1) and $eager (above-the-fold image). --}}
@php
    $heading = $heading ?? "h3";
    $category = $blog->categories->first();
    $url = route("blog.show", $blog->slug);
@endphp

<article
    class="group hover:border-primary-container border-line relative flex flex-col overflow-hidden rounded-lg border bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
>
    <div class="border-line bg-ink relative aspect-16/10 overflow-hidden border-b">
        @if ($blog->featured_image_url)
            <img
                src="{{ $blog->featured_image_url }}"
                @if ($srcset = \App\Services\ImageProcessor::srcset($blog->featured_image, "blog"))
                    srcset="{{ $srcset }}"
                    sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                @endif
                width="1600"
                height="1000"
                alt="{{ $blog->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="{{ $eager ?? false ? "eager" : "lazy" }}"
                @if ($priority ?? false) fetchpriority="high" @endif
                decoding="async"
            />
        @else
            <div
                class="flex h-full w-full items-center justify-center bg-[linear-gradient(to_right,#1C1C1C_1px,transparent_1px),linear-gradient(to_bottom,#1C1C1C_1px,transparent_1px)] bg-size-[2rem_2rem]"
            >
                <span class="font-label-sm text-label-sm text-dark-subtle tracking-widest uppercase">{{ $category?->name ?? "Insight" }}</span>
            </div>
        @endif

        @if ($category)
            <span
                class="font-label-sm bg-ink/90 absolute top-2.5 left-2.5 px-2 py-0.5 text-[10px] tracking-wider text-white uppercase backdrop-blur-sm"
            >
                {{ $category->name }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col justify-between p-4">
        <div class="space-y-2">
            <div class="font-label-sm text-secondary flex items-center justify-between gap-2 text-[11px] tracking-wider uppercase">
                <time datetime="{{ $blog->published_date->toDateString() }}">{{ $blog->published_date->format("M d, Y") }}</time>
                <span>{{ $blog->reading_time }} min read</span>
            </div>
            <{{ $heading }}
                class="text-on-surface group-hover:text-primary-container line-clamp-2 text-[17px] leading-snug font-semibold transition-colors"
            >
                <a href="{{ $url }}" class="after:absolute after:inset-0">{{ $blog->title }}</a>
            </{{ $heading }}>
            @if ($blog->excerpt)
                <p class="text-secondary line-clamp-2 text-sm leading-relaxed">{{ $blog->excerpt }}</p>
            @endif
        </div>

        <div
            class="font-label-sm text-on-surface border-line mt-3 flex items-center justify-between border-t pt-3 text-[11px] font-semibold tracking-wider uppercase"
        >
            <span class="group-hover:text-primary-container transition-colors">Read Article</span>
            <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
        </div>
    </div>
</article>
