{{-- Website footer. Data comes from App\View\Composers\WebsiteFooterComposer. --}}
<footer class="border-ink-line bg-ink w-full border-t text-white">
    <div class="site-container pt-space-2xl pb-space-xl">
        <div class="gap-gutter pb-space-2xl border-ink-line grid grid-cols-1 border-b lg:grid-cols-12">
            <div class="gap-space-xl lg:pr-space-xl flex flex-col justify-between pr-0 lg:col-span-4">
                <div class="space-y-space-md">
                    <a
                        href="{{ route('home') }}"
                        class="font-headline-md text-headline-md inline-block font-semibold tracking-tight text-white uppercase"
                    >
                        {{ $appName }}
                    </a>
                    <p class="font-body-lg text-body-lg text-dark-muted max-w-sm">
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
                                aria-label="{{ $appName }} on {{ $social['platform'] ?: 'social media' }}"
                                title="{{ $social['platform'] }}"
                                class="border-ink-line-strong text-dark-text flex h-9 w-9 items-center justify-center rounded-md border transition-colors hover:border-white hover:text-white"
                            >
                                <x-icons.social :platform="$social['platform'] ?? ''" class="h-4 w-4" aria-hidden="true" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="gap-gutter grid grid-cols-2 md:grid-cols-4 lg:col-span-8">
                @foreach ($columns as $heading => $items)
                    <nav class="space-y-space-md flex flex-col" aria-label="{{ $heading }}">
                        <span class="font-label-sm text-label-sm text-dark-subtle tracking-widest uppercase">{{ $heading }}</span>
                        <ul class="space-y-space-xs">
                            @foreach ($items as $item)
                                <li class="py-1">
                                    <a href="{{ $item['url'] }}" class="font-body-sm text-body-sm text-dark-text transition-colors hover:text-white">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endforeach

                <div class="space-y-space-md flex flex-col">
                    <span class="font-label-sm text-label-sm text-dark-subtle tracking-widest uppercase">Get in Touch</span>
                    <ul class="space-y-space-xs font-body-sm text-body-sm text-dark-text">
                        @if ($email)
                            <li class="py-1">
                                <a href="mailto:{{ $email }}" class="break-all transition-colors hover:text-white">{{ $email }}</a>
                            </li>
                        @endif

                        @if ($phone)
                            <li class="py-1">
                                <a href="{{ \App\Helpers\Settings::telHref($phone) }}" class="transition-colors hover:text-white">
                                    {{ $phone }}
                                </a>
                            </li>
                        @endif

                        @if ($address)
                            <li class="text-dark-muted py-1 whitespace-pre-line">{{ $address }}</li>
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
            <p class="font-label-sm text-label-sm text-dark-subtle tracking-wider uppercase">
                © {{ date('Y') }} {{ $appName }}. All Rights Reserved.
            </p>

            @if ($legalPages->isNotEmpty())
                <nav
                    class="gap-x-space-lg gap-y-space-xs font-label-sm text-label-sm text-dark-subtle flex flex-wrap items-center justify-center tracking-wider uppercase"
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
