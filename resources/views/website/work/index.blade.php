@php
    $page = $projects->currentPage();
    $pageTitle = ($activeService ? $activeService->title . ' Projects' : 'Our Work & Case Studies') . ($page > 1 ? ' – Page ' . $page : '') . ' | ' . $appName;
    $description = $activeService
        ? 'Case studies of ' . Str::lower($activeService->title) . ' projects delivered by ' . $appName . '.'
        : 'Case studies of websites, web applications, mobile apps and custom software delivered by ' . $appName . '.';
    $tabClass = 'blog-tab font-label-sm text-label-sm shrink-0 border px-3.5 py-2 tracking-wider whitespace-nowrap uppercase transition-colors';
@endphp

<x-website
    :title="$pageTitle"
    :description="$description"
    :canonical="route('work.index', array_filter(['service' => $activeService?->slug, 'page' => $page > 1 ? $page : null]))"
    :image="$projects->first()?->featured_image_url"
>
    @push('heads')
        <x-website.json-ld :data="\App\Support\StructuredData::projectList($projects, $activeService)" />
    @endpush

    <div class="bg-surface text-on-surface flex w-full flex-col">
        <section class="border-line w-full border-b pt-8 pb-8 md:pt-12 md:pb-10 lg:pt-16 lg:pb-12">
            <div class="site-container">
                <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                    <div class="max-w-3xl">
                        <span class="font-label-sm text-label-sm text-primary mb-4 block font-semibold tracking-widest uppercase">Our Work</span>
                        <h1
                            class="font-display text-ink text-[36px] leading-[1.05] font-semibold tracking-[-0.04em] sm:text-[52px] lg:text-[56px] xl:text-[64px]"
                        >
                            {{ $activeService ? $activeService->title . ' projects.' : 'Products we have helped bring to life.' }}
                        </h1>
                        <p class="font-body-lg text-body-lg text-secondary mt-5 max-w-2xl">
                            {{ $activeService?->excerpt ?: 'Selected case studies: the problem each client faced, what we built, and the results it delivered.' }}
                        </p>
                    </div>
                    <span class="font-label-sm text-label-sm text-secondary shrink-0 tracking-wider uppercase">
                        {{ $totalProjects }} {{ Str::plural('Project', $totalProjects) }}
                    </span>
                </div>

                @if ($services->isNotEmpty())
                    <nav class="border-line mt-8 flex gap-1.5 overflow-x-auto border-t pt-4 lg:mt-10" aria-label="Filter projects by service">
                        <a
                            href="{{ route('work.index') }}"
                            @class([$tabClass, 'is-active' => ! $activeService])
                            @if (! $activeService) aria-current="page" @endif
                        >
                            All ({{ $totalProjects }})
                        </a>
                        @foreach ($services as $service)
                            <a
                                href="{{ route('work.index', ['service' => $service->slug]) }}"
                                @class([$tabClass, 'is-active' => $activeService?->is($service)])
                                @if ($activeService?->is($service)) aria-current="page" @endif
                            >
                                {{ $service->title }} ({{ $service->projects_count }})
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </section>

        <section class="section-y w-full">
            <div class="site-container">
                @if ($projects->isNotEmpty())
                    <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($projects as $project)
                            @include('website.work.partials.card', ['project' => $project, 'heading' => 'h2', 'eager' => $loop->index < 3 && $page === 1, 'priority' => $loop->first && $page === 1])
                        @endforeach
                    </div>
                @else
                    <div class="px-space-lg py-space-2xl border-outline-variant rounded-lg border border-dashed bg-white text-center">
                        <p class="font-headline-sm text-headline-sm text-on-surface font-semibold">No projects published yet.</p>
                    </div>
                @endif

                @if ($projects->hasPages())
                    <div class="pt-space-xl">
                        {{ $projects->onEachSide(1)->links('website.partials.pagination') }}
                    </div>
                @endif
            </div>
        </section>

        <x-website.faq class="bg-white" />

        @include('website.home.sections.cta')
    </div>
</x-website>
