@php
    $toc = $content['toc'];
    $updated = $page->updated_at ?? ($page->published_at ?? $page->created_at);
@endphp

<x-website
    :title="$page->seo?->meta_title ?: $page->title . ' | ' . \App\Helpers\Settings::appName()"
    :description="$page->seo?->meta_description ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $page->content))), 155)"
    :image="$page->featured_image_url"
>
    {{-- Header --}}
    <section class="bg-surface-container-lowest w-full border-b border-[#E1E5EA]">
        <div class="site-container py-space-xl">
            <div class="gap-gutter grid grid-cols-1 items-end lg:grid-cols-12">
                <h1
                    class="font-display text-headline-lg-mobile text-on-surface md:text-headline-lg xl:text-display leading-[1.05] tracking-tight lg:col-span-8"
                >
                    {{ $page->title }}
                </h1>
                <div class="gap-space-sm lg:pl-gutter flex flex-col lg:col-span-4 lg:border-l lg:border-[#E1E5EA]">
                    <div class="font-label-sm text-label-sm text-secondary flex items-center justify-between tracking-wider uppercase">
                        <span>Last Updated</span>
                        <time datetime="{{ $updated->toDateString() }}" class="text-on-surface font-semibold">
                            {{ $updated->format('F d, Y') }}
                        </time>
                    </div>
                    @if (count($toc))
                        <div class="font-label-sm text-label-sm text-secondary flex items-center justify-between tracking-wider uppercase">
                            <span>Sections</span>
                            <span class="text-on-surface font-semibold">{{ count($toc) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($page->featured_image_url)
        <section class="pt-space-xl w-full">
            <div class="site-container">
                <img
                    src="{{ $page->featured_image_url }}"
                    alt="{{ $page->title }}"
                    width="1600"
                    height="600"
                    class="aspect-8/3 w-full rounded-lg border border-[#E1E5EA] object-cover"
                />
            </div>
        </section>
    @endif

    {{-- Body --}}
    <section class="py-space-xl w-full">
        <div class="site-container gap-gutter grid grid-cols-1 lg:grid-cols-12">
            @if (count($toc))
                <aside class="lg:col-span-4">
                    <div
                        x-data="{
                            active: '{{ $toc[0]['id'] }}',
                            ids: @js(array_column($toc, 'id')),
                            update() {
                                for (const id of [...this.ids].reverse()) {
                                    const el = document.getElementById(id)
                                    if (el && el.getBoundingClientRect().top < 160) {
                                        this.active = id
                                        return
                                    }
                                }
                                this.active = this.ids[0]
                            },
                        }"
                        x-init="update()"
                        x-on:scroll.window.passive="update()"
                        class="bg-surface-container-lowest rounded-lg p-4 shadow-sm lg:sticky lg:top-28"
                    >
                        <span class="mb-space-md font-label-sm text-label-sm text-outline block tracking-widest uppercase">On this page</span>
                        <nav class="space-y-space-xs" aria-label="Table of contents">
                            @foreach ($toc as $item)
                                <a
                                    href="#{{ $item['id'] }}"
                                    :class="active === '{{ $item['id'] }}' ? 'bg-surface-container text-on-surface' : 'text-on-surface-variant'"
                                    class="gap-space-sm px-space-xs font-label-md text-label-md hover:bg-surface-container hover:text-primary flex items-baseline py-2 tracking-wider uppercase transition-all"
                                >
                                    <span :class="active === '{{ $item['id'] }}' ? 'text-primary' : 'text-outline'" class="font-semibold">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}.
                                    </span>
                                    <span>{{ $item['title'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>
            @endif

            <article
                class="{{ count($toc) ? 'lg:col-span-8' : 'lg:col-span-10 lg:col-start-2' }} article-prose is-plain bg-surface-container-lowest min-w-0 rounded-lg p-4 shadow-sm md:p-6"
            >
                {!! $content['html'] !!}
            </article>
        </div>
    </section>

    <x-website.faq class="bg-white" />

    @include(
        'website.blog.partials.cta',
        [
            'ctaTitle' => 'Questions about this page?',
            'ctaText' => 'Get in touch and our team will be happy to help.',
        ]
    )
</x-website>
