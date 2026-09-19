@php
    $email = \App\Helpers\Settings::emails()[0] ?? null;
@endphp

<section class="py-space-2xl w-full bg-[#0A0A0A] text-white">
    <div class="site-container px-4">
        <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
            <h2 class="mb-space-md font-display text-[44px] leading-[1.05] font-semibold tracking-[-0.04em] text-white sm:text-[60px] lg:text-[68px]">
                Have a project in mind?
            </h2>

            <p class="mb-space-xl font-body-lg text-body-lg max-w-xl text-[#A0A0A0]">
                Tell us what you're building. We'll help you turn the idea into an enterprise-ready digital product on schedule and with architectural
                certainty.
            </p>

            <div class="gap-space-md flex w-full flex-col items-center justify-center sm:flex-row">
                <a
                    href="{{ route('contact') }}"
                    class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-white py-4 tracking-wider text-[#0A0A0A] uppercase transition-all hover:text-white sm:w-auto"
                >
                    Start a Conversation →
                </a>
                @if ($email)
                    <a
                        href="mailto:{{ $email }}"
                        class="px-space-xl font-label-md text-label-md inline-flex w-full items-center justify-center border border-white/20 bg-transparent py-4 tracking-wider text-white uppercase transition-all hover:border-white sm:w-auto"
                    >
                        {{ $email }}
                    </a>
                @else
                    <a
                        href="{{ route('services') }}"
                        class="px-space-xl font-label-md text-label-md inline-flex w-full items-center justify-center border border-white/20 bg-transparent py-4 tracking-wider text-white uppercase transition-all hover:border-white sm:w-auto"
                    >
                        Explore Services →
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
