@php
    $links = [
        ['label' => 'Home', 'url' => url('/'), 'pattern' => '/'],
        ['label' => 'Services', 'url' => url('/services'), 'pattern' => 'services*'],
        ['label' => 'Industries', 'url' => url('/industries'), 'pattern' => 'industries*'],
        ['label' => 'Work', 'url' => url('/work'), 'pattern' => 'work*'],
        ['label' => 'About', 'url' => url('/about'), 'pattern' => 'about*'],
        ['label' => 'Blog', 'url' => url('/blog'), 'pattern' => 'blog*'],
    ];

    $currentPath = request()->path();

    $isActive = function ($pattern) use ($currentPath) {
        if ($pattern === '/') {
            return $currentPath === '/' || $currentPath === '';
        }
        return request()->is($pattern);
    };
@endphp

<header
    x-data="{ mobileMenuOpen: false }"
    x-on:keydown.escape.window="mobileMenuOpen = false"
    x-effect="document.documentElement.classList.toggle('overflow-hidden', mobileMenuOpen)"
    class="sticky top-0 z-50 w-full border-b border-slate-100/80 bg-white/80 backdrop-blur-md transition-all duration-200"
>
    <div class="custom-width mx-auto p-2">
        <div class="flex items-center justify-between">
            <a
                href="{{ url('/') }}"
                class="group flex items-center gap-2 rounded-lg text-sm font-semibold tracking-tight text-slate-900 transition-opacity hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                aria-label="Home"
                x-on:click="mobileMenuOpen = false"
            >
                <div
                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white shadow-sm shadow-blue-500/30"
                >
                    B
                </div>
                <span class="text-base font-bold text-slate-800">My Business</span>
            </a>

            <nav class="hidden items-center lg:flex" aria-label="Main Navigation">
                <div class="flex items-center gap-0.5 rounded-full border border-slate-200/60 bg-slate-100/60 p-1 backdrop-blur-sm">
                    @foreach ($links as $link)
                        @php
                            $active = $isActive($link['pattern']);
                        @endphp

                        <a
                            href="{{ $link['url'] }}"
                            @if ($active) aria-current="page" @endif
                            class="{{
                                $active ? 'bg-white font-semibold text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900'
                            }} relative rounded-full px-3.5 py-1 text-sm font-medium transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </nav>

            <div class="hidden items-center lg:flex">
                <a
                    href="{{ url('/contact') }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-full bg-slate-900 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-600 hover:shadow-md hover:shadow-blue-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 active:scale-[0.97]"
                >
                    <span>Start a Project</span>
                    <svg class="h-3.5 w-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <div class="flex items-center lg:hidden">
                <button
                    type="button"
                    x-on:click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen.toString()"
                    aria-controls="mobile-navigation"
                    aria-label="Toggle navigation menu"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                >
                    <svg x-show="!mobileMenuOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition-opacity duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="mobileMenuOpen = false"
        class="fixed inset-0 z-40 h-screen bg-slate-900/20 backdrop-blur-xs lg:hidden"
        aria-hidden="true"
    ></div>

    <div
        id="mobile-navigation"
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 z-50 flex h-screen w-72 flex-col bg-white/95 shadow-xl backdrop-blur-xl lg:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Mobile navigation"
    >
        <div class="flex h-14 items-center justify-between border-b border-slate-100 px-5">
            <span class="text-xs font-bold tracking-wider text-slate-400 uppercase">Menu</span>
            <button
                type="button"
                x-on:click="mobileMenuOpen = false"
                aria-label="Close menu"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus:outline-none"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-3" aria-label="Mobile Navigation">
            @foreach ($links as $link)
                @php
                    $active = $isActive($link['pattern']);
                @endphp

                <a
                    href="{{ $link['url'] }}"
                    @if ($active) aria-current="page" @endif
                    x-on:click="mobileMenuOpen = false"
                    class="{{
                        $active ? 'bg-blue-50 font-semibold text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                    }} flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                >
                    <span>{{ $link['label'] }}</span>
                    @if ($active)
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-100 p-4">
            <a
                href="{{ url('/contact') }}"
                x-on:click="mobileMenuOpen = false"
                class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-blue-600 active:scale-[0.98]"
            >
                <span>Start a Project</span>
                <svg class="h-3.5 w-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</header>
