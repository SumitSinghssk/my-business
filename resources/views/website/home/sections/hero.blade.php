<x-website.page-hero>
    <x-slot:title>We build digital products for businesses ready to move forward.</x-slot>

    <x-slot:text>
        From websites and mobile applications to custom software, we design and engineer digital experiences built around real business goals.
    </x-slot>

    <x-slot:actions>
        <x-website.button :href="route('contact')">Start a Project →</x-website.button>
        <x-website.button :href="route('work.index')" variant="outline">View Our Work →</x-website.button>
    </x-slot>

    <x-slot:media>
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
    </x-slot>
</x-website.page-hero>
