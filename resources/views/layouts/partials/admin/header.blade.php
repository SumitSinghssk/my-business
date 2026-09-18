<header
    class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/80 px-4 backdrop-blur-md sm:px-6 lg:pl-68 dark:border-slate-800 dark:bg-slate-900/80"
>
    <div class="flex items-center">
        <button
            x-on:click="sidebarOpen = !sidebarOpen"
            class="mr-4 inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 lg:hidden dark:border-slate-700 dark:hover:bg-slate-800"
        >
            <x-icons.menu class="h-5 w-5" />
        </button>
    </div>

    <div class="flex items-center gap-3">
        <button
            type="button"
            x-data
            x-on:click="$store.theme.toggle()"
            x-bind:aria-label="$store.theme.isDark ? 'Switch to light theme' : 'Switch to dark theme'"
            x-bind:title="$store.theme.isDark ? 'Switch to light theme' : 'Switch to dark theme'"
            class="relative flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800"
        >
            <x-icons.moon x-show="!$store.theme.isDark" x-cloak class="h-5 w-5" />
            <x-icons.sun x-show="$store.theme.isDark" x-cloak class="h-5 w-5" />
        </button>

        @can('admin.notifications.view')
            <div class="relative" x-data="{ open: false }" @click.outside="open = false; $store.notif.close()">
                <button
                    x-on:click="window.innerWidth < 768 ? $store.notif.toggle() : (open = ! open)"
                    class="{{
                        request()->routeIs('admin.notifications.*')
                            ? 'border-blue-500 bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400'
                            : 'border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800'
                    }} relative flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border transition"
                >
                    <x-icons.notification class="bell-infinite h-5 w-5" />
                    <span x-cloak x-show="$store.notif.unread > 0" x-transition class="absolute top-1.5 right-2.5 flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                    </span>
                </button>

                <div
                    x-show="open && window.innerWidth >= 768"
                    x-transition:enter="transition duration-200 ease-out"
                    x-transition:enter-start="translate-y-1 scale-95 opacity-0"
                    x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                    x-cloak
                    class="absolute right-0 z-100 mt-3 hidden w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl md:block dark:border-slate-700 dark:bg-slate-900"
                >
                    @include('layouts.partials.admin.notifications-content')
                </div>
            </div>

            <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
        @endcan

        <div class="relative" x-data="{ open: false }">
            <button
                x-on:click="open = !open"
                class="flex cursor-pointer items-center gap-3 rounded-xl p-1 pr-3 transition hover:bg-slate-50 dark:hover:bg-slate-800"
            >
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-md bg-linear-to-tr from-slate-700 to-slate-900 text-sm font-bold text-white shadow-sm ring-1 ring-slate-900/10 dark:from-slate-600 dark:to-slate-800 dark:ring-white/10"
                >
                    {{ strtoupper(substr(auth('web')->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="hidden text-left sm:block">
                    <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ auth('web')->user()->name }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Administrator</p>
                </div>
                <x-icons.chevron-forward class="h-3 w-3 rotate-90 text-slate-400" />
            </button>

            <div
                x-show="open"
                x-on:click.outside="open = false"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="translate-y-1 scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                x-cloak
                class="absolute right-0 z-100 mt-3 w-60 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl dark:border-slate-700 dark:bg-slate-900"
            >
                <div class="px-3 py-3">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth('web')->user()->name }}</p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth('web')->user()->email }}</p>
                </div>

                <div class="my-1 h-px bg-slate-100 dark:bg-slate-800"></div>

                @can('profile.view')
                    <a
                        href="{{ route('admin.profile.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <x-icons.account-circle class="h-4 w-4 text-slate-400" />
                        Profile Settings
                    </a>
                @endcan

                @can('settings.view')
                    <a
                        href="{{ route('admin.settings.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <x-icons.setting class="h-4 w-4 text-slate-400" />
                        System Settings
                    </a>
                @endcan

                <div class="my-1 h-px bg-slate-100 dark:bg-slate-800"></div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                    >
                        <x-icons.logout class="h-4 w-4" />
                        Logout
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
