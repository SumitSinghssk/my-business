{{-- Service detail: article content with the sticky summary sidebar. --}}
{{-- Content + sticky summary --}}
<section class="section-y border-line w-full border-b">
    <div class="site-container gap-section grid grid-cols-1 items-start lg:grid-cols-12">
        <article class="article-prose is-plain border-line min-w-0 rounded-lg border bg-white p-4 shadow-sm md:p-6 lg:col-span-8 lg:p-8">
            {!! $service->content !!}
        </article>

        <aside class="lg:sticky lg:top-24 lg:col-span-4">
            <div class="divide-line border-line divide-y rounded-lg border bg-white shadow-sm">
                @if ($highlights)
                    <div class="p-4 lg:p-5">
                        <span class="{{ $sideLabel }}">What's included</span>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($highlights as $point)
                                <li class="font-body-sm text-body-sm text-on-surface flex items-start gap-2.5">
                                    <x-icons.check class="text-primary-container mt-0.5 h-4 w-4 shrink-0" aria-hidden="true" stroke-width="2.25" />
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
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
                        Discuss This Project →
                    </a>
                </div>
            </div>
        </aside>
    </div>
</section>
