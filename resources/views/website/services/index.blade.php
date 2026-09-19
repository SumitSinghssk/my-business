@php
    $appName = \App\Helpers\Settings::appName();

    $services = [
        [
            'id' => 'website',
            'title' => 'Website Development',
            'summary' => 'Fast, accessible and search-friendly websites that turn visitors into customers, built on a CMS your team can actually use.',
            'points' => ['Marketing sites & landing pages', 'Headless and Laravel CMS builds', 'Core Web Vitals & technical SEO', 'Analytics and conversion tracking'],
            'tags' => ['Laravel', 'Next.js', 'Tailwind CSS'],
            'image' => 'images/website/process/agile-build.jpg',
        ],
        [
            'id' => 'web-app',
            'title' => 'Web Applications',
            'summary' => 'Secure, scalable web platforms, from customer portals and dashboards to full SaaS products with billing and multi-tenancy.',
            'points' => ['SaaS platforms & internal tools', 'Role-based access and audit trails', 'Third-party & payment integrations', 'Real-time dashboards and reporting'],
            'tags' => ['React', 'Laravel', 'PostgreSQL'],
            'image' => 'images/website/work/orion-systems.jpg',
        ],
        [
            'id' => 'mobile-app',
            'title' => 'Mobile Apps',
            'summary' => 'Native-quality iOS and Android apps from a single codebase, designed for offline use, speed and app-store success.',
            'points' => ['iOS & Android from one codebase', 'Offline-first data sync', 'Push notifications & deep links', 'App Store and Play Store release'],
            'tags' => ['Flutter', 'React Native', 'Swift / Kotlin'],
            'image' => 'images/website/work/apex-pay-logistics.jpg',
        ],
        [
            'id' => 'custom-software',
            'title' => 'Custom Software',
            'summary' => 'Bespoke systems that automate the way your business actually works, replacing spreadsheets and disconnected tools.',
            'points' => ['Workflow & process automation', 'ERP, CRM and legacy integrations', 'APIs and microservices', 'Data migration from legacy systems'],
            'tags' => ['Node.js', 'Go', 'REST & GraphQL'],
            'image' => 'images/website/process/architecture.jpg',
        ],
        [
            'id' => 'ui-ux',
            'title' => 'UI/UX Design',
            'summary' => 'Research-led product design and design systems that make complex products feel simple and keep brand consistency at scale.',
            'points' => ['User research & journey mapping', 'Wireframes and clickable prototypes', 'Design systems & component libraries', 'Accessibility (WCAG 2.2) reviews'],
            'tags' => ['Figma', 'Design Tokens', 'Prototyping'],
            'image' => 'images/website/process/design-systems.jpg',
        ],
        [
            'id' => 'cloud-devops',
            'title' => 'Cloud & DevOps',
            'summary' => 'Reliable infrastructure and automated delivery pipelines so you can ship often, scale on demand and sleep at night.',
            'points' => ['AWS architecture & cost optimisation', 'CI/CD pipelines and infrastructure as code', 'Containers & Kubernetes', 'Monitoring, alerting & incident response'],
            'tags' => ['AWS', 'Docker', 'Terraform'],
            'image' => 'images/website/process/launch.jpg',
        ],
        [
            'id' => 'consulting',
            'title' => 'Architecture Consulting',
            'summary' => 'Independent technical audits and architecture guidance for teams facing scale, performance or reliability challenges.',
            'points' => ['Codebase & security audits', 'Performance and scalability reviews', 'Technology roadmap planning', 'Fractional CTO & team mentoring'],
            'tags' => ['System Design', 'Observability', 'SRE'],
            'image' => 'images/website/process/observability.jpg',
        ],
    ];

    $faqs = [
        ['q' => 'What does a typical project cost?', 'a' => 'Most websites start from around $10k and most custom web or mobile products range from $25k to $150k, depending on scope. After a short discovery call we provide a detailed, fixed-scope estimate.'],
        ['q' => 'How long does it take to build a product?', 'a' => 'Marketing websites usually take 4 to 8 weeks. Web and mobile applications typically take 8 to 16 weeks for a first production release, delivered in two-week sprints with working software at every step.'],
        ['q' => 'Which technologies do you use?', 'a' => 'We choose proven technology that fits your team and goals, most often Laravel, React, Next.js, Flutter, Node.js, PostgreSQL and AWS. We avoid experimental stacks that would be hard for you to maintain.'],
        ['q' => 'Will I own the source code?', 'a' => 'Yes. You own 100% of the code, designs and documentation, and everything lives in your own repositories and cloud accounts from day one.'],
    ];
@endphp

<x-website
    :title="'Software Development Services | ' . $appName"
    description="Website development, web applications, mobile apps, custom software, UI/UX design, cloud & DevOps and architecture consulting from one senior team."
>
    <section class="pt-space-xl w-full border-b border-[#E1E5EA] pb-10">
        <div class="site-container px-4">
            <div class="gap-gutter grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr]">
                <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                    <h1
                        class="mb-space-lg font-display text-[40px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[54px] lg:text-[48px] xl:text-[58px] 2xl:text-[64px]"
                    >
                        Software development services built around real business goals.
                    </h1>

                    <p class="mb-space-xl font-body-lg text-body-lg text-secondary max-w-xl">
                        Strategy, design and engineering under one roof. Pick a single service or bring us in end to end, from first idea to a product
                        running reliably in production.
                    </p>

                    <div class="gap-space-md flex flex-wrap items-center">
                        <a
                            href="{{ route('contact') }}"
                            class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors"
                        >
                            Start a Project →
                        </a>
                    </div>
                </div>

                <div class="relative aspect-1376/768 w-full overflow-hidden lg:aspect-auto lg:min-h-full">
                    <img
                        src="{{ asset('images/website/about/team-workspace.jpg') }}"
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
            <div id="{{ $service['id'] }}" class="group {{ $loop->even ? 'bg-[#F7F8FA]' : 'bg-white' }} scroll-mt-20 border-b border-[#E1E5EA]">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div
                        class="bg-surface-container-low {{ $loop->even ? 'lg:order-2' : '' }} relative aspect-16/10 w-full overflow-hidden lg:aspect-auto lg:min-h-120"
                    >
                        <img
                            src="{{ asset($service['image']) }}"
                            alt="{{ $service['title'] }} services"
                            width="1200"
                            height="750"
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
                                    {{ $service['title'] }}
                                </h2>
                            </div>

                            <p class="font-body-md text-body-md text-secondary leading-relaxed">
                                {{ $service['summary'] }}
                            </p>

                            <ul class="grid grid-cols-1 gap-x-6 gap-y-2.5 sm:grid-cols-2">
                                @foreach ($service['points'] as $point)
                                    <li class="font-body-sm text-body-sm text-on-surface flex items-start gap-2.5">
                                        <svg
                                            class="text-primary-container mt-0.5 h-4 w-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2.25"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <path d="M5 12.5l4.5 4.5L19 7.5" />
                                        </svg>
                                        <span>{{ $point }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="flex flex-col gap-4 border-t border-[#E1E5EA] pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($service['tags'] as $tag)
                                        <span
                                            class="font-label-sm rounded border border-[#E1E5EA] bg-white px-2 py-0.5 text-xs tracking-wider text-[#434655] uppercase"
                                        >
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>

                                <a
                                    href="{{ route('contact', ['service' => $service['id']]) }}"
                                    class="font-label-md hover:text-primary inline-flex shrink-0 items-center gap-1 self-start border-b border-[#0A0A0A] pb-1 text-sm font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors sm:self-auto"
                                >
                                    Discuss This Project →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA] bg-white">
        <div class="site-container px-4">
            <div class="mb-6 flex flex-col items-start gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                    Common questions about working with us.
                </h2>
            </div>

            <div x-data="{ open: 0 }" class="mx-auto max-w-3xl divide-y divide-[#E1E5EA] overflow-hidden rounded-lg border border-[#E1E5EA]">
                @foreach ($faqs as $faq)
                    <div>
                        <button
                            type="button"
                            x-on:click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
                            :aria-expanded="(open === {{ $loop->index }}).toString()"
                            class="flex w-full items-center justify-between gap-4 p-5 text-left transition-colors hover:bg-[#F7F8FA]"
                        >
                            <span class="font-headline-sm text-on-surface text-base font-semibold sm:text-[17px]">{{ $faq['q'] }}</span>
                            <svg
                                class="text-primary-container h-5 w-5 shrink-0 transition-transform duration-200"
                                :class="open === {{ $loop->index }} && 'rotate-45'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                aria-hidden="true"
                            >
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                        </button>
                        <div x-show="open === {{ $loop->index }}" x-transition.opacity x-cloak class="px-5 pb-5">
                            <p class="font-body-md text-body-md text-secondary leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('website.home.sections.cta')
    @include('website.partials.faq-schema', ['faqs' => $faqs])
</x-website>
