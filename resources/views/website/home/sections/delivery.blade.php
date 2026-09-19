@php
    $phases = [
        [
            'meta' => 'Phase 1.0 • Days 1-10',
            'title' => 'Discover & Map',
            'text' => 'Deep immersion in commercial logic, constraint mapping, technical debt audits, and core user workflows.',
            'image' => 'discover.jpg',
            'alt' => 'Team mapping requirements on a wall of notes during a discovery workshop sticky',
        ],
        [
            'meta' => 'Phase 2.0 • Days 11-20',
            'title' => 'Architecture & Specs',
            'text' => 'System topology, relational and non-relational data modeling, API contracts, and infrastructure blueprints.',
            'image' => 'architecture.jpg',
            'alt' => 'Engineer connecting components on a system architecture flow diagram',
        ],
        [
            'meta' => 'Phase 3.0 • Sprints 1-2',
            'title' => 'Design Systems',
            'text' => 'High-density wireframing, component tokenization, accessible interaction patterns, and click-through prototypes.',
            'image' => 'design-systems.jpg',
            'alt' => 'Designer sketching user interface wireframes on paper',
        ],
        [
            'meta' => 'Phase 4.0 • Sprints 3-6',
            'title' => 'Agile Build',
            'text' => 'Modular engine engineering, type-safe data pipes, continuous automated regression testing, and PR reviews.',
            'image' => 'agile-build.jpg',
            'alt' => 'Source code open in an editor on a laptop',
        ],
        [
            'meta' => 'Phase 5.0 • Production',
            'title' => 'Hardened Launch',
            'text' => 'Third-party penetration testing, multi-region container orchestration, staging audits, and zero-downtime cutover.',
            'image' => 'launch.jpg',
            'alt' => 'Networked server racks in a data centre',
        ],
        [
            'meta' => 'Phase 6.0 • Continuity',
            'title' => 'Scale & Observability',
            'text' => 'Real-time telemetry observability, P99 latency optimizations, weekly iterations, and enterprise SLA guarantee.',
            'image' => 'observability.jpg',
            'alt' => 'Live performance analytics dashboard with latency and traffic charts',
        ],
    ];
@endphp

<section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA] bg-white">
    <div class="site-container px-4">
        {{-- Header: left-aligned below lg, centered from lg up --}}
        <div class="mb-6 flex flex-col gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                Simple process. Serious execution.
            </h2>
            <p class="font-body-md text-body-md text-secondary max-w-2xl">
                No ambiguous handoffs, zero opaque sprints, and no black-box development. Our delivery lifecycle operates with mathematical
                transparency across six disciplined milestones.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            @foreach ($phases as $phase)
                <div
                    class="group hover:border-primary-container flex flex-col overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="bg-surface-container-low aspect-16/10 overflow-hidden border-b border-[#E1E5EA]">
                        <img
                            src="{{ asset('images/website/process/' . $phase['image']) }}"
                            alt="{{ $phase['alt'] }}"
                            width="1200"
                            height="750"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        />
                    </div>

                    <div class="flex flex-1 flex-col justify-between p-4">
                        <div class="space-y-space-sm">
                            <span class="font-label-sm text-label-sm text-outline block tracking-widest uppercase">{{ $phase['meta'] }}</span>
                            <h3
                                class="font-headline-sm text-headline-sm group-hover:text-primary-container font-semibold text-[#0A0A0A] transition-colors"
                            >
                                {{ $phase['title'] }}
                            </h3>
                            <p class="font-body-sm text-body-sm text-secondary leading-relaxed">{{ $phase['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
