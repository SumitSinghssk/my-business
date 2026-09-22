{{-- About: how the studio started. --}}
{{-- Our story --}}
<section class="section-y border-line w-full border-b">
    <div class="site-container">
        <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-12">
            <div
                class="bg-surface-container-low border-line relative aspect-4/3 w-full overflow-hidden rounded-lg border sm:aspect-video lg:col-span-6 lg:aspect-auto lg:min-h-105"
            >
                <img
                    src="{{ asset('images/website/about/code-review.webp') }}"
                    width="1600"
                    height="1000"
                    alt="Two engineers reviewing code together"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                />
            </div>

            <div class="lg:pl-space-lg flex flex-col justify-center gap-5 lg:col-span-6">
                <span class="font-label-sm text-label-sm text-primary font-semibold tracking-widest uppercase">Our Story</span>
                <h2 class="section-title text-ink">Started by engineers who were tired of throwaway software.</h2>
                <div class="font-body-md text-body-md text-secondary flex flex-col gap-4 leading-relaxed">
                    <p>
                        {{ $appName }} began with a simple frustration: too many businesses were paying for software that looked finished on launch
                        day and started falling apart soon after. Handoffs were messy, documentation was missing, and nobody owned the outcome.
                    </p>
                    <p>
                        We set out to work differently. Small senior teams, clear communication, and engineering standards we'd be proud to hand over.
                        Every product we build is designed to be understood, maintained and extended by the people who own it.
                    </p>
                    <p>
                        Today we partner with startups and established enterprises across fintech, healthcare, logistics and SaaS, from first
                        prototype to platforms serving millions of requests a day.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
