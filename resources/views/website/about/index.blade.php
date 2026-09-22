@php
    $stats = \App\Support\WebsiteContent::aboutStats();
    $values = \App\Support\WebsiteContent::values();
    $model = \App\Support\WebsiteContent::engagementModel();
    $team = \App\Support\WebsiteContent::team();
@endphp

<x-website
    :title="'About Us: Our Studio, Team & Approach | ' . $appName"
    description="We are a software and digital product studio combining strategy, design and engineering to build reliable products for ambitious businesses."
    :image="asset('images/website/about/team-office.jpg')"
>
    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home hero) --}}
        <x-website.page-hero :eyebrow="'About ' . $appName">
            <x-slot:title>A studio built around engineering discipline.</x-slot>

            <x-slot:text>
                We're a team of strategists, designers and engineers who help businesses turn ambitious ideas into reliable digital products, and keep
                them running long after launch.
            </x-slot>

            <x-slot:actions>
                <x-website.button :href="route('contact')">Start a Project →</x-website.button>
                <x-website.button :href="route('work.index')" variant="outline">See Our Work →</x-website.button>
            </x-slot>

            <x-slot:media>
                <div class="relative aspect-16/10 w-full overflow-hidden rounded-lg lg:aspect-auto lg:min-h-full">
                    <img
                        src="{{ asset('images/website/about/team-office.webp') }}"
                        width="1600"
                        height="1000"
                        alt="Our engineering team working together in the studio"
                        class="absolute inset-0 h-full w-full object-cover"
                        fetchpriority="high"
                    />
                </div>
            </x-slot>

            {{-- Key numbers --}}
            <dl class="border-line bg-line mt-10 grid grid-cols-2 gap-px overflow-hidden rounded-lg border md:mt-14 lg:mt-16 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="flex flex-col gap-1 bg-white p-4 lg:p-5">
                        <dt class="font-label-sm text-label-sm text-outline order-1 tracking-widest uppercase">{{ $stat['label'] }}</dt>
                        <dd
                            class="{{ $loop->index === 2 ? 'text-primary' : 'text-ink' }} font-display order-2 text-[28px] leading-tight font-semibold tracking-[-0.03em] md:text-[34px] lg:text-[40px]"
                        >
                            {{ $stat['value'] }}
                        </dd>
                        <dd class="font-body-sm text-body-sm text-secondary order-3">{{ $stat['note'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </x-website.page-hero>

        @include('website.about.sections.story')

        @include('website.about.sections.values')

        @include('website.partials.process')

        @include('website.about.sections.engagement')

        @include('website.about.sections.team')

        <x-website.faq class="bg-white" />

        @include('website.home.sections.tech-strip')
        @include('website.home.sections.cta')
    </div>
</x-website>
