@props([
    'type' => 'submit',
    'variant' => 'primary',
    'loading' => false,
    'full' => false,
    'size' => 'md',
    'class' => '',
])

@php
    $base = 'inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg font-semibold transition-all duration-150 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 disabled:active:scale-100';

    $sizes = $size === 'sm' ? 'px-2.5 py-1.5 text-xs' : 'px-3.5 py-2 text-sm';

    $width = $full ? 'w-full' : '';

    $variants = [
        'primary' => 'bg-blue-600 text-white shadow-sm shadow-blue-600/25 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 shadow-xs hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700',
        'danger' => 'bg-red-500 text-white shadow-sm shadow-red-500/25 hover:bg-red-600',
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
    <x-icons.spinner x-cloak x-show="typeof submitting !== 'undefined' && submitting" class="h-4 w-4 animate-spin" />

    <span class="{!! $class !!} truncate">
        {{ $slot }}
    </span>
</button>
