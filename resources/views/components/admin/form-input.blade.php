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

    $paddingClasses = ($hasLeftIcon ? 'pl-11 ' : 'pl-4 ') . ($hasRightIcon ? 'pr-11 ' : 'pr-4 ');

    $stateClasses = ! $disabled
        ? 'bg-slate-50/50 border-slate-200 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-900 '
        : 'bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:border-slate-700 ';
@endphp

<div class="w-full" x-data="{ show: false }">
    <div class="relative">
        @if ($hasLeftIcon)
            <div
                class="{{ $disabled ? 'text-slate-300 dark:text-slate-600' : 'text-slate-400' }} pointer-events-none absolute inset-y-0 left-4 z-10 flex items-center"
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
                        'w-full rounded-xl border py-3 text-sm transition-all sm:text-base ' .
                        $paddingClasses .
                        $stateClasses,
                ])
            }}
        />

        @if ($toggle && ! $disabled)
            <button
                type="button"
                x-on:click="show = !show"
                class="absolute inset-y-0 right-4 z-20 flex cursor-pointer items-center text-slate-400 transition hover:text-blue-600"
            >
                <div x-show="!show" class="flex items-center">
                    <x-icons.visibility class="h-5 w-5" />
                </div>
                <div x-show="show" x-cloak class="flex items-center">
                    <x-icons.visibility-lock class="h-5 w-5" />
                </div>
            </button>
        @elseif (isset($rightIcon))
            <div class="pointer-events-none absolute inset-y-0 right-4 z-10 flex items-center text-slate-400">
                {{ $rightIcon }}
            </div>
        @endif
    </div>
</div>
