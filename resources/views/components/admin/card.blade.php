@props([
    'title' => '',
    'subtitle' => '',
    'text' => '',
])

<div
    {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 p-4 sm:p-6 sm:rounded-lg sm:border border-slate-200 dark:border-slate-800 shadow-xs transition-all']) }}
>
    @php
        $hasTitle = isset($title) && (is_object($title) ? (string) $title !== '' : $title !== '');
        $hasHeader = $hasTitle || $subtitle || $text || isset($actions) || isset($extra);
    @endphp

    @if ($hasHeader)
        <div class="mb-8 flex flex-wrap items-center justify-between gap-5">
            <div class="min-w-0">
                @if ($hasTitle)
                    @if (is_object($title))
                        {{ $title }}
                    @else
                        <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl dark:text-white">
                            {{ $title }}
                        </h2>
                    @endif
                @endif

                @if ($subtitle || $text)
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @if ($subtitle)
                            <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                {{ $subtitle }}
                            </span>
                        @endif

                        @if ($subtitle && $text)
                            <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        @endif

                        @if ($text)
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                {{ $text }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            @if (isset($actions) || isset($extra))
                <div class="flex items-center gap-3">
                    @isset($extra)
                        <div class="flex items-center space-x-2">
                            {{ $extra }}
                        </div>
                    @endisset

                    @isset($actions)
                        <div class="hidden items-center gap-2 lg:flex">
                            {{ $actions }}
                        </div>

                        <div x-data="{ open: false }" class="relative lg:hidden">
                            <button
                                x-on:click="open = !open"
                                class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                                    />
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-on:click.away="open = false"
                                x-transition:enter="transition duration-150 ease-out"
                                x-transition:enter-start="scale-95 opacity-0"
                                x-transition:enter-end="scale-100 opacity-100"
                                class="absolute right-0 z-50 mt-2 w-52 origin-top-right rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl dark:border-slate-700 dark:bg-slate-800"
                            >
                                <div class="flex w-full flex-col space-y-1">
                                    {{ $actions }}
                                </div>
                            </div>
                        </div>
                    @endisset
                </div>
            @endif
        </div>
    @endif

    <div class="relative">
        {{ $slot }}
    </div>
</div>
