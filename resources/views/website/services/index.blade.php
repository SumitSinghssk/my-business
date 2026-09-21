@php
    $appName = \App\Helpers\Settings::appName();
@endphp

<x-website
    :title="'Software Development Services | ' . $appName"
    description="Website development, web applications, mobile apps, custom software, UI/UX design, cloud & DevOps and architecture consulting from one senior team."
    :image="asset('images/website/about/team-workspace.jpg')"
>
    <section class="w-full border-b border-[#E1E5EA] pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20">
        <div class="site-container">
            <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
                <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                    <h1
                        class="sm:mb-space-lg font-display mb-5 text-[36px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[54px] lg:text-[48px] xl:text-[58px] 2xl:text-[64px]"
                    >
                        Software development services built around real business goals.
                    </h1>

                    <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">
                        Strategy, design and engineering under one roof. Pick a single service or bring us in end to end, from first idea to a product
                        running reliably in production.
                    </p>

                    <div class="sm:gap-space-md flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <a
                            href="{{ route('contact') }}"
                            class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors sm:w-auto"
                        >
                            Start a Project →
                        </a>
                    </div>
                </div>

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
            </div>
        </div>
    </section>

    <section class="w-full overflow-hidden">
        @foreach ($services as $service)
            <div id="{{ $service->slug }}" class="group {{ $loop->even ? 'bg-[#F7F8FA]' : 'bg-white' }} scroll-mt-20 border-b border-[#E1E5EA]">
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

                                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
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

                            <div class="flex flex-col gap-4 border-t border-[#E1E5EA] pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($service->tags ?? [] as $tag)
                                        <span
                                            class="font-label-sm rounded border border-[#E1E5EA] bg-white px-2 py-0.5 text-xs tracking-wider text-[#434655] uppercase"
                                        >
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>

                                <a
                                    href="{{ route('services.show', $service->slug) }}"
                                    class="font-label-md hover:text-primary inline-flex shrink-0 items-center gap-1 self-start border-b border-[#0A0A0A] pb-1 text-sm font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors sm:self-auto"
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
