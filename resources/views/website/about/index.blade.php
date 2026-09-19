@php
    $appName = \App\Helpers\Settings::appName();

    $stats = [
        ['value' => '2024', 'label' => 'Founded', 'note' => 'Independent studio'],
        ['value' => '40+', 'label' => 'Products Shipped', 'note' => 'Web, mobile & platforms'],
        ['value' => '99.98%', 'label' => 'Production Uptime', 'note' => 'Across client systems'],
        ['value' => '14', 'label' => 'Industry Awards', 'note' => 'Engineering & design'],
    ];

    $values = [
        ['title' => 'Clarity over cleverness', 'text' => 'We choose simple, well-understood solutions and write code the next engineer can read on their first day.'],
        ['title' => 'Own the outcome', 'text' => 'We measure success by what your product achieves in the real world, not by tickets closed or hours logged.'],
        ['title' => 'Craft in the details', 'text' => 'Accessibility, performance and edge cases are part of the work, not polish we add if there is time left.'],
        ['title' => 'Honest partnership', 'text' => 'We share bad news early, explain trade-offs plainly, and recommend less work when less is the right answer.'],
    ];

    $model = [
        ['title' => 'Dedicated pods', 'text' => 'A small, senior team of product, design and engineering works only on your product for the length of the engagement.'],
        ['title' => 'Transparent delivery', 'text' => 'Two-week sprints, live demos and a shared board. You always know what shipped, what is next and why.'],
        ['title' => 'Full ownership', 'text' => 'Code, designs and documentation live in your accounts from day one. No lock-in, no black boxes.'],
    ];

    $team = [
        ['name' => 'Elena Vance', 'role' => 'Principal Systems Architect', 'bio' => 'Designs fault-tolerant platforms and leads our core architecture practice.'],
        ['name' => 'Daniel Okafor', 'role' => 'Head of Engineering', 'bio' => 'Runs our engineering pods and keeps delivery predictable at scale.'],
        ['name' => 'Sofia Thorne', 'role' => 'Head of Product Design', 'bio' => 'Leads research, design systems and interaction design across products.'],
        ['name' => 'Priya Raman', 'role' => 'Director of Delivery', 'bio' => 'Partners with clients on scope, planning and long-term product roadmaps.'],
    ];
@endphp

<x-website
    :title="'About Us | ' . $appName"
    description="We are a software and digital product studio combining strategy, design and engineering to build reliable products for ambitious businesses."
>
    <section class="pt-space-xl w-full border-b border-[#E1E5EA] pb-10">
        <div class="site-container px-4">
            <div class="gap-gutter grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr]">
                <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                    <div class="mb-space-md inline-flex items-center gap-2">
                        <span class="bg-primary-container h-2 w-2 animate-pulse rounded-full"></span>
                        <span class="font-label-sm text-label-sm text-secondary font-semibold tracking-widest uppercase">About {{ $appName }}</span>
                    </div>

                    <h1
                        class="mb-space-lg font-display text-[44px] leading-[1.03] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[60px] lg:text-[56px] xl:text-[68px] 2xl:text-[74px]"
                    >
                        A studio built around engineering discipline.
                    </h1>

                    <p class="mb-space-xl font-body-lg text-body-lg text-secondary max-w-xl">
                        We're a team of strategists, designers and engineers who help businesses turn ambitious ideas into reliable digital products
                        and keep them running long after launch.
                    </p>

                    <div class="gap-space-md flex flex-wrap items-center">
                        <a
                            href="{{ route('contact') }}"
                            class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors"
                        >
                            Start a Project →
                        </a>
                        <a
                            href="{{ route('home') . '#selected-work' }}"
                            class="px-space-xl font-label-md text-label-md inline-flex items-center justify-center border border-[#E1E5EA] bg-white py-4 tracking-wider text-[#0A0A0A] uppercase transition-all hover:border-[#0A0A0A]"
                        >
                            See Our Work →
                        </a>
                    </div>
                </div>

                <div class="relative aspect-16/10 w-full overflow-hidden lg:aspect-auto lg:min-h-full">
                    <img
                        src="{{ asset('images/website/about/team-office.jpg') }}"
                        width="1600"
                        height="1000"
                        alt="Our engineering team working together in the studio"
                        class="absolute inset-0 h-full w-full object-cover object-top-left"
                        fetchpriority="high"
                    />
                </div>
            </div>
        </div>
    </section>

    <section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA] bg-white">
        <div class="site-container px-4">
            <div class="grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-[#E1E5EA] bg-[#E1E5EA] lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="flex flex-col gap-1 bg-[#F7F8FA] p-4 md:p-5 lg:p-6">
                        <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">{{ $stat['label'] }}</span>
                        <span
                            class="{{ $loop->index === 2 ? 'text-primary' : 'text-[#0A0A0A]' }} font-display text-[30px] leading-tight font-semibold tracking-[-0.03em] md:text-[36px] lg:text-[40px]"
                        >
                            {{ $stat['value'] }}
                        </span>
                        <span class="font-body-sm text-body-sm text-secondary">{{ $stat['note'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full overflow-hidden border-b border-[#E1E5EA]">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="bg-surface-container-low relative aspect-16/10 w-full overflow-hidden lg:aspect-auto lg:min-h-120">
                <img
                    src="{{ asset('images/website/about/code-review.jpg') }}"
                    alt="Two engineers reviewing code together"
                    width="1600"
                    height="1000"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                />
            </div>

            <div class="flex flex-col justify-center px-4 py-8 md:px-8 md:py-10 lg:px-12 lg:py-14 xl:px-20">
                <div class="flex w-full max-w-xl flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                            Started by engineers who were tired of throwaway software.
                        </h2>
                    </div>

                    <div class="font-body-md text-body-md text-secondary flex flex-col gap-4 leading-relaxed">
                        <p>
                            {{ $appName }} began with a simple frustration: too many businesses were paying for software that looked finished on
                            launch day and started falling apart soon after. Handoffs were messy, documentation was missing, and nobody owned the
                            outcome.
                        </p>
                        <p>
                            We set out to work differently. Small senior teams, clear communication, and engineering standards we'd be proud to hand
                            over. Every product we build is designed to be understood, maintained and extended by the people who own it.
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

    <section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA] bg-white">
        <div class="site-container px-4">
            <div class="mb-6 flex flex-col items-start gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                    The principles behind every decision.
                </h2>
                <p class="font-body-md text-body-md text-secondary max-w-2xl">
                    These aren't posters on a wall. They shape how we scope work, review code, and talk to the people we build for.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                @foreach ($values as $value)
                    <div
                        class="group hover:border-primary-container flex h-full flex-col rounded-lg border border-[#E1E5EA] bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <span class="text-label-md text-primary mb-3 font-mono font-semibold">
                            [ {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} ]
                        </span>
                        <h3
                            class="font-headline-sm text-headline-sm group-hover:text-primary-container mb-2 font-semibold text-[#0A0A0A] transition-colors"
                        >
                            {{ $value['title'] }}
                        </h3>
                        <p class="font-body-md text-body-md text-secondary">{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full overflow-hidden border-b border-[#E1E5EA] bg-[#F7F8FA]">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="bg-surface-container-low relative aspect-16/10 w-full overflow-hidden lg:order-2 lg:aspect-auto lg:min-h-120">
                <img
                    src="{{ asset('images/website/about/collaboration.jpg') }}"
                    alt="Product, design and engineering team collaborating"
                    width="1600"
                    height="1000"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                />
            </div>

            <div class="flex flex-col justify-center px-4 py-8 md:px-8 md:py-10 lg:order-1 lg:items-end lg:px-12 lg:py-14 xl:px-20">
                <div class="flex w-full max-w-xl flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                            One team, fully focused on your product.
                        </h2>
                    </div>

                    <div class="divide-y divide-[#E1E5EA] border-y border-[#E1E5EA]">
                        @foreach ($model as $item)
                            <div class="flex gap-4 py-4">
                                <span class="text-label-md text-primary-container font-mono font-semibold">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <div>
                                    <h3 class="font-headline-sm text-[18px] font-semibold text-[#0A0A0A]">
                                        {{ $item['title'] }}
                                    </h3>

                                    <p class="font-body-sm text-body-sm text-secondary mt-1">
                                        {{ $item['text'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a
                        href="{{ route('home') }}#selected-work"
                        class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 self-start border-b border-[#0A0A0A] pb-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                    >
                        See what we've built →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA] bg-white">
        <div class="site-container px-4">
            <div class="mb-6 flex flex-col items-start gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                    The people leading the work.
                </h2>
                <a
                    href="{{ route('contact') }}"
                    class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                >
                    Work With Us →
                </a>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                @foreach ($team as $person)
                    @php
                        $initials = collect(preg_split('/\s+/', trim($person['name'])))
                            ->filter()
                            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp

                    <div class="flex h-full flex-col rounded-lg border border-[#E1E5EA] bg-white p-5 shadow-sm">
                        <span
                            class="{{ $loop->even ? 'bg-primary-container' : 'bg-[#0A0A0A]' }} font-headline-sm text-headline-sm mb-4 flex h-14 w-14 items-center justify-center font-semibold text-white"
                            aria-hidden="true"
                        >
                            {{ $initials }}
                        </span>
                        <h3 class="font-headline-sm text-headline-sm font-semibold text-[#0A0A0A]">{{ $person['name'] }}</h3>
                        <p class="font-label-sm text-label-sm text-primary mt-1 tracking-wider uppercase">{{ $person['role'] }}</p>
                        <p class="font-body-sm text-body-sm text-secondary mt-3 border-t border-[#E1E5EA] pt-3">{{ $person['bio'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('website.home.sections.tech-strip')
    @include('website.home.sections.cta')
</x-website>
