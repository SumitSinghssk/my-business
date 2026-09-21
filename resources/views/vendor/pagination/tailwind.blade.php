@php
    $hasPages = $paginator->hasPages();
    $hasTotals = method_exists($paginator, 'total') && method_exists($paginator, 'firstItem');

    $btnBase = 'inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-2.5 text-xs font-semibold transition-all';
    $btnIdle = 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 active:scale-90 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white';
    $btnDisabled = 'cursor-not-allowed border-slate-100 bg-slate-50 text-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-700';
    $btnActive = 'border-slate-900 bg-slate-900 text-white dark:border-white dark:bg-white dark:text-slate-900';
@endphp

<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    {{-- Results summary (always visible) --}}
    <p class="text-xs text-slate-500 dark:text-slate-400">
        @if ($hasTotals && $paginator->total() > 0)
            {!! __('Showing') !!}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
            {!! __('of') !!}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        @endif
    </p>

    @if ($hasPages)
        <div class="flex items-center gap-1">
            {{-- Previous --}}

            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="{{ $btnBase }} {{ $btnDisabled }}">
                    <x-icons.chevron-left-mini class="h-4 w-4" />
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    aria-label="{{ __('pagination.previous') }}"
                    class="{{ $btnBase }} {{ $btnIdle }}"
                >
                    <x-icons.chevron-left-mini class="h-4 w-4" />
                </a>
            @endif

            {{-- Numbered pages (desktop) --}}
            <div class="hidden items-center gap-1 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="px-1.5 text-xs font-medium text-slate-400 dark:text-slate-600">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="{{ $btnBase }} {{ $btnActive }}">{{ $page }}</span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    class="{{ $btnBase }} {{ $btnIdle }}"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Compact indicator (mobile) --}}
            @if ($hasTotals)
                <span class="px-2 text-xs font-medium text-slate-500 sm:hidden dark:text-slate-400">
                    {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>
            @endif

            {{-- Next --}}

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    aria-label="{{ __('pagination.next') }}"
                    class="{{ $btnBase }} {{ $btnIdle }}"
                >
                    <x-icons.chevron-right-mini class="h-4 w-4" />
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="{{ $btnBase }} {{ $btnDisabled }}">
                    <x-icons.chevron-right-mini class="h-4 w-4" />
                </span>
            @endif
        </div>
    @endif
</nav>
