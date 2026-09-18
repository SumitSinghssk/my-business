<div class="border-b border-slate-200 bg-white/80 px-4 py-2 backdrop-blur-md sm:px-6 dark:border-slate-800 dark:bg-slate-900/80">
    <nav class="flex items-center gap-2 overflow-x-auto text-[11px] font-medium whitespace-nowrap">
        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>

        @if (isset($breadcrumb) && count($breadcrumb))
            @foreach ($breadcrumb as $index => $item)
                <x-icons.chevron-forward class="h-3 w-3 shrink-0 text-slate-300 dark:text-slate-600" />

                @if ($loop->last)
                    <span class="truncate text-slate-900 dark:text-white">
                        {{ $item['label'] }}
                    </span>
                @else
                    <a
                        href="{{ $item['url'] ?? '#' }}"
                        class="text-slate-500 transition-colors hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400"
                    >
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        @endif
    </nav>
</div>
