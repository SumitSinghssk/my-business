{{-- Blog post card. Expects: $blog (App\Models\Blog with categories loaded) --}}
@php
    $category = $blog->categories->first();
    $url = route('blog.show', $blog->slug);
@endphp

<article
    class="group hover:border-primary-container relative flex flex-col overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
>
    <div class="relative aspect-16/10 overflow-hidden border-b border-[#E1E5EA] bg-[#0A0A0A]">
        @if ($blog->featured_image_url)
            <img
                src="{{ $blog->featured_image_url }}"
                alt="{{ $blog->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            />
        @else
            <div
                class="flex h-full w-full items-center justify-center bg-[linear-gradient(to_right,#1C1C1C_1px,transparent_1px),linear-gradient(to_bottom,#1C1C1C_1px,transparent_1px)] bg-size-[2rem_2rem]"
            >
                <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">{{ $category?->name ?? 'Insight' }}</span>
            </div>
        @endif

        @if ($category)
            <span
                class="top-space-sm left-space-sm font-label-sm text-label-sm absolute bg-[#0A0A0A]/90 px-2 py-1 tracking-wider text-white uppercase backdrop-blur-sm"
            >
                {{ $category->name }}
            </span>
        @endif
    </div>

    <div class="p-space-lg flex flex-1 flex-col justify-between">
        <div class="space-y-space-sm">
            <div class="gap-space-sm font-label-sm text-label-sm text-secondary flex items-center justify-between tracking-widest uppercase">
                <time datetime="{{ $blog->published_date->toDateString() }}">{{ $blog->published_date->format('M d, Y') }}</time>
                <span>{{ $blog->reading_time }} min read</span>
            </div>
            <h3
                class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary-container leading-snug font-semibold transition-colors"
            >
                <a href="{{ $url }}" class="after:absolute after:inset-0">{{ $blog->title }}</a>
            </h3>
            @if ($blog->excerpt)
                <p class="font-body-sm text-body-sm text-secondary line-clamp-3">{{ $blog->excerpt }}</p>
            @endif
        </div>

        <div
            class="mt-space-md pt-space-md font-label-sm text-label-sm text-on-surface flex items-center justify-between border-t border-[#E1E5EA] font-semibold tracking-wider uppercase"
        >
            <span class="group-hover:text-primary-container transition-colors">Read Article</span>
            <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
        </div>
    </div>
</article>
