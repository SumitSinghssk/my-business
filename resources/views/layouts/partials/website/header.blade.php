@php
    use App\Helpers\Settings;

    $appName = Settings::appName();
    $logo = Settings::logoLight();

    $links = [
        ['label' => 'Services', 'url' => route('services'), 'pattern' => 'services'],
        ['label' => 'Work', 'url' => route('home') . '#selected-work', 'pattern' => null],
        ['label' => 'About', 'url' => route('about'), 'pattern' => 'about'],
        ['label' => 'Insights', 'url' => route('blog.index'), 'pattern' => 'insights*'],
    ];
@endphp

<header
    x-data="{ mobileMenuOpen: false }"
    x-on:keydown.escape.window="mobileMenuOpen = false"
    x-effect="document.documentElement.classList.toggle('overflow-hidden', mobileMenuOpen)"
    class="bg-surface-container-lowest sticky top-0 z-50 w-full border-b border-[#E1E5EA]"
>
    <div class="site-container gap-gutter flex items-center justify-between p-4">
        <div class="gap-space-md flex items-center">
            <a
                href="{{ route('home') }}"
                class="gap-space-md focus-visible:ring-primary-container flex items-center focus:outline-none focus-visible:ring-2"
                aria-label="{{ $appName }} home"
            >
                @if ($logo)
                    <img src="{{ $logo }}" alt="{{ $appName }} logo" class="h-8 w-auto object-contain" />
                @endif

                <span class="font-headline-sm text-headline-sm text-on-surface font-semibold tracking-tight uppercase">{{ $appName }}</span>
            </a>
        </div>

        <nav class="gap-space-xl hidden items-center md:flex" aria-label="Main Navigation">
            @foreach ($links as $link)
                @php($active = $link['pattern'] && request()->is($link['pattern']))
                <a
                    href="{{ $link['url'] }}"
                    @if ($active) aria-current="page" @endif
                    class="{{ $active ? 'border-primary-container text-on-surface border-b-2 font-medium' : 'text-on-surface-variant hover:text-on-surface' }} font-label-md text-label-md py-2 tracking-wider uppercase transition-colors"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="gap-space-md flex items-center">
            <a
                href="{{ route('contact') }}"
                class="px-space-lg font-label-md text-label-md text-on-primary hover:border-primary-container hover:bg-primary-container hidden items-center justify-center border border-[#0A0A0A] bg-[#0A0A0A] py-3 tracking-wider uppercase transition-all sm:inline-flex"
            >
                Let's Talk →
            </a>

            <button
                type="button"
                x-on:click="mobileMenuOpen = !mobileMenuOpen"
                :aria-expanded="mobileMenuOpen.toString()"
                aria-controls="mobile-navigation"
                aria-label="Toggle navigation menu"
                class="text-on-surface focus-visible:ring-primary-container inline-flex h-10 w-10 items-center justify-center border border-[#E1E5EA] transition-colors hover:border-[#0A0A0A] focus:outline-none focus-visible:ring-2 md:hidden"
            >
                <svg x-show="!mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="square" stroke-width="1.75" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="square" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition.opacity.duration.200ms
        x-on:click="mobileMenuOpen = false"
        class="fixed inset-0 z-40 h-screen bg-[#0A0A0A]/40 md:hidden"
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
        class="bg-surface-container-lowest fixed top-0 right-0 z-50 flex h-screen w-80 max-w-[85vw] flex-col border-l border-[#E1E5EA] md:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Mobile navigation"
    >
        <div class="px-margin-mobile flex h-19 items-center justify-between border-b border-[#E1E5EA]">
            <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Menu</span>
            <button
                type="button"
                x-on:click="mobileMenuOpen = false"
                aria-label="Close menu"
                class="text-on-surface inline-flex h-10 w-10 items-center justify-center border border-[#E1E5EA] hover:border-[#0A0A0A] focus:outline-none"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="square" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-1 flex-col overflow-y-auto" aria-label="Mobile Navigation">
            @foreach ($links as $link)
                @php($active = $link['pattern'] && request()->is($link['pattern']))
                <a
                    href="{{ $link['url'] }}"
                    @if ($active) aria-current="page" @endif
                    x-on:click="mobileMenuOpen = false"
                    class="{{ $active ? 'border-l-primary-container bg-surface-container-low text-on-surface border-l-2' : 'text-on-surface-variant hover:text-on-surface hover:bg-[#F7F8FA]' }} px-margin-mobile py-space-md font-label-md text-label-md flex items-center justify-between border-b border-[#E1E5EA] tracking-wider uppercase transition-colors"
                >
                    <span>{{ $link['label'] }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            @endforeach
        </nav>

        <div class="p-margin-mobile border-t border-[#E1E5EA]">
            <a
                href="{{ route('contact') }}"
                x-on:click="mobileMenuOpen = false"
                class="px-space-lg font-label-md text-label-md hover:bg-primary-container flex w-full items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors"
            >
                Let's Talk →
            </a>
        </div>
    </div>
</header>
