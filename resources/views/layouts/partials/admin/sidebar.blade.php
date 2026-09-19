@php
    $appName = \App\Helpers\Settings::appName();
    $logo = \App\Helpers\Settings::logoLight();
    $user = auth('web')->user();
@endphp

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex h-screen w-60 -translate-x-full transform flex-col border-r border-slate-200/80 bg-white transition-transform duration-300 ease-out lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900"
>
    {{-- Brand --}}
    <div class="flex h-14 shrink-0 items-center justify-between px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2.5">
            @if ($logo)
                <img src="{{ $logo }}" alt="{{ $appName }}" class="h-8 w-8 rounded-lg object-contain" />
            @else
                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-linear-to-br from-blue-500 to-violet-500 text-sm font-extrabold text-white shadow-sm shadow-blue-500/30"
                >
                    {{ strtoupper(mb_substr($appName, 0, 1)) }}
                </span>
            @endif
            <span class="min-w-0">
                <span class="block truncate text-sm font-bold text-slate-900 dark:text-white">{{ $appName }}</span>
                <span class="block text-[10px] font-semibold tracking-wide text-slate-400 uppercase">Admin Panel</span>
            </span>
        </a>
        <button
            type="button"
            x-on:click="sidebarOpen = false"
            class="rounded-md p-1 text-slate-400 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800"
            aria-label="Close menu"
        >
            <x-icons.close class="h-4 w-4" />
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-3" aria-label="Admin navigation">
        @foreach ($links as $section)
            @php
                $visibleItems = collect($section['items'])->filter(fn ($link) => ! isset($link['permission']) || $user->can($link['permission']));
            @endphp

            @if ($visibleItems->isNotEmpty())
                <div>
                    <h3 class="mb-1.5 px-2.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase dark:text-slate-500">
                        {{ $section['section'] }}
                    </h3>

                    <div class="space-y-0.5">
                        @foreach ($visibleItems as $link)
                            @php($active = request()->routeIs($link['active']))
                            <a
                                href="{{ $link['route'] }}"
                                @if ($active) aria-current="page" @endif
                                class="{{ $active ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100/70 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-100' }} group relative flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium transition-colors"
                            >
                                @if ($active)
                                    <span class="absolute top-1.5 bottom-1.5 left-0 w-0.75 rounded-r-full bg-blue-500"></span>
                                @endif

                                <x-dynamic-component
                                    :component="'icons.' . $link['icon']"
                                    class="{{ $active ? 'text-blue-600 dark:text-blue-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }} h-4.5 w-4.5 shrink-0"
                                />
                                <span class="truncate">{{ $link['title'] }}</span>
                                @if (! empty($link['badge']))
                                    <span class="ml-auto rounded-full bg-blue-500 px-1.5 py-px text-[10px] leading-4 font-bold text-white">
                                        {{ $link['badge'] > 99 ? '99+' : $link['badge'] }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    {{-- Footer: website link + user --}}
    <div class="shrink-0 space-y-2 border-t border-slate-100 p-3 dark:border-slate-800">
        <a
            href="{{ route('home') }}"
            target="_blank"
            rel="noopener"
            class="flex items-center justify-between rounded-lg bg-slate-50 px-2.5 py-2 text-xs font-semibold text-slate-600 transition-colors hover:bg-blue-50 hover:text-blue-700 dark:bg-slate-800/60 dark:text-slate-300 dark:hover:bg-blue-500/10 dark:hover:text-blue-300"
        >
            <span class="flex items-center gap-2">
                <x-icons.desktop class="h-4 w-4" />
                View Website
            </span>
            <x-icons.link class="h-3.5 w-3.5 opacity-60" />
        </a>

        @can('profile.view')
            <a
                href="{{ route('admin.profile.edit') }}"
                class="flex items-center gap-2.5 rounded-lg p-1.5 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/60"
            >
                <img src="{{ $user->avatar_url }}" alt="" class="h-8 w-8 rounded-lg object-cover" />
                <span class="min-w-0">
                    <span class="block truncate text-xs font-semibold text-slate-900 dark:text-white">{{ $user->name }}</span>
                    <span class="block truncate text-[11px] text-slate-400">{{ $user->email }}</span>
                </span>
            </a>
        @endcan
    </div>
</aside>
