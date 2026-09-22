@php
    $email = \App\Helpers\Settings::emails()[0] ?? null;
@endphp

<section class="section-y bg-ink w-full text-white">
    <div class="site-container">
        <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
            <h2 class="mb-space-md font-display text-[44px] leading-[1.05] font-semibold tracking-[-0.04em] text-white sm:text-[60px] lg:text-[68px]">
                Have a project in mind?
            </h2>

            <p class="mb-space-xl font-body-lg text-body-lg text-dark-muted max-w-xl">
                Tell us what you're building. We'll help you turn the idea into an enterprise-ready digital product on schedule and with architectural
                certainty.
            </p>

            <div class="gap-space-md flex w-full flex-col items-center justify-center sm:flex-row">
                <x-website.button :href="route('contact')" variant="inverse">Start a Conversation →</x-website.button>
                @if ($email)
                    <x-website.button href="mailto:{{ $email }}" variant="outline-dark">{{ $email }}</x-website.button>
                @else
                    <x-website.button :href="route('services')" variant="outline-dark">Explore Services →</x-website.button>
                @endif
            </div>
        </div>
    </div>
</section>
