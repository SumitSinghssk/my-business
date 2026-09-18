@props([
    'headers' => [],
    'data',
    'emptyMessage' => 'No records found.',
    'wrapperClass' => '',
])

@if (count($data) > 0)
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="{{ $wrapperClass }} overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-slate-50/50 dark:bg-slate-800/40">
                    <tr>
                        @foreach ($headers as $header)
                            <th
                                class="border-b border-slate-100 px-6 py-4 text-left text-[11px] font-bold tracking-widest text-slate-500 uppercase dark:border-slate-800 dark:text-slate-400"
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
            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 dark:border-slate-800 dark:bg-slate-800/40">
                {{ $data->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
@else
    <div
        class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white px-4 py-20 dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-400 dark:bg-slate-800">
            <x-icons.search class="h-10 w-10" />
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $emptyMessage }}</h3>
        <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or adding a new record.</p>
    </div>
@endif
