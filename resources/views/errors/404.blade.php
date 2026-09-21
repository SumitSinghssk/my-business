@php
    $appName = \App\Helpers\Settings::appName();

    $links = [
        ['label' => 'Services', 'text' => 'What we build and how we can help.', 'url' => route('services')],
        ['label' => 'Insights', 'text' => 'Engineering articles and case write-ups.', 'url' => route('blog.index')],
        ['label' => 'About', 'text' => 'The team and principles behind the work.', 'url' => route('about')],
        ['label' => 'Contact', 'text' => 'Tell us about your project.', 'url' => route('contact')],
    ];

    try {
        $latestPosts = \App\Models\Blog::published()
            ->latestPublished()
            ->take(3)
            ->get(['title', 'slug']);
    } catch (\Throwable) {
        $latestPosts = collect();
    }
@endphp

<x-website :title="'Page Not Found | ' . $appName" description="The page you are looking for could not be found." :noindex="true">
    <section class="bg-surface-container-lowest py-space-2xl w-full border-b border-[#E1E5EA]">
        <div class="site-container">
            <div class="mx-auto max-w-3xl text-center">
                <span class="text-label-md text-primary font-mono font-semibold">[ ERROR 404 ]</span>
                <h1 class="mt-space-md font-display text-[44px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[64px]">
                    This page doesn't exist.
                </h1>
                <p class="mt-space-md font-body-lg text-body-lg text-secondary mx-auto max-w-xl">
                    The link may be broken or the page may have moved. Here are a few places to pick up from.
                </p>
                <div class="mt-space-xl gap-space-md flex flex-wrap items-center justify-center">
                    <a
                        href="{{ route('home') }}"
                        class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors"
                    >
                        Back to Home →
                    </a>
                    <a
                        href="{{ route('contact') }}"
                        class="px-space-xl font-label-md text-label-md inline-flex items-center justify-center border border-[#E1E5EA] bg-white py-4 tracking-wider text-[#0A0A0A] uppercase transition-all hover:border-[#0A0A0A]"
                    >
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-space-2xl w-full">
        <div class="site-container">
            <div class="gap-space-md grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($links as $link)
                    <a
                        href="{{ $link['url'] }}"
                        class="group p-space-lg hover:border-primary-container rounded-lg border border-[#E1E5EA] bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <span
                            class="font-headline-sm text-headline-sm group-hover:text-primary-container flex items-center justify-between font-semibold text-[#0A0A0A] transition-colors"
                        >
                            {{ $link['label'] }}
                            <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
                        </span>
                        <span class="mt-space-xs font-body-sm text-body-sm text-secondary block">{{ $link['text'] }}</span>
                    </a>
                @endforeach
            </div>

            @if ($latestPosts->isNotEmpty())
                <div class="mt-space-xl p-space-lg rounded-lg border border-[#E1E5EA] bg-white">
                    <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Latest Insights</span>
                    <ul class="mt-space-sm divide-y divide-[#E1E5EA]">
                        @foreach ($latestPosts as $post)
                            <li>
                                <a
                                    href="{{ route('blog.show', $post->slug) }}"
                                    class="gap-space-md py-space-sm font-body-md text-body-md text-on-surface hover:text-primary-container flex items-center justify-between transition-colors"
                                >
                                    <span>{{ $post->title }}</span>
                                    <span aria-hidden="true">→</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>
</x-website>
