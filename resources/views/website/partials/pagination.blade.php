{{-- Website pagination, styled to the site theme. Usage: $paginator->links('website.partials.pagination') --}}
@if ($paginator->hasPages())
    @php
        $base = 'font-label-sm text-label-sm border px-3 py-1.5 tracking-wider uppercase transition-colors';
        $idle = 'text-on-surface hover:bg-surface-container border-line bg-white';
        $disabled = 'bg-surface text-outline border-line cursor-not-allowed';
    @endphp

    <nav class="gap-space-md flex flex-col items-center justify-between sm:flex-row" aria-label="Pagination">
        <p class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">
            Page {{ str_pad($paginator->currentPage(), 2, '0', STR_PAD_LEFT) }} of {{ str_pad($paginator->lastPage(), 2, '0', STR_PAD_LEFT) }} •
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <div class="flex flex-wrap items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="{{ $base }} {{ $disabled }}" aria-disabled="true">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $base }} {{ $idle }}">Prev</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="font-label-sm text-outline px-2">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="{{ $base }} border-ink bg-ink text-white" aria-current="page">
                                {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="{{ $base }} {{ $idle }}">
                                {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $base }} {{ $idle }}">Next</a>
            @else
                <span class="{{ $base }} {{ $disabled }}" aria-disabled="true">Next</span>
            @endif
        </div>
    </nav>
@endif
