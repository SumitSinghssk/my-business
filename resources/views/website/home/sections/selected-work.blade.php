{{-- Featured case studies. Expects: $featuredProjects (Collection of App\Models\Project) --}}
@if ($featuredProjects->isNotEmpty())
    <section id="selected-work" class="section-y w-full scroll-mt-19 border-b border-[#E1E5EA]">
        <div class="site-container">
            <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                    Products we've helped bring to life.
                </h2>
                <a
                    href="{{ route('work.index') }}"
                    class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 font-semibold tracking-wider text-[#0A0A0A] uppercase transition-colors"
                >
                    View All Work →
                </a>
            </div>

            <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    @include('website.work.partials.card', ['project' => $project])
                @endforeach
            </div>
        </div>
    </section>
@endif
