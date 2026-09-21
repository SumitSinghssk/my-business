<section class="w-full border-b border-[#E1E5EA] pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20">
    <div class="site-container">
        <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
            <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                <h1
                    class="font-display sm:mb-space-lg mb-5 text-[38px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[60px] lg:text-[56px] xl:text-[68px] 2xl:text-[74px]"
                >
                    We build digital products for businesses ready to move forward.
                </h1>

                <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">
                    From websites and mobile applications to custom software, we design and engineer digital experiences built around real business
                    goals.
                </p>

                <div class="sm:gap-space-md flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <a
                        href="{{ route('contact') }}"
                        class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors sm:w-auto"
                    >
                        Start a Project →
                    </a>
                    <a
                        href="{{ route('work.index') }}"
                        class="px-space-xl font-label-md text-label-md inline-flex w-full items-center justify-center border border-[#E1E5EA] bg-white py-4 tracking-wider text-[#0A0A0A] uppercase transition-all hover:border-[#0A0A0A] sm:w-auto"
                    >
                        View Our Work →
                    </a>
                </div>
            </div>

            <div class="relative aspect-1028/574 w-full overflow-hidden lg:aspect-auto lg:min-h-full">
                <img
                    src="{{ asset('images/website/hero/dashboard.webp') }}"
                    width="1028"
                    height="574"
                    alt="SaaS analytics and enterprise orchestration dashboard interface"
                    class="absolute inset-0 h-full w-full object-cover object-top-left"
                    fetchpriority="high"
                />
            </div>
        </div>
    </div>
</section>
