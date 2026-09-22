{{-- About: how we engage (one team per product). Expects $model. --}}
{{-- Engagement model --}}
<section class="section-y border-line w-full border-b bg-white">
    <div class="site-container">
        <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-12">
            <div class="lg:pr-space-lg flex flex-col justify-center gap-5 lg:order-1 lg:col-span-6">
                <span class="font-label-sm text-label-sm text-primary font-semibold tracking-widest uppercase">How We Engage</span>
                <h2 class="section-title text-ink">One team, fully focused on your product.</h2>

                <div class="divide-line border-line divide-y border-y">
                    @foreach ($model as $item)
                        <div class="flex gap-4 py-4">
                            <span class="text-label-md text-accent-on-dark font-mono font-semibold">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div>
                                <h3 class="font-headline-sm text-ink text-[18px] font-semibold">{{ $item['title'] }}</h3>
                                <p class="font-body-sm text-body-sm text-secondary mt-1">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a
                    href="{{ route('work.index') }}"
                    class="font-label-md text-label-md hover:text-primary border-ink text-ink inline-flex items-center gap-1 self-start border-b pb-1 font-semibold tracking-wider uppercase transition-colors"
                >
                    See what we've built →
                </a>
            </div>

            <div
                class="bg-surface-container-low border-line relative aspect-4/3 w-full overflow-hidden rounded-lg border sm:aspect-video lg:order-2 lg:col-span-6 lg:aspect-auto lg:min-h-105"
            >
                <img
                    src="{{ asset('images/website/about/collaboration.webp') }}"
                    width="1600"
                    height="1000"
                    alt="Product, design and engineering team collaborating"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                />
            </div>
        </div>
    </div>
</section>
