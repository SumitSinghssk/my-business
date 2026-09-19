@php
    $cases = [
        [
            'sector' => 'saas',
            'service' => 'web-app',
            'label' => 'Case 01 • Enterprise SaaS',
            'title' => 'Orion Systems',
            'text' => 'Multi-cloud network topology console streaming telemetry across 24,000 active cluster nodes with dynamic resource reallocation.',
            'image' => 'orion-systems.jpg',
            'width' => 1376,
            'height' => 768,
            'alt' => 'Orion Systems infrastructure orchestration dashboard',
            'tags' => ['Next.js 15', 'TypeScript', 'Go', 'TimescaleDB'],
        ],
        [
            'sector' => 'fintech',
            'service' => 'mobile-app',
            'label' => 'Case 02 • Fintech & Logistics',
            'title' => 'Apex Pay & Logistics',
            'text' => 'Real-time cross-border routing & driver financial telemetry for 6,000+ fleet vehicles with offline-capable driver settlement.',
            'image' => 'apex-pay-logistics.jpg',
            'width' => 964,
            'height' => 602,
            'alt' => 'Apex fintech wallet and logistics shipment tracking mobile apps',
            'tags' => ['Flutter', 'React Native', 'AWS IoT', 'PostgreSQL'],
        ],
        [
            'sector' => 'health',
            'service' => 'custom-software',
            'label' => 'Case 03 • AI & Healthtech',
            'title' => 'GenoSync Diagnostics',
            'text' => 'Multi-modal genomic sequencing analysis and real-time patient biomarker telemetry for clinical research and oncology diagnostics.',
            'image' => 'genosync-diagnostics.jpg',
            'width' => 908,
            'height' => 568,
            'alt' => 'GenoSync genomic diagnostics platform on a tablet',
            'tags' => ['Python / PyTorch', 'React', 'FastAPI', 'Kubernetes'],
        ],
    ];
@endphp

<section id="selected-work" class="py-space-lg md:py-space-xl w-full scroll-mt-19 border-b border-[#E1E5EA]">
    <div class="site-container px-4">
        <div class="mb-6 flex flex-col items-start gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                Products we've helped bring to life.
            </h2>
            <a
                href="{{ route('services') }}"
                class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
            >
                Explore Our Services →
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            @foreach ($cases as $case)
                <article
                    class="group hover:border-primary-container flex flex-col overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="bg-surface-container-low aspect-16/10 overflow-hidden border-b border-[#E1E5EA]">
                        <img
                            src="{{ asset('images/website/work/' . $case['image']) }}"
                            alt="{{ $case['alt'] }}"
                            width="{{ $case['width'] }}"
                            height="{{ $case['height'] }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        />
                    </div>

                    <div class="flex flex-1 flex-col justify-between p-5">
                        <div class="space-y-space-sm">
                            <span class="font-label-sm text-label-sm text-primary block font-semibold tracking-widest uppercase">
                                {{ $case['label'] }}
                            </span>
                            <h3
                                class="font-headline-sm text-headline-sm group-hover:text-primary-container font-semibold text-[#0A0A0A] transition-colors"
                            >
                                {{ $case['title'] }}
                            </h3>
                            <p class="font-body-sm text-body-sm text-secondary leading-relaxed">{{ $case['text'] }}</p>

                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @foreach ($case['tags'] as $tag)
                                    <span
                                        class="font-label-sm text-label-sm rounded border border-[#E1E5EA] bg-white px-2 py-0.5 tracking-wider text-[#434655] uppercase"
                                    >
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-5 border-t border-[#E1E5EA] pt-4">
                            <a
                                href="{{ route('contact', ['service' => $case['service']]) }}"
                                class="gap-space-xs font-label-md text-label-md group-hover:text-primary inline-flex items-center font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                            >
                                Discuss a Similar Project →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
