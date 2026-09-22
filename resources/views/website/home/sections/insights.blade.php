@if ($latestPosts->isNotEmpty())
    <section class="section-y border-line w-full border-b">
        <div class="site-container">
            <x-website.section-heading title="Perspectives on technology and craft.">
                <a
                    href="{{ route('blog.index') }}"
                    class="font-label-md text-label-md hover:text-primary inline-flex items-center gap-1 font-semibold tracking-wider uppercase transition-colors"
                >
                    Read All Insights →
                </a>
            </x-website.section-heading>

            <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($latestPosts as $blog)
                    @include('website.blog.partials.card', ['blog' => $blog])
                @endforeach
            </div>
        </div>
    </section>
@endif
