@php
    use App\Helpers\Settings;
    use App\Models\Blog;
    use App\Models\Page;
    use App\Models\Service;

    $appName = Settings::appName();
    $emails = array_values(array_filter(Settings::emails()));
    $phones = array_values(array_filter(Settings::phones()));
    $address = collect(Settings::addresses())->first(fn ($a) => filled($a['text'] ?? null));
    $socialLinks = array_values(array_filter(Settings::socialLinks(), fn ($s) => filled($s['url'] ?? null)));

    $columns = [
        'Company' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Services', 'url' => route('services')],
            ['label' => 'Work', 'url' => route('work.index')],
            ['label' => 'About', 'url' => route('about')],
            ['label' => 'Insights', 'url' => route('blog.index')],
            ['label' => 'Contact', 'url' => route('contact')],
        ],
    ];

    $footerServices = Service::active()
        ->ordered()
        ->take(6)
        ->get(['title', 'slug']);
    if ($footerServices->isNotEmpty()) {
        $columns['Services'] = $footerServices->map(fn ($service) => ['label' => $service->title, 'url' => route('services.show', $service->slug)])->all();
    }

    $latestPosts = Blog::published()
        ->latestPublished()
        ->take(4)
        ->get(['title', 'slug']);
    if ($latestPosts->isNotEmpty()) {
        $columns['Latest Insights'] = $latestPosts->map(fn ($post) => ['label' => \Illuminate\Support\Str::limit($post->title, 42), 'url' => route('blog.show', $post->slug)])->all();
    }

    $legalPages = Page::published()
        ->orderBy('title')
        ->get(['title', 'slug']);
@endphp

<footer class="w-full border-t border-[#1C1C1C] bg-[#0A0A0A] text-white">
    <div class="site-container pt-space-2xl pb-space-xl">
        <div class="gap-gutter pb-space-2xl grid grid-cols-1 border-b border-[#1C1C1C] lg:grid-cols-12">
            <div class="gap-space-xl lg:pr-space-xl flex flex-col justify-between pr-0 lg:col-span-4">
                <div class="space-y-space-md">
                    <a
                        href="{{ route('home') }}"
                        class="font-headline-md text-headline-md inline-block font-semibold tracking-tight text-white uppercase"
                    >
                        {{ $appName }}
                    </a>
                    <p class="font-body-lg text-body-lg max-w-sm text-[#A0A0A0]">
                        Designing and engineering digital products for ambitious businesses.
                    </p>
                </div>

                @if ($socialLinks)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($socialLinks as $social)
                            <a
                                href="{{ $social['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer me"
                                class="font-label-sm text-label-sm border border-[#2A2A2A] px-3 py-1.5 tracking-wider text-[#D1D5DB] uppercase transition-colors hover:border-white hover:text-white"
                            >
                                {{ $social['platform'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="gap-gutter grid grid-cols-2 md:grid-cols-4 lg:col-span-8">
                @foreach ($columns as $heading => $items)
                    <nav class="space-y-space-md flex flex-col" aria-label="{{ $heading }}">
                        <span class="font-label-sm text-label-sm tracking-widest text-[#8E91A0] uppercase">{{ $heading }}</span>
                        <ul class="space-y-space-xs">
                            @foreach ($items as $item)
                                <li class="py-1">
                                    <a href="{{ $item['url'] }}" class="font-body-sm text-body-sm text-[#D1D5DB] transition-colors hover:text-white">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endforeach

                <div class="space-y-space-md flex flex-col">
                    <span class="font-label-sm text-label-sm tracking-widest text-[#8E91A0] uppercase">Get in Touch</span>
                    <ul class="space-y-space-xs font-body-sm text-body-sm text-[#D1D5DB]">
                        @foreach (array_slice($emails, 0, 1) as $email)
                            <li class="py-1">
                                <a href="mailto:{{ $email }}" class="break-all transition-colors hover:text-white">{{ $email }}</a>
                            </li>
                        @endforeach

                        @foreach (array_slice($phones, 0, 1) as $phone)
                            <li class="py-1">
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="transition-colors hover:text-white">
                                    {{ $phone }}
                                </a>
                            </li>
                        @endforeach

                        @if ($address)
                            <li class="py-1 whitespace-pre-line text-[#A0A0A0]">{{ $address['text'] }}</li>
                        @endif

                        <li class="py-1">
                            <a href="{{ route('contact') }}" class="hover:text-primary-fixed-dim font-semibold text-white transition-colors">
                                Start a Project →
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="gap-space-md pt-space-lg flex flex-col items-center justify-between md:flex-row">
            <p class="font-label-sm text-label-sm tracking-wider text-[#8E91A0] uppercase">
                © {{ date('Y') }} {{ $appName }}. All Rights Reserved.
            </p>

            @if ($legalPages->isNotEmpty())
                <nav
                    class="gap-x-space-lg gap-y-space-xs font-label-sm text-label-sm flex flex-wrap items-center justify-center tracking-wider text-[#8E91A0] uppercase"
                    aria-label="Legal"
                >
                    @foreach ($legalPages as $legalPage)
                        <a href="{{ route('page.show', $legalPage->slug) }}" class="transition-colors hover:text-white">{{ $legalPage->title }}</a>
                    @endforeach
                </nav>
            @endif
        </div>
    </div>
</footer>
