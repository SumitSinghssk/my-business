{{-- 404 page. Recent articles ($latestPosts) come from App\View\Composers\NotFoundComposer. --}}
@php
    $links = [
        ['label' => 'Services', 'text' => 'What we build and how we can help.', 'url' => route('services')],
        ['label' => 'Insights', 'text' => 'Engineering articles and case write-ups.', 'url' => route('blog.index')],
        ['label' => 'About', 'text' => 'The team and principles behind the work.', 'url' => route('about')],
        ['label' => 'Contact', 'text' => 'Tell us about your project.', 'url' => route('contact')],
    ];
@endphp

<x-website :title="'Page Not Found | ' . $appName" description="The page you are looking for could not be found." :noindex="true">
    <section class="bg-surface-container-lowest py-space-2xl border-line w-full border-b">
        <div class="site-container">
            <div class="mx-auto max-w-3xl text-center">
                <span class="text-label-md text-primary font-mono font-semibold">[ ERROR 404 ]</span>
                <h1 class="mt-space-md font-display text-ink text-[44px] leading-[1.05] font-semibold tracking-[-0.04em] sm:text-[64px]">
                    This page doesn't exist.
                </h1>
                <p class="mt-space-md font-body-lg text-body-lg text-secondary mx-auto max-w-xl">
                    The link may be broken or the page may have moved. Here are a few places to pick up from.
                </p>
                <div class="mt-space-xl gap-space-md flex flex-wrap items-center justify-center">
                    <x-website.button :href="route('home')" :fluid="false">Back to Home →</x-website.button>
                    <x-website.button :href="route('contact')" variant="outline" :fluid="false">Contact Us</x-website.button>
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
                        class="group p-space-lg hover:border-primary-container border-line rounded-lg border bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <span
                            class="font-headline-sm text-headline-sm group-hover:text-primary-container text-ink flex items-center justify-between font-semibold transition-colors"
                        >
                            {{ $link['label'] }}
                            <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
                        </span>
                        <span class="mt-space-xs font-body-sm text-body-sm text-secondary block">{{ $link['text'] }}</span>
                    </a>
                @endforeach
            </div>

            @if ($latestPosts->isNotEmpty())
                <div class="mt-space-xl p-space-lg border-line rounded-lg border bg-white">
                    <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Latest Insights</span>
                    <ul class="mt-space-sm divide-line divide-y">
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
