@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'autocomplete' => 'off',
    'toggle' => false,
    'disabled' => false,
])

@php
    $hasLeftIcon = isset($leftIcon);
    $hasRightIcon = isset($rightIcon) || ($toggle && ! $disabled);

    $paddingClasses = ($hasLeftIcon ? 'pl-9 ' : 'pl-3 ') . ($hasRightIcon ? 'pr-9 ' : 'pr-3 ');

    $stateClasses = ! $disabled
        ? 'bg-white border-slate-200 text-slate-900 placeholder:text-slate-400 focus:border-blue-400 focus:ring-3 focus:ring-blue-500/15 focus:outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-900 '
        : 'bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:border-slate-700 ';
@endphp

<div class="w-full" x-data="{ show: false }">
    <div class="relative">
        @if ($hasLeftIcon)
            <div
                class="{{ $disabled ? 'text-slate-300 dark:text-slate-600' : 'text-slate-400' }} pointer-events-none absolute inset-y-0 left-3 z-10 flex items-center"
            >
                {{ $leftIcon }}
            </div>
        @endif

        <input
            :type="{{ $toggle ? 'show ? \'text\' : \'password\'' : '\'' . $type . '\'' }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if($required) required @endif
            @disabled($disabled)
            {{
                $attributes->merge([
                    'class' =>
                        'w-full rounded-lg border py-2 text-sm transition-all ' .
                        $paddingClasses .
                        $stateClasses,
                ])
            }}
        />

        @if ($toggle && ! $disabled)
            <button
                type="button"
                x-on:click="show = !show"
                class="absolute inset-y-0 right-3 z-20 flex cursor-pointer items-center text-slate-400 transition hover:text-blue-600"
            >
                <div x-show="!show" class="flex items-center">
                    <x-icons.visibility class="h-4 w-4" />
                </div>
                <div x-show="show" x-cloak class="flex items-center">
                    <x-icons.visibility-lock class="h-4 w-4" />
                </div>
            </button>
        @elseif (isset($rightIcon))
            <div class="pointer-events-none absolute inset-y-0 right-3 z-10 flex items-center text-slate-400">
                {{ $rightIcon }}
            </div>
        @endif
    </div>
</div>
