@php
    $testimonials = [
        [
            'quote' => "Brought enterprise-level discipline to our product engineering. They didn't just write code; they transformed our product velocity, uptime, and systemic reliability.",
            'name' => 'Marcus Vance',
            'role' => 'Chief Technology Officer',
            'company' => 'Vectra Dynamics',
        ],
        [
            'quote' => 'From the first sprint, the team treated our platform like their own. Release cycles dropped from weeks to days, and our compliance audits stopped being a fire drill.',
            'name' => 'Elena Rossi',
            'role' => 'VP of Engineering',
            'company' => 'Northbridge Health',
        ],
        [
            'quote' => 'We came in with a rough idea and left with a production-ready product. Clear communication, honest timelines, and architecture that scales without rewrites.',
            'name' => 'Daniel Okafor',
            'role' => 'Founder & CEO',
            'company' => 'Stackline Logistics',
        ],
        [
            'quote' => 'Their engineers slotted into our workflow seamlessly. Uptime is up, incident load is down, and our own team finally has room to focus on roadmap work.',
            'name' => 'Priya Nair',
            'role' => 'Head of Product',
            'company' => 'Finlytic',
        ],
    ];

    $total = count($testimonials);
@endphp

<section class="py-space-2xl w-full border-b border-[#E1E5EA] bg-[#F7F8FA]">
    <div class="site-container px-4">
        <div class="mx-auto max-w-4xl" data-testimonials>
            <div class="mb-space-lg gap-space-md flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-primary font-semibold tracking-widest uppercase">Partner Endorsement</span>

                @if ($total > 1)
                    <div class="gap-space-md flex items-center">
                        <span class="text-label-sm text-secondary font-mono font-semibold tracking-wider" aria-hidden="true">
                            <span data-testimonials-current class="text-[#0A0A0A]">01</span>
                            / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                data-testimonials-prev
                                aria-label="Previous testimonial"
                                class="flex h-11 w-11 cursor-pointer items-center justify-center border border-[#E1E5EA] bg-white text-[#0A0A0A] transition-colors hover:border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0A0A0A]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.75"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M19 12H5M11 6l-6 6 6 6" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                data-testimonials-next
                                aria-label="Next testimonial"
                                class="flex h-11 w-11 cursor-pointer items-center justify-center border border-[#E1E5EA] bg-white text-[#0A0A0A] transition-colors hover:border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0A0A0A]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.75"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
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
                                    class="mb-space-xl font-headline-lg text-headline-lg leading-tight font-medium tracking-[-0.03em] text-[#0A0A0A]"
                                >
                                    “{{ $testimonial['quote'] }}”
                                </blockquote>

                                <figcaption class="gap-space-md pt-space-md flex w-full items-center border-t border-[#E1E5EA]">
                                    <div
                                        class="font-label-md text-label-md flex h-12 w-12 shrink-0 items-center justify-center bg-[#0A0A0A] font-semibold text-white"
                                    >
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <span class="font-headline-sm text-headline-sm block font-semibold text-[#0A0A0A]">
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

@push('scripts')
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-testimonials]').forEach(function (root) {
                var el = root.querySelector('[data-testimonials-swiper]');
                var current = root.querySelector('[data-testimonials-current]');
                var multiple = el.querySelectorAll('.swiper-slide').length > 1;

                new Swiper(el, {
                    slidesPerView: 1,
                    spaceBetween: 32,
                    speed: 600,
                    loop: multiple,
                    allowTouchMove: multiple,
                    autoHeight: false,
                    keyboard: { enabled: true },
                    a11y: { enabled: true },
                    autoplay: multiple ? { delay: 7000, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
                    navigation: {
                        prevEl: root.querySelector('[data-testimonials-prev]'),
                        nextEl: root.querySelector('[data-testimonials-next]'),
                    },
                    on: {
                        slideChange: function (swiper) {
                            if (current) {
                                current.textContent = String(swiper.realIndex + 1).padStart(2, '0');
                            }
                        },
                    },
                });
            });
        });
    </script>
@endpush
