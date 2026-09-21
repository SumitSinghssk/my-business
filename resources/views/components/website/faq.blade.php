{{--
    FAQ section + FAQPage structured data for the current page.
    
    The questions come from the page's Admin → SEO record (FAQs tab), matched by
    URL path ("/" for home, "services", "services/web-applications"…), so every
    page's FAQs are managed in one place. Renders nothing when there are none.
    
    Usage: <x-website.faq />  or  <x-website.faq title="Questions before we talk?" text="…" class="bg-white" />
--}}

@props([
    'title' => 'Frequently asked questions.',
    'text' => null,
    'path' => null,
])

@php
    $faqs = \App\Models\Seo::forPath($path ?? request()->path())?->faqItems() ?? [];

    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(
            fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ],
            $faqs,
        ),
    ];
@endphp

@if ($faqs)
    <section id="faq" {{ $attributes->class('section-y w-full scroll-mt-20 border-b border-[#E1E5EA]') }}>
        <script type="application/ld+json">
            {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
        </script>

        <div class="site-container">
            <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">{{ $title }}</h2>
                @if ($text)
                    <p class="font-body-md text-body-md text-secondary max-w-2xl">{{ $text }}</p>
                @endif
            </div>

            <div
                x-data="{ open: 0 }"
                class="mx-auto max-w-3xl divide-y divide-[#E1E5EA] overflow-hidden rounded-lg border border-[#E1E5EA] bg-white"
            >
                @foreach ($faqs as $faq)
                    <div>
                        <button
                            type="button"
                            x-on:click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
                            x-bind:aria-expanded="(open === {{ $loop->index }}).toString()"
                            aria-controls="faq-answer-{{ $loop->index }}"
                            class="flex w-full items-center justify-between gap-4 p-4 text-left transition-colors hover:bg-[#F7F8FA]"
                        >
                            <span class="font-headline-sm text-on-surface text-base font-semibold sm:text-[17px]">{{ $faq['question'] }}</span>
                            <x-icons.plus
                                class="text-primary-container h-5 w-5 shrink-0 transition-transform duration-200"
                                x-bind:class="open === {{ $loop->index }} && 'rotate-45'"
                                aria-hidden="true"
                            />
                        </button>
                        <div
                            id="faq-answer-{{ $loop->index }}"
                            x-show="open === {{ $loop->index }}"
                            x-transition.opacity
                            x-cloak
                            class="px-4 pb-4"
                        >
                            <p class="font-body-md text-body-md text-secondary leading-relaxed whitespace-pre-line">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
