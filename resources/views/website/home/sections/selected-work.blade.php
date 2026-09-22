{{-- Featured case studies. Expects: $featuredProjects (Collection of App\Models\Project) --}}
@if ($featuredProjects->isNotEmpty())
    <section id="selected-work" class="section-y border-line w-full scroll-mt-19 border-b">
        <div class="site-container">
            <x-website.section-heading title="Products we've helped bring to life.">
                <a
                    href="{{ route('work.index') }}"
                    class="font-label-md text-label-md hover:text-primary text-ink inline-flex items-center gap-1 font-semibold tracking-wider uppercase transition-colors"
                >
                    View All Work →
                </a>
            </x-website.section-heading>

            <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    @include('website.work.partials.card', ['project' => $project])
                @endforeach
            </div>
        </div>
    </section>
@endif
