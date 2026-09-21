{{-- "How we work" steps, shared by the About and service pages. Optional: $title, $text, $class --}}
@php
    $process = [
        ['image' => 'discover', 'title' => 'Discover', 'text' => 'Workshops, user research and a technical audit to agree what success looks like before anything is built.'],
        ['image' => 'architecture', 'title' => 'Design & Architect', 'text' => 'Product design, system architecture and a delivery plan your whole team can read and challenge.'],
        ['image' => 'agile-build', 'title' => 'Build', 'text' => 'Two-week sprints with live demos, code review on every change and automated tests from day one.'],
        ['image' => 'launch', 'title' => 'Launch & Grow', 'text' => 'Staged releases, monitoring and ongoing engineering support as your product and traffic grow.'],
    ];
@endphp

<section class="section-y {{ $class ?? '' }} w-full border-b border-[#E1E5EA]">
    <div class="site-container">
        <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                {{ $title ?? 'How we take a product from idea to launch.' }}
            </h2>
            <p class="font-body-md text-body-md text-secondary max-w-2xl">
                {{ $text ?? 'A clear, repeatable process, so you always know where your product stands and what happens next.' }}
            </p>
        </div>

        <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($process as $step)
                <div class="flex h-full flex-col overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm">
                    <div class="relative aspect-16/10 overflow-hidden border-b border-[#E1E5EA]">
                        <img
                            src="{{ asset('images/website/process/' . $step['image'] . '.webp') }}"
                            width="1600"
                            height="1000"
                            alt="{{ $step['title'] }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                        <span
                            class="font-label-sm absolute top-2.5 left-2.5 bg-[#0A0A0A]/90 px-2 py-0.5 text-[11px] font-semibold tracking-wider text-white"
                        >
                            STEP {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-headline-sm text-headline-sm mb-2 font-semibold text-[#0A0A0A]">{{ $step['title'] }}</h3>
                        <p class="font-body-sm text-body-sm text-secondary">{{ $step['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
