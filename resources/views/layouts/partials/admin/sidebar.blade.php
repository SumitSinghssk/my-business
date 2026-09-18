<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 transform flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900"
>
    <div class="flex h-16 items-center px-6">
        <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                <span class="font-bold">A</span>
            </div>
            <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                {{ config('app.name') }}
            </span>
        </div>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-4 py-6">
        @foreach ($links as $section)
            @php
                $visibleItems = collect($section['items'])->filter(function ($link) {
                    return ! isset($link['permission']) ||
                        auth()
                            ->user()
                            ->can($link['permission']);
                });
            @endphp

            @if ($visibleItems->isNotEmpty())
                <div>
                    <h3 class="mb-2 px-3 text-[11px] font-bold tracking-widest text-slate-400 uppercase dark:text-slate-500">
                        {{ $section['section'] }}
                    </h3>

                    <div class="space-y-1">
                        @foreach ($visibleItems as $link)
                            <a
                                href="{{ $link['route'] }}"
                                class="{{
                                    request()->routeIs($link['active'])
                                        ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-100'
                                }} group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200"
                            >
                                <x-dynamic-component
                                    :component="'icons.' . $link['icon']"
                                    class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110"
                                />
                                {{ $link['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>
</aside>
