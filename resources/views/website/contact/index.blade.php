<x-website
    :title="'Contact Us: Start Your Project | ' . $appName"
    description="Tell us about your project. Our engineering team replies within one business day."
    :image="asset('images/website/contact/workspace.jpg')"
>
    <div class="bg-surface text-on-surface flex w-full flex-col">
        <x-website.page-hero :eyebrow="'Contact ' . $appName">
            <x-slot:title>Let's build something reliable together.</x-slot>

            <x-slot:text>
                Tell us what you're building. Whether it's a new product, a rescue mission or a scaling challenge, we'll help you find the right way
                forward.
            </x-slot>

            <x-slot:actions>
                <x-website.button href="#contact-form">Send a Message ↓</x-website.button>
                <x-website.button :href="$emails ? 'mailto:' . $emails[0] : route('services')" variant="outline">
                    {{ $emails ? 'Email Us →' : 'Explore Services →' }}
                </x-website.button>
            </x-slot>

            <x-slot:media>
                <div class="relative aspect-16/10 w-full overflow-hidden rounded-lg lg:aspect-auto lg:min-h-full">
                    <img
                        src="{{ asset('images/website/contact/workspace.webp') }}"
                        width="1600"
                        height="1000"
                        alt="Engineering team collaborating around a shared desk"
                        class="absolute inset-0 h-full w-full object-cover"
                        fetchpriority="high"
                    />
                </div>
            </x-slot>
        </x-website.page-hero>

        <section class="section-y border-line w-full border-b">
            <div class="site-container">
                <div class="mb-6 flex flex-col gap-3">
                    <h2 class="section-title text-ink">Tell us about your project.</h2>
                    <p class="font-body-md text-body-md text-secondary max-w-2xl">
                        A few details help us bring the right people to the first conversation. We reply within one business day.
                    </p>
                </div>

                <div class="gap-section grid grid-cols-1 items-start lg:grid-cols-12">
                    <div
                        id="contact-form"
                        @class(['scroll-mt-24', 'lg:col-span-7 xl:col-span-8' => $hasSidebar, 'lg:col-span-8 lg:col-start-3' => ! $hasSidebar])
                    >
                        @include('website.contact.partials.form')
                    </div>

                    @if ($hasSidebar)
                        @include('website.contact.partials.details')
                    @endif
                </div>

                @include('website.contact.partials.offices')
            </div>
        </section>

        <x-website.faq title="Questions before we talk?" text="If you don't see your question here, just ask it in the form." class="bg-white" />
    </div>
</x-website>
