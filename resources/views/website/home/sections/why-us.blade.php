@php
    $principles = \App\Support\WebsiteContent::principles();
@endphp

<section class="section-y border-line w-full border-b">
    <div class="site-container">
        <x-website.section-heading
            title="Good software starts with understanding the problem."
            text="We reject the culture of throwaway agency code. Every digital product we ship is designed as an enduring commercial asset with strict documentation, zero technical debt, and modular code ownership transferred entirely to your team."
        />

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($principles as $principle)
                <div class="border-line flex h-full flex-col justify-between border bg-white p-4">
                    <div>
                        <span class="font-label-sm text-label-sm text-secondary mb-3 block font-semibold tracking-widest uppercase">
                            {{ $principle['label'] }}
                        </span>
                        <h3 class="font-headline-sm text-headline-sm text-ink mb-2 font-semibold">{{ $principle['title'] }}</h3>
                        <p class="font-body-md text-body-md text-secondary">{{ $principle['text'] }}</p>
                    </div>
                    <div class="font-label-sm text-label-sm text-secondary border-line mt-4 border-t pt-3 uppercase">
                        {{ $principle['footer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
