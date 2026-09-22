<x-website
    :title="'Software Development Services | ' . $appName"
    description="Website development, web applications, mobile apps, custom software, UI/UX design, cloud & DevOps and architecture consulting from one senior team."
    :image="asset('images/website/about/team-workspace.jpg')"
>
    <x-website.page-hero title-class="text-[36px] sm:text-[54px] lg:text-[48px] xl:text-[58px] 2xl:text-[64px]">
        <x-slot:title>Software development services built around real business goals.</x-slot>

        <x-slot:text>
            Strategy, design and engineering under one roof. Pick a single service or bring us in end to end, from first idea to a product running
            reliably in production.
        </x-slot>

        <x-slot:actions>
            <x-website.button :href="route('contact')">Start a Project →</x-website.button>
        </x-slot>

        <x-slot:media>
            <div class="relative aspect-1376/768 w-full overflow-hidden rounded-lg lg:aspect-auto lg:min-h-full">
                <img
                    src="{{ asset('images/website/about/team-workspace.webp') }}"
                    width="1376"
                    height="768"
                    alt="Product team planning software development services together"
                    class="absolute inset-0 h-full w-full object-cover object-top-left"
                    fetchpriority="high"
                />
            </div>
        </x-slot>
    </x-website.page-hero>

    <section class="w-full overflow-hidden">
        @foreach ($services as $service)
            <div id="{{ $service->slug }}" class="group {{ $loop->even ? 'bg-canvas' : 'bg-white' }} border-line scroll-mt-20 border-b">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div
                        class="bg-surface-container-low {{ $loop->even ? 'lg:order-2' : '' }} relative aspect-16/10 w-full overflow-hidden lg:aspect-auto lg:min-h-120"
                    >
                        <img
                            src="{{ $service->featured_image_url ?? asset('images/website/about/team-workspace.webp') }}"
                            alt="{{ $service->title }}"
                            width="1600"
                            height="1000"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                        />
                    </div>

                    <div
                        class="{{ $loop->even ? 'lg:order-1 lg:items-end' : 'lg:order-2 lg:items-start' }} flex flex-col justify-center px-4 py-8 md:px-8 md:py-10 lg:px-12 lg:py-14 xl:px-20"
                    >
                        <div class="flex w-full max-w-xl flex-col gap-5">
                            <div class="flex flex-col gap-2">
                                <span class="text-label-sm text-primary font-mono font-semibold">
                                    [ {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} ]
                                </span>

                                <h2 class="font-headline-lg text-ink text-2xl font-semibold tracking-[-0.035em] md:text-3xl lg:text-4xl">
                                    <a href="{{ route('services.show', $service->slug) }}" class="hover:text-primary-container transition-colors">
                                        {{ $service->title }}
                                    </a>
                                </h2>
                            </div>

                            <p class="font-body-md text-body-md text-secondary leading-relaxed">
                                {{ $service->excerpt }}
                            </p>

                            <ul class="grid grid-cols-1 gap-x-6 gap-y-2.5 sm:grid-cols-2">
                                @foreach ($service->highlights ?? [] as $point)
                                    <li class="font-body-sm text-body-sm text-on-surface flex items-start gap-2.5">
                                        <x-icons.check
                                            class="text-primary-container mt-0.5 h-4 w-4 shrink-0"
                                            aria-hidden="true"
                                            stroke-width="2.25"
                                        />
                                        <span>{{ $point }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="border-line flex flex-col gap-4 border-t pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($service->tags ?? [] as $tag)
                                        <span
                                            class="font-label-sm border-line text-on-surface-variant rounded border bg-white px-2 py-0.5 text-xs tracking-wider uppercase"
                                        >
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>

                                <a
                                    href="{{ route('services.show', $service->slug) }}"
                                    class="font-label-md hover:text-primary border-ink text-ink inline-flex shrink-0 items-center gap-1 self-start border-b pb-1 text-sm font-semibold tracking-wider uppercase transition-colors sm:self-auto"
                                >
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <x-website.faq title="Common questions about working with us." class="bg-white" />

    @include('website.home.sections.cta')
</x-website>
