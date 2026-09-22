@php
    $layers = \App\Support\WebsiteContent::technologyLayers();
@endphp

<section class="section-y border-ink-line bg-ink w-full border-b text-white">
    <div class="site-container">
        <x-website.section-heading
            title="Technology that works for your business."
            text="We use proven modern technologies to create fast, secure, and scalable digital products. No fragile novelty stacks."
            dark
        />

        <div class="border-ink-line grid grid-cols-1 border-t border-l md:grid-cols-2 lg:grid-cols-3">
            @foreach ($layers as $layer)
                <div class="border-ink-line hover:bg-ink-soft border-r border-b p-4 transition-colors lg:p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="font-label-sm text-label-sm text-dark-subtle tracking-widest uppercase">{{ $layer['layer'] }}</span>
                        <span class="font-label-sm text-label-sm text-accent-on-dark uppercase">{{ $layer['tag'] }}</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm mb-3 font-semibold text-white">{{ $layer['title'] }}</h3>
                    <ul class="space-y-space-xs font-body-md text-body-md text-dark-muted">
                        @foreach ($layer['items'] as $name => $note)
                            <li class="flex items-center justify-between gap-3">
                                <span>{{ $name }}</span>
                                <span class="text-dark-subtle shrink-0 text-xs">{{ $note }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
