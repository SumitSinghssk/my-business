{{-- Website header with the main and mobile navigation. Data comes from App\View\Composers\WebsiteHeaderComposer. --}}
<header
    x-data="{
        mobileMenuOpen: false,
        openMenu() {
            this.mobileMenuOpen = true
            this.$nextTick(() => this.$refs.closeMenu.focus())
        },
        closeMenu() {
            if (! this.mobileMenuOpen) return
            this.mobileMenuOpen = false
            this.$nextTick(() => this.$refs.menuToggle.focus())
        },
        {{-- Keep Tab inside the open panel (it is a modal dialog). --}}
        trapFocus(event) {
            const items = [...this.$refs.menuPanel.querySelectorAll('a[href], button:not([disabled])')]
            const first = items[0]
            const last = items[items.length - 1]
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault()
                last.focus()
            } else if (! event.shiftKey && document.activeElement === last) {
                event.preventDefault()
                first.focus()
            }
        },
    }"
    x-on:keydown.escape.window="closeMenu()"
    x-on:resize.window="if (window.innerWidth >= 768) mobileMenuOpen = false"
    x-effect="document.documentElement.classList.toggle('overflow-hidden', mobileMenuOpen)"
    class="bg-surface-container-lowest border-line sticky top-0 z-50 w-full border-b"
>
    <div class="site-container gap-gutter flex items-center justify-between p-4">
        <div class="gap-space-md flex items-center">
            <a
                href="{{ route('home') }}"
                class="gap-space-md focus-visible:ring-primary-container flex items-center focus:outline-none focus-visible:ring-2"
                aria-label="{{ $appName }} home"
            >
                @if ($logo)
                    <img
                        src="{{ $logo }}"
                        alt=""
                        @if ($logoWidth) width="{{ $logoWidth }}" height="{{ $logoHeight }}" @endif
                        class="h-8 w-auto object-contain"
                    />
                @else
                    <span class="font-headline-sm text-headline-sm text-on-surface font-semibold tracking-tight uppercase">{{ $appName }}</span>
                @endif
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
                class="px-space-lg font-label-md text-label-md text-on-primary hover:border-primary-container hover:bg-primary-container border-ink bg-ink hidden items-center justify-center border py-3 tracking-wider uppercase transition-all sm:inline-flex"
            >
                Let's Talk →
            </a>

            <button
                type="button"
                x-ref="menuToggle"
                x-on:click="mobileMenuOpen ? closeMenu() : openMenu()"
                :aria-expanded="mobileMenuOpen.toString()"
                aria-controls="mobile-navigation"
                aria-label="Toggle navigation menu"
                class="text-on-surface focus-visible:ring-primary-container border-line hover:border-ink inline-flex h-10 w-10 items-center justify-center border transition-colors focus:outline-none focus-visible:ring-2 md:hidden"
            >
                <x-icons.menu-lines x-show="!mobileMenuOpen" class="h-5 w-5" aria-hidden="true" />
                <x-icons.close x-show="mobileMenuOpen" x-cloak class="h-5 w-5" aria-hidden="true" stroke-width="1.75" />
            </button>
        </div>
    </div>

    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition.opacity.duration.200ms
        x-on:click="closeMenu()"
        class="bg-ink/40 fixed inset-0 z-40 h-screen md:hidden"
        aria-hidden="true"
    ></div>

    <div
        id="mobile-navigation"
        x-ref="menuPanel"
        x-show="mobileMenuOpen"
        x-on:keydown.tab="trapFocus($event)"
        x-cloak
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="bg-surface-container-lowest border-line fixed top-0 right-0 z-50 flex h-screen w-80 max-w-[85vw] flex-col border-l md:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Mobile navigation"
    >
        <div class="px-margin-mobile border-line flex h-19 items-center justify-between border-b">
            <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Menu</span>
            <button
                type="button"
                x-ref="closeMenu"
                x-on:click="closeMenu()"
                aria-label="Close menu"
                class="text-on-surface border-line hover:border-ink focus-visible:ring-primary-container inline-flex h-10 w-10 items-center justify-center border focus:outline-none focus-visible:ring-2"
            >
                <x-icons.close class="h-5 w-5" aria-hidden="true" stroke-width="1.75" />
            </button>
        </div>

        <nav class="flex flex-1 flex-col overflow-y-auto" aria-label="Mobile Navigation">
            @foreach ($links as $link)
                @php($active = $link['pattern'] && request()->is($link['pattern']))
                <a
                    href="{{ $link['url'] }}"
                    @if ($active) aria-current="page" @endif
                    x-on:click="mobileMenuOpen = false"
                    class="{{ $active ? 'border-l-primary-container bg-surface-container-low text-on-surface border-l-2' : 'text-on-surface-variant hover:text-on-surface hover:bg-canvas' }} px-margin-mobile py-space-md font-label-md text-label-md border-line flex items-center justify-between border-b tracking-wider uppercase transition-colors"
                >
                    <span>{{ $link['label'] }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            @endforeach
        </nav>

        <div class="p-margin-mobile border-line border-t">
            <a
                href="{{ route('contact') }}"
                x-on:click="mobileMenuOpen = false"
                class="px-space-lg font-label-md text-label-md hover:bg-primary-container bg-ink flex w-full items-center justify-center py-4 tracking-wider text-white uppercase transition-colors"
            >
                Let's Talk →
            </a>
        </div>
    </div>
</header>
