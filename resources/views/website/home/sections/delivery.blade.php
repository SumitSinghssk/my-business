@php
    $phases = \App\Support\WebsiteContent::deliveryPhases();
@endphp

<section class="section-y border-line w-full border-b bg-white">
    <div class="site-container">
        <x-website.section-heading
            title="Simple process. Serious execution."
            text="No ambiguous handoffs, zero opaque sprints, and no black-box development. Our delivery lifecycle operates with mathematical transparency across six disciplined milestones."
        />

        <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($phases as $phase)
                <div
                    class="group hover:border-primary-container border-line flex flex-col overflow-hidden rounded-lg border bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="bg-surface-container-low border-line aspect-16/10 overflow-hidden border-b">
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
                            <h3 class="font-headline-sm text-headline-sm group-hover:text-primary-container text-ink font-semibold transition-colors">
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
