@props([
    'name' => '',
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'value' => null,
])

@php
    $hasLeftIcon = isset($leftIcon);

    $paddingClasses = $hasLeftIcon ? 'pr-9 pl-9' : 'pr-9 pl-3';

    $stateClasses = ! $disabled
        ? 'bg-white border-slate-200 text-slate-900 focus:border-blue-400 focus:ring-3 focus:ring-blue-500/15 focus:outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-900 '
        : 'bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:border-slate-700 ';
@endphp

<div class="relative w-full">
    @if ($hasLeftIcon)
        <div
            class="{{ $disabled ? 'text-slate-300 dark:text-slate-600' : 'text-slate-400' }} pointer-events-none absolute inset-y-0 left-3 z-10 flex items-center"
        >
            {{ $leftIcon }}
        </div>
    @endif

    <select
        name="{{ $name }}"
        @if($required) required @endif
        @disabled($disabled)
        {{
            $attributes->merge([
                'class' =>
                    'w-full appearance-none rounded-lg border py-2 text-sm transition-all ' .
                    $paddingClasses .
                    ' ' .
                    $stateClasses,
            ])
        }}
    >
        @if ($placeholder)
            <option value="" disabled {{ $value ? '' : 'selected' }}>
                {{ $placeholder }}
            </option>
        @endif

        {{ $slot }}
    </select>

    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
        <x-icons.chevron-down-mini class="h-4 w-4" />
    </div>
</div>
