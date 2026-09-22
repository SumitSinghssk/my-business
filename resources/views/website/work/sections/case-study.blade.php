{{-- Project detail: the case study with the sticky project details sidebar. --}}
{{-- Case study + sticky project details --}}
<section class="section-y border-line w-full border-b">
    <div class="site-container gap-section grid grid-cols-1 items-start lg:grid-cols-12">
        <article class="article-prose is-plain border-line min-w-0 rounded-lg border bg-white p-4 shadow-sm md:p-6 lg:col-span-8 lg:p-8">
            {!! $project->content !!}
        </article>

        <aside class="lg:sticky lg:top-24 lg:col-span-4">
            <div class="divide-line border-line divide-y rounded-lg border bg-white shadow-sm">
                @if ($details || $service || $project->project_url)
                    <div class="p-4 lg:p-5">
                        <span class="{{ $sideLabel }}">Project details</span>
                        <dl class="mt-3 space-y-3">
                            @foreach ($details as $label => $value)
                                <div class="flex items-baseline justify-between gap-4">
                                    <dt class="font-body-sm text-body-sm text-secondary">{{ $label }}</dt>
                                    <dd class="font-body-sm text-body-sm text-ink text-right font-semibold">{{ $value }}</dd>
                                </div>
                            @endforeach

                            @if ($service)
                                <div class="flex items-baseline justify-between gap-4">
                                    <dt class="font-body-sm text-body-sm text-secondary">Service</dt>
                                    <dd class="font-body-sm text-body-sm text-right font-semibold">
                                        <a
                                            href="{{ route('services.show', $service->slug) }}"
                                            class="text-primary hover:text-primary-container transition-colors"
                                        >
                                            {{ $service->title }}
                                        </a>
                                    </dd>
                                </div>
                            @endif

                            @if ($project->project_url)
                                <div class="flex items-baseline justify-between gap-4">
                                    <dt class="font-body-sm text-body-sm text-secondary">Website</dt>
                                    <dd class="font-body-sm text-body-sm min-w-0 truncate text-right font-semibold">
                                        <a
                                            href="{{ $project->project_url }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="text-primary hover:text-primary-container transition-colors"
                                        >
                                            {{ preg_replace('#^https?://(www\.)?#', '', rtrim($project->project_url, '/')) }} ↗
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                @if ($tags)
                    <div class="p-4 lg:p-5">
                        <span class="{{ $sideLabel }}">Technologies</span>
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($tags as $tag)
                                <span
                                    class="font-label-sm border-line bg-canvas text-on-surface-variant rounded border px-2 py-0.5 text-xs tracking-wider uppercase"
                                >
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="p-4 lg:p-5">
                    <a
                        href="{{ $contactUrl }}"
                        class="font-label-md text-label-md hover:bg-primary-container bg-ink inline-flex w-full items-center justify-center px-4 py-3 tracking-wider text-white uppercase transition-colors"
                    >
                        Start a Similar Project →
                    </a>
                </div>
            </div>
        </aside>
    </div>
</section>
