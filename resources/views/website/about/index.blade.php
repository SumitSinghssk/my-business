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

    $sectionTitle = 'font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl';
@endphp

<x-website
    :title="'About Us: Our Studio, Team & Approach | ' . $appName"
    description="We are a software and digital product studio combining strategy, design and engineering to build reliable products for ambitious businesses."
    :image="asset('images/website/about/team-office.jpg')"
>
    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home hero) --}}
        <section class="w-full border-b border-[#E1E5EA] pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20">
            <div class="site-container">
                <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
                    <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                        <span class="font-label-sm text-label-sm text-primary mb-4 font-semibold tracking-widest uppercase">
                            About {{ $appName }}
                        </span>

                        <h1
                            class="sm:mb-space-lg font-display mb-5 text-[38px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[60px] lg:text-[56px] xl:text-[68px] 2xl:text-[74px]"
                        >
                            A studio built around engineering discipline.
                        </h1>

                        <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">
                            We're a team of strategists, designers and engineers who help businesses turn ambitious ideas into reliable digital
                            products, and keep them running long after launch.
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
                                See Our Work →
                            </a>
                        </div>
                    </div>

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
                </div>

                {{-- Key numbers --}}
                <dl
                    class="mt-10 grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-[#E1E5EA] bg-[#E1E5EA] md:mt-14 lg:mt-16 lg:grid-cols-4"
                >
                    @foreach ($stats as $stat)
                        <div class="flex flex-col gap-1 bg-white p-4 lg:p-5">
                            <dt class="font-label-sm text-label-sm text-outline order-1 tracking-widest uppercase">{{ $stat['label'] }}</dt>
                            <dd
                                class="{{ $loop->index === 2 ? 'text-primary' : 'text-[#0A0A0A]' }} font-display order-2 text-[28px] leading-tight font-semibold tracking-[-0.03em] md:text-[34px] lg:text-[40px]"
                            >
                                {{ $stat['value'] }}
                            </dd>
                            <dd class="font-body-sm text-body-sm text-secondary order-3">{{ $stat['note'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>

        {{-- Our story --}}
        <section class="section-y w-full border-b border-[#E1E5EA]">
            <div class="site-container">
                <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-12">
                    <div
                        class="bg-surface-container-low relative aspect-4/3 w-full overflow-hidden rounded-lg border border-[#E1E5EA] sm:aspect-video lg:col-span-6 lg:aspect-auto lg:min-h-105"
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
                        <h2 class="{{ $sectionTitle }}">Started by engineers who were tired of throwaway software.</h2>
                        <div class="font-body-md text-body-md text-secondary flex flex-col gap-4 leading-relaxed">
                            <p>
                                {{ $appName }} began with a simple frustration: too many businesses were paying for software that looked finished on
                                launch day and started falling apart soon after. Handoffs were messy, documentation was missing, and nobody owned the
                                outcome.
                            </p>
                            <p>
                                We set out to work differently. Small senior teams, clear communication, and engineering standards we'd be proud to
                                hand over. Every product we build is designed to be understood, maintained and extended by the people who own it.
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

        {{-- Values --}}
        <section class="section-y w-full border-b border-[#E1E5EA] bg-white">
            <div class="site-container">
                <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
                    <h2 class="{{ $sectionTitle }}">The principles behind every decision.</h2>
                    <p class="font-body-md text-body-md text-secondary max-w-2xl">
                        These aren't posters on a wall. They shape how we scope work, review code, and talk to the people we build for.
                    </p>
                </div>

                <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($values as $value)
                        <div
                            class="group hover:border-primary-container flex h-full flex-col rounded-lg border border-[#E1E5EA] bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                        >
                            <span class="text-label-md text-primary mb-3 font-mono font-semibold">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
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

        @include('website.partials.process')

        {{-- Engagement model --}}
        <section class="section-y w-full border-b border-[#E1E5EA] bg-white">
            <div class="site-container">
                <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-12">
                    <div class="lg:pr-space-lg flex flex-col justify-center gap-5 lg:order-1 lg:col-span-6">
                        <span class="font-label-sm text-label-sm text-primary font-semibold tracking-widest uppercase">How We Engage</span>
                        <h2 class="{{ $sectionTitle }}">One team, fully focused on your product.</h2>

                        <div class="divide-y divide-[#E1E5EA] border-y border-[#E1E5EA]">
                            @foreach ($model as $item)
                                <div class="flex gap-4 py-4">
                                    <span class="text-label-md font-mono font-semibold text-[#4D8BFF]">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div>
                                        <h3 class="font-headline-sm text-[18px] font-semibold text-[#0A0A0A]">{{ $item['title'] }}</h3>
                                        <p class="font-body-sm text-body-sm text-secondary mt-1">{{ $item['text'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <a
                            href="{{ route('work.index') }}"
                            class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 self-start border-b border-[#0A0A0A] pb-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                        >
                            See what we've built →
                        </a>
                    </div>

                    <div
                        class="bg-surface-container-low relative aspect-4/3 w-full overflow-hidden rounded-lg border border-[#E1E5EA] sm:aspect-video lg:order-2 lg:col-span-6 lg:aspect-auto lg:min-h-105"
                    >
                        <img
                            src="{{ asset('images/website/about/collaboration.webp') }}"
                            width="1600"
                            height="1000"
                            alt="Product, design and engineering team collaborating"
                            class="absolute inset-0 h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </section>

        {{-- Team --}}
        <section class="section-y w-full border-b border-[#E1E5EA]">
            <div class="site-container">
                <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
                    <h2 class="{{ $sectionTitle }}">The people leading the work.</h2>
                    <p class="font-body-md text-body-md text-secondary max-w-2xl">
                        Senior specialists who stay close to every engagement, from the first workshop to long after launch.
                    </p>
                </div>

                <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($team as $person)
                        @php
                            $initials = collect(preg_split('/\s+/', trim($person['name'])))
                                ->filter()
                                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp

                        <div class="flex h-full flex-col rounded-lg border border-[#E1E5EA] bg-white p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="{{ $loop->even ? 'bg-primary-container' : 'bg-[#0A0A0A]' }} font-headline-sm flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-base font-semibold text-white"
                                    aria-hidden="true"
                                >
                                    {{ $initials }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="text-[17px] leading-snug font-semibold text-[#0A0A0A]">{{ $person['name'] }}</h3>
                                    <p class="font-label-sm text-primary text-[11px] tracking-wider uppercase">{{ $person['role'] }}</p>
                                </div>
                            </div>
                            <p class="font-body-sm text-body-sm text-secondary mt-3 border-t border-[#E1E5EA] pt-3">{{ $person['bio'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <x-website.faq class="bg-white" />

        @include('website.home.sections.tech-strip')
        @include('website.home.sections.cta')
    </div>
</x-website>
