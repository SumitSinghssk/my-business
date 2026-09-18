@props([
    'name' => '',
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'value' => null,
])

@php
    $hasLeftIcon = isset($leftIcon);

    $paddingClasses = $hasLeftIcon ? 'pr-10 pl-11' : 'pr-10 pl-4';

    $stateClasses = ! $disabled
        ? 'bg-slate-50/50 border-slate-200 text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-900 '
        : 'bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:border-slate-700 ';
@endphp

<div class="relative w-full">
    @if ($hasLeftIcon)
        <div
            class="{{ $disabled ? 'text-slate-300 dark:text-slate-600' : 'text-slate-400' }} pointer-events-none absolute inset-y-0 left-4 z-10 flex items-center"
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
                    'w-full appearance-none rounded-xl border py-3 text-sm transition-all sm:text-base ' .
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

    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
            <path
                fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd"
            />
        </svg>
    </div>
</div>
