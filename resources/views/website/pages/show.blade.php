<x-website
    :title="$page->seo?->meta_title ?: $page->title . ' | ' . $appName"
    :description="$page->seo?->meta_description ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($page->content), ENT_QUOTES | ENT_HTML5))), 155)"
>
    <section class="py-space-xl w-full">
        <div class="site-container">
            <header class="mb-6 md:mb-8">
                <h1 class="section-title">{{ $page->title }}</h1>
                @if ($page->updated_at)
                    <p class="font-body-sm text-body-sm text-secondary mt-2">
                        Last updated
                        <time datetime="{{ $page->updated_at->toDateString() }}">{{ $page->updated_at->format('F j, Y') }}</time>
                    </p>
                @endif
            </header>

            <div class="article-prose is-plain bg-surface-container-lowest min-w-0 rounded-lg p-4 shadow-sm md:p-6">
                {!! $content['html'] !!}
            </div>
        </div>
    </section>

    <x-website.faq class="bg-white" />
</x-website>
