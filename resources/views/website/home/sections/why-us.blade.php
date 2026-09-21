@php
    $principles = [
        ['label' => '01 / Discipline', 'title' => 'Business First', 'text' => 'We align technical scope with unit economics, conversion efficiency, and operational ROI before writing a single line of code.', 'footer' => 'Measurable Outcomes'],
        ['label' => '02 / Unification', 'title' => 'Design & Engineering', 'text' => 'No wall between designers and engineers. Prototypes are tested against real API schemas early in the process to prevent costly rework.', 'footer' => 'Zero Hand-off Drift'],
        ['label' => '03 / Performance', 'title' => 'Built to Scale', 'text' => 'Architected from day one to handle traffic spikes, database sharding, and regional expansions without rewriting core infrastructure.', 'footer' => 'Enterprise Durability'],
        ['label' => '04 / Partnership', 'title' => 'Long-Term Thinking', 'text' => 'Complete documentation, 100% IP handover, automated regression test suites, and continuous post-launch engineering support.', 'footer' => 'Full Code Sovereignty'],
    ];
@endphp

<section class="section-y w-full border-b border-[#E1E5EA]">
    <div class="site-container">
        <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                Good software starts with understanding the problem.
            </h2>
            <p class="font-body-md text-body-md text-secondary max-w-2xl">
                We reject the culture of throwaway agency code. Every digital product we ship is designed as an enduring commercial asset with strict
                documentation, zero technical debt, and modular code ownership transferred entirely to your team.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($principles as $principle)
                <div class="flex h-full flex-col justify-between border border-[#E1E5EA] bg-white p-4">
                    <div>
                        <span class="font-label-sm text-label-sm text-secondary mb-3 block font-semibold tracking-widest uppercase">
                            {{ $principle['label'] }}
                        </span>
                        <h3 class="font-headline-sm text-headline-sm mb-2 font-semibold text-[#0A0A0A]">{{ $principle['title'] }}</h3>
                        <p class="font-body-md text-body-md text-secondary">{{ $principle['text'] }}</p>
                    </div>
                    <div class="font-label-sm text-label-sm text-secondary mt-4 border-t border-[#E1E5EA] pt-3 uppercase">
                        {{ $principle['footer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
