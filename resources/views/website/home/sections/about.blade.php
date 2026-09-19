@php
    $stats = [
        ['value' => '99.98%', 'label' => 'Historical SLA Production Uptime', 'highlight' => false],
        ['value' => '$420M+', 'label' => 'Processed Client Volume (2024)', 'highlight' => true],
        ['value' => '14', 'label' => 'Global Engineering & Design Awards', 'highlight' => false],
    ];
@endphp

<section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA]">
    <div class="site-container px-4">
        <div class="mx-auto mb-6 lg:mb-8 lg:max-w-4xl lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                We combine strategy, design and engineering to turn ideas into reliable digital products.
            </h2>
        </div>

        <div class="grid grid-cols-1 items-stretch gap-6 lg:grid-cols-12 lg:gap-8">
            <div
                class="bg-surface-container-low relative aspect-4/3 w-full overflow-hidden rounded-lg border border-[#E1E5EA] sm:aspect-video lg:col-span-7 lg:aspect-auto lg:min-h-105"
            >
                <img
                    src="{{ asset('images/website/about/team-workspace.jpg') }}"
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
                        class="gap-space-xs font-label-md text-label-md hover:text-primary inline-flex items-center border-b border-[#0A0A0A] pb-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                    >
                        Learn More About Us →
                    </a>
                </div>

                <div class="border-t border-[#E1E5EA]">
                    @foreach ($stats as $stat)
                        <div class="grid grid-cols-[8rem_1fr] items-center gap-4 border-b border-[#E1E5EA] py-3">
                            <span
                                class="{{ $stat['highlight'] ? 'text-primary' : 'text-[#0A0A0A]' }} font-display text-3xl leading-none font-semibold lg:text-[34px]"
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
