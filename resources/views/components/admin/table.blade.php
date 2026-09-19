@props([
    'headers' => [],
    'data',
    'emptyMessage' => 'No records found.',
    'wrapperClass' => '',
])

@if (count($data) > 0)
    <div class="admin-table overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <div class="{{ $wrapperClass }} overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-slate-50 dark:bg-slate-800/40">
                    <tr>
                        @foreach ($headers as $header)
                            <th
                                class="border-b border-slate-200/80 px-4 py-2.5 text-left text-[11px] font-semibold tracking-wide text-slate-500 uppercase dark:border-slate-800 dark:text-slate-400"
                            >
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
        @if (is_object($data) && method_exists($data, 'links'))
            <div class="border-t border-slate-100 px-4 py-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                {{ $data->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
@else
    <div
        class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white px-4 py-12 dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-500 dark:bg-slate-800">
            <x-icons.search class="h-5 w-5" />
        </div>
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $emptyMessage }}</h3>
        <p class="mt-1 text-xs text-slate-500">Try adjusting your filters or adding a new record.</p>
    </div>
@endif
