@php
    $testimonials = \App\Support\WebsiteContent::testimonials();
    $total = count($testimonials);
@endphp

<section class="section-y border-line bg-canvas w-full border-b">
    <div class="site-container">
        <div class="mx-auto max-w-4xl" data-testimonials>
            <div class="mb-space-lg gap-space-md flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-primary font-semibold tracking-widest uppercase">Partner Endorsement</span>

                @if ($total > 1)
                    <div class="gap-space-md flex items-center">
                        <span class="text-label-sm text-secondary font-mono font-semibold tracking-wider" aria-hidden="true">
                            <span data-testimonials-current class="text-ink">01</span>
                            / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                data-testimonials-prev
                                aria-label="Previous testimonial"
                                class="border-line text-ink hover:border-ink hover:bg-ink focus-visible:outline-ink flex h-11 w-11 cursor-pointer items-center justify-center border bg-white transition-colors hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2"
                            >
                                <x-icons.arrow-prev class="h-5 w-5" aria-hidden="true" />
                            </button>
                            <button
                                type="button"
                                data-testimonials-next
                                aria-label="Next testimonial"
                                class="border-line text-ink hover:border-ink hover:bg-ink focus-visible:outline-ink flex h-11 w-11 cursor-pointer items-center justify-center border bg-white transition-colors hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2"
                            >
                                <x-icons.arrow-next class="h-5 w-5" aria-hidden="true" />
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="swiper" data-testimonials-swiper>
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                        @php
                            $initials = collect(preg_split('/\s+/', trim($testimonial['name'])))
                                ->filter()
                                ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp

                        <div class="swiper-slide" style="height: auto">
                            <figure class="flex h-full flex-col justify-between">
                                <blockquote
                                    class="mb-space-xl font-headline-lg text-headline-lg text-ink leading-tight font-medium tracking-[-0.03em]"
                                >
                                    “{{ $testimonial['quote'] }}”
                                </blockquote>

                                <figcaption class="gap-space-md pt-space-md border-line flex w-full items-center border-t">
                                    <div
                                        class="font-label-md text-label-md bg-ink flex h-12 w-12 shrink-0 items-center justify-center font-semibold text-white"
                                    >
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <span class="font-headline-sm text-headline-sm text-ink block font-semibold">
                                            {{ $testimonial['name'] }}
                                        </span>
                                        <span class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">
                                            {{ $testimonial['role'] }} • {{ $testimonial['company'] }}
                                        </span>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
