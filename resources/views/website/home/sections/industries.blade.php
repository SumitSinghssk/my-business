@php
    $sectors = \App\Support\WebsiteContent::industries();
@endphp

<section class="section-y border-line w-full border-b bg-white">
    <div class="site-container">
        <x-website.section-heading
            title="Digital solutions across industries."
            text="Tailored sector logic addressing compliance, regulatory security, and customer expectations."
        />

        <div class="gap-section grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($sectors as $sector)
                <div
                    class="hover:border-primary-container border-line flex flex-col overflow-hidden rounded-lg border bg-white shadow-sm transition duration-200"
                >
                    <img
                        src="{{ $sector['image'] }}"
                        width="1376"
                        height="768"
                        alt="{{ $sector['alt'] }}"
                        class="border-line h-48 w-full rounded-t-lg border-b object-cover"
                        loading="lazy"
                    />
                    <div class="flex flex-1 flex-col justify-between gap-4 rounded-b-lg bg-white p-4">
                        <div class="space-y-space-xs">
                            <div class="mb-1 flex items-center gap-1.5">
                                <span class="bg-primary-container h-1.5 w-1.5 rounded-full"></span>
                                <span class="text-label-sm text-outline font-mono font-medium tracking-wider uppercase">
                                    Sector / {{ $sector['number'] }}
                                </span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-ink mb-2 font-semibold">{{ $sector['title'] }}</h3>
                            <p class="font-body-sm text-body-sm text-secondary leading-relaxed">{{ $sector['text'] }}</p>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($sector['tags'] as $tag)
                                <span
                                    class="bg-surface-container-low font-label-sm border-line text-on-surface-variant border px-2 py-0.5 text-[9px] font-semibold tracking-wider uppercase"
                                >
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
