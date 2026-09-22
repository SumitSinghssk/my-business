@php
    $stats = \App\Support\WebsiteContent::homeStats();
@endphp

<section class="section-y border-line w-full border-b">
    <div class="site-container">
        <x-website.section-heading title="We combine strategy, design and engineering to turn ideas into reliable digital products." />

        <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-12">
            <div
                class="bg-surface-container-low border-line relative aspect-4/3 w-full overflow-hidden rounded-lg border sm:aspect-video lg:col-span-7 lg:aspect-auto lg:min-h-105"
            >
                <img
                    src="{{ asset('images/website/about/team-workspace.webp') }}"
                    width="1376"
                    height="768"
                    alt="Engineering team in an architectural design critique"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                />
            </div>

            <div class="lg:pl-space-md flex flex-col justify-between gap-6 lg:col-span-5">
                <div class="flex flex-col items-start gap-4">
                    <p class="font-body-lg text-body-lg text-secondary leading-relaxed">
                        We work with high-velocity enterprises and venture-backed founders to design, build, and scale software systems that solve
                        tangible operational and commercial problems. No fragmented handoffs—every product is delivered by unified cross-functional
                        pods.
                    </p>
                    <a
                        href="{{ route('about') }}"
                        class="gap-space-xs font-label-md text-label-md hover:text-primary border-ink text-ink inline-flex items-center border-b pb-1 font-semibold tracking-wider uppercase transition-colors"
                    >
                        Learn More About Us →
                    </a>
                </div>

                <div class="border-line border-t">
                    @foreach ($stats as $stat)
                        <div class="border-line grid grid-cols-[8rem_1fr] items-center gap-4 border-b py-3">
                            <span
                                class="{{ $stat['highlight'] ? 'text-primary' : 'text-ink' }} font-display text-3xl leading-none font-semibold lg:text-[34px]"
                            >
                                {{ $stat['value'] }}
                            </span>
                            <span class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
