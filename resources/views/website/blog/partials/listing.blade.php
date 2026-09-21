{{-- Blog grid + pagination. Rendered inside the listing page and returned alone when a category tab is clicked. --}}
@if ($activeCategory?->description)
    <p class="mb-space-lg font-body-md text-body-md text-secondary max-w-3xl">{{ $activeCategory->description }}</p>
@endif

@if ($blogs->isNotEmpty())
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($blogs as $blog)
            @include('website.blog.partials.card', ['blog' => $blog, 'heading' => 'h2', 'eager' => $loop->index < 3 && $blogs->onFirstPage(), 'priority' => $loop->first && $blogs->onFirstPage()])
        @endforeach
    </div>
@else
    <div class="px-space-lg py-space-2xl rounded-lg border border-dashed border-[#C3C6D8] bg-white text-center">
        <p class="font-headline-sm text-headline-sm text-on-surface font-semibold">No articles published yet.</p>
    </div>
@endif

@if ($blogs->hasPages())
    <div class="pt-space-xl" data-blog-pagination>
        {{ $blogs->onEachSide(1)->links('website.partials.pagination') }}
    </div>
@endif
