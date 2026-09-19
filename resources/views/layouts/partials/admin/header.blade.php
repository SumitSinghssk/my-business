@php
    $user = auth('web')->user();
    $crumbs = $breadcrumb ?? [];
    $role = $user->getRoleNames()->first();
@endphp

<header
    class="sticky top-0 z-40 flex h-14 shrink-0 items-center justify-between gap-3 border-b border-slate-200/80 bg-white/85 px-3 backdrop-blur-md sm:px-5 lg:px-6 dark:border-slate-800 dark:bg-slate-900/85"
>
    {{-- Left: menu + breadcrumb --}}
    <div class="flex min-w-0 items-center gap-2">
        <button
            type="button"
            x-on:click="sidebarOpen = !sidebarOpen"
            class="inline-flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800"
            aria-label="Open menu"
        >
            <x-icons.menu class="h-4.5 w-4.5" />
        </button>

        <nav class="flex min-w-0 items-center gap-1.5 overflow-hidden text-sm whitespace-nowrap" aria-label="Breadcrumb">
            <a
                href="{{ route('admin.dashboard') }}"
                class="{{ count($crumbs) ? 'text-slate-400 hover:text-blue-600' : 'font-semibold text-slate-900 dark:text-white' }} shrink-0 transition-colors"
            >
                Dashboard
            </a>
            @foreach ($crumbs as $item)
                <x-icons.chevron-forward class="h-3 w-3 shrink-0 text-slate-300 dark:text-slate-600" />

                @if ($loop->last)
                    <span class="truncate font-semibold text-slate-900 dark:text-white">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] ?? '#' }}" class="truncate text-slate-400 transition-colors hover:text-blue-600">
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    {{-- Right: actions --}}
    <div class="flex shrink-0 items-center gap-1.5">
        <a
            href="{{ route('home') }}"
            target="_blank"
            rel="noopener"
            class="hidden h-8 items-center gap-1.5 rounded-lg border border-slate-200 px-2.5 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 sm:inline-flex dark:border-slate-700 dark:text-slate-300 dark:hover:bg-blue-500/10"
        >
            <x-icons.desktop class="h-3.5 w-3.5" />
            View site
        </a>

        <button
            type="button"
            x-data
            x-on:click="$store.theme.toggle()"
            x-bind:aria-label="$store.theme.isDark ? 'Switch to light theme' : 'Switch to dark theme'"
            x-bind:title="$store.theme.isDark ? 'Switch to light theme' : 'Switch to dark theme'"
            class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
        >
            <x-icons.moon x-show="!$store.theme.isDark" x-cloak class="h-4.5 w-4.5" />
            <x-icons.sun x-show="$store.theme.isDark" x-cloak class="h-4.5 w-4.5" />
        </button>

        @can('admin.notifications.view')
            <div class="relative" x-data="{ open: false }" @click.outside="open = false; $store.notif.close()">
                <button
                    type="button"
                    x-on:click="window.innerWidth < 768 ? $store.notif.toggle() : (open = ! open)"
                    aria-label="Notifications"
                    class="{{
                        request()->routeIs('admin.notifications.*')
                            ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400'
                            : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'
                    }} relative flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg transition"
                >
                    <x-icons.notification class="h-4.5 w-4.5" />
                    <span x-cloak x-show="$store.notif.unread > 0" x-transition class="absolute top-1 right-1 flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-blue-500 ring-2 ring-white dark:ring-slate-900"></span>
                    </span>
                </button>

                <div
                    x-show="open && window.innerWidth >= 768"
                    x-transition:enter="transition duration-150 ease-out"
                    x-transition:enter-start="translate-y-1 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-cloak
                    class="absolute right-0 z-100 mt-2 hidden w-80 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl md:block dark:border-slate-700 dark:bg-slate-900"
                >
                    @include('layouts.partials.admin.notifications-content')
                </div>
            </div>
        @endcan

        <div class="mx-1 h-5 w-px bg-slate-200 dark:bg-slate-700"></div>

        <div class="relative" x-data="{ open: false }">
            <button
                type="button"
                x-on:click="open = !open"
                class="flex cursor-pointer items-center gap-2 rounded-lg p-1 pr-1.5 transition hover:bg-slate-100 dark:hover:bg-slate-800"
            >
                <img src="{{ $user->avatar_url }}" alt="" class="h-7 w-7 rounded-lg object-cover" />
                <span class="hidden text-left sm:block">
                    <span class="block text-xs leading-tight font-semibold text-slate-900 dark:text-white">{{ $user->name }}</span>
                    <span class="block text-[10px] leading-tight text-slate-400">{{ $role ? ucwords($role) : 'Administrator' }}</span>
                </span>
                <x-icons.chevron-down class="h-3.5 w-3.5 text-slate-400" />
            </button>

            <div
                x-show="open"
                x-on:click.outside="open = false"
                x-transition:enter="transition duration-150 ease-out"
                x-transition:enter-start="translate-y-1 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-cloak
                class="absolute right-0 z-100 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl dark:border-slate-700 dark:bg-slate-900"
            >
                <div class="px-2.5 py-2">
                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                </div>

                <div class="my-1 h-px bg-slate-100 dark:bg-slate-800"></div>

                @can('profile.view')
                    <a
                        href="{{ route('admin.profile.edit') }}"
                        class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <x-icons.account-circle class="h-4 w-4 text-slate-400" />
                        My Profile
                    </a>
                @endcan

                @can('admin.settings.view')
                    <a
                        href="{{ route('admin.settings.index') }}"
                        class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <x-icons.setting class="h-4 w-4 text-slate-400" />
                        Settings
                    </a>
                @endcan

                <div class="my-1 h-px bg-slate-100 dark:bg-slate-800"></div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                    >
                        <x-icons.logout class="h-4 w-4" />
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

@can('admin.notifications.view')
    <div x-data x-show="$store.notif.open" x-cloak class="fixed inset-0 z-60 md:hidden">
        <div
            x-show="$store.notif.open"
            x-transition.opacity
            x-on:click="$store.notif.close()"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        ></div>

        <div
            x-show="$store.notif.open"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition duration-200 ease-in"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="absolute right-0 bottom-0 left-0 flex h-[80vh] flex-col overflow-hidden rounded-t-xl bg-white dark:bg-slate-900"
        >
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Notifications</h2>
                    @can('admin.notifications.mark-all-as-read')
                        <button
                            x-show="$store.notif.unread > 0"
                            x-on:click="$store.notif.markAllRead()"
                            class="cursor-pointer text-xs font-medium text-blue-600 hover:underline dark:text-blue-400"
                        >
                            Mark all as read
                        </button>
                    @endcan
                </div>

                <button x-on:click="$store.notif.close()" class="cursor-pointer rounded-full bg-slate-100 p-2 text-slate-500 dark:bg-slate-800">
                    <x-icons.close class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 pb-4">
                @include('layouts.partials.admin.notifications-content')
            </div>

            <div class="border-t border-slate-100 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                <a
                    href="{{ route('admin.notifications.list') }}"
                    class="block w-full rounded-lg bg-slate-100 px-4 py-2 text-center text-xs font-medium text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                >
                    View All Notifications
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <style>
            @keyframes bell-ring {
                0% {
                    transform: rotate(0);
                }
                10% {
                    transform: rotate(15deg);
                }
                20% {
                    transform: rotate(-10deg);
                }
                30% {
                    transform: rotate(12deg);
                }
                40% {
                    transform: rotate(-6deg);
                }
                50% {
                    transform: rotate(4deg);
                }
                60% {
                    transform: rotate(0);
                }
                100% {
                    transform: rotate(0);
                }
            }

            .bell-infinite {
                animation: bell-ring 1.5s ease-in-out infinite;
                transform-origin: top center;
            }
        </style>

        <script defer>
            document.addEventListener('alpine:init', () => {
                Alpine.store('notif', {
                    open: false,
                    items: [],
                    unread: 0,

                    toggle() {
                        this.open = !this.open;
                    },

                    close() {
                        this.open = false;
                    },

                    fetch() {
                        axios
                            .get('{{ route('admin.notifications.index') }}')
                            .then((res) => {
                                this.items = res.data.notifications;
                                this.unread = res.data.unread_count;
                            })
                            .catch((err) => console.error(err));
                    },

                    markAllRead() {
                        axios.post('{{ route('admin.notifications.markAllRead') }}').then(() => {
                            this.unread = 0;
                            this.items = this.items.map((n) => ({ ...n, seen: true }));
                        });
                    },

                    markRead(id) {
                        const url = '{{ route('admin.notifications.markRead', ':id') }}'.replace(':id', id);

                        axios.post(url).then(() => {
                            const notif = this.items.find((n) => n.id === id);
                            if (notif && !notif.seen) {
                                notif.seen = true;
                                this.unread--;
                            }
                        });
                    },
                });

                Alpine.store('notif').fetch();
            });
        </script>
    @endpush
@endcan
