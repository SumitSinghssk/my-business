@props([
    'status',
])

@php
    $statusValue = $status instanceof \App\Enums\CommonStatusEnum ? $status->value : $status;

    $map = [
        'active' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-900/30',
            'text' => 'text-emerald-700 dark:text-emerald-400',
            'dot' => 'bg-emerald-500',
            'label' => 'Active',
        ],
        'inactive' => [
            'bg' => 'bg-slate-100 dark:bg-slate-800',
            'text' => 'text-slate-500 dark:text-slate-400',
            'dot' => 'bg-slate-400',
            'label' => 'Inactive',
        ],
    ];

    $current = $map[$statusValue] ?? $map['inactive'];
@endphp

<span class="{{ $current['bg'] }} {{ $current['text'] }} inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[11px] font-semibold">
    <span class="{{ $current['dot'] }} h-1.5 w-1.5 rounded-full"></span>
    {{ $current['label'] }}
</span>
