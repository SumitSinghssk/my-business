@props([
    'type' => 'submit',
    'variant' => 'primary',
    'loading' => false,
    'full' => false,
    'class' => '',
])

@php
    $base = 'flex cursor-pointer items-center justify-center gap-2 rounded-md font-semibold tracking-tight transition-all duration-150 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50 disabled:active:scale-100';

    $sizes = 'px-4 py-2.5 text-sm';

    $width = $full ? 'w-full' : '';

    $variants = [
        'primary' => 'bg-slate-900 text-white shadow-xs hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200',
        'secondary' => 'border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700',
        'danger' => 'bg-red-600 text-white shadow-xs hover:bg-red-700',
        'outline' => 'border border-slate-300 bg-transparent text-slate-700 hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800',
    ];

    $classes = "$base $sizes $width " . $variants[$variant];
@endphp

<button
    type="{{ $type }}"
    x-data
    :disabled="typeof submitting !== 'undefined' ? submitting : false"
    {{ $attributes->merge(['class' => $classes]) }}
>
    <svg
        x-cloak
        x-show="typeof submitting !== 'undefined' && submitting"
        class="h-4 w-4 animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg>

    <span class="{!! $class !!} truncate">
        {{ $slot }}
    </span>
</button>
