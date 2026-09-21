@if ($latestPosts->isNotEmpty())
    <section class="section-y w-full border-b border-[#E1E5EA]">
        <div class="site-container">
            <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                    Perspectives on technology and craft.
                </h2>
                <a
                    href="{{ route('blog.index') }}"
                    class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 font-semibold tracking-wider uppercase transition-colors"
                >
                    Read All Insights →
                </a>
            </div>

            <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($latestPosts as $blog)
                    @include('website.blog.partials.card', ['blog' => $blog])
                @endforeach
            </div>
        </div>
    </section>
@endif
