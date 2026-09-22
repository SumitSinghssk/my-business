{{-- "How we work" steps, shared by the About and service pages. Optional: $title, $text, $class --}}
@php
    $process = \App\Support\WebsiteContent::processSteps();
@endphp

<section class="section-y {{ $class ?? '' }} border-line w-full border-b">
    <div class="site-container">
        <x-website.section-heading
            title="{{ $title ?? 'How we take a product from idea to launch.' }}"
            text="{{ $text ?? 'A clear, repeatable process, so you always know where your product stands and what happens next.' }}"
        />

        <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($process as $step)
                <div class="border-line flex h-full flex-col overflow-hidden rounded-lg border bg-white shadow-sm">
                    <div class="border-line relative aspect-16/10 overflow-hidden border-b">
                        <img
                            src="{{ asset('images/website/process/' . $step['image'] . '.webp') }}"
                            width="1600"
                            height="1000"
                            alt="{{ $step['title'] }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                        <span
                            class="font-label-sm bg-ink/90 absolute top-2.5 left-2.5 px-2 py-0.5 text-[11px] font-semibold tracking-wider text-white"
                        >
                            STEP {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-headline-sm text-headline-sm text-ink mb-2 font-semibold">{{ $step['title'] }}</h3>
                        <p class="font-body-sm text-body-sm text-secondary">{{ $step['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
