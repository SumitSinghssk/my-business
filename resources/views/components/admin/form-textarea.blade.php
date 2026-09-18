@props([
    'id' => '',
    'name' => '',
    'rows' => 3,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'editor' => false,
])

@php
    $hasLeftIcon = isset($leftIcon);
    $hasRightIcon = isset($rightIcon);

    $paddingClasses = ($hasLeftIcon ? 'pl-11 ' : 'pl-4 ') . ($hasRightIcon ? 'pr-11 ' : 'pr-4 ');

    $stateClasses = ! $disabled
        ? 'bg-slate-50/50 border-slate-200 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-900 '
        : 'bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:border-slate-700 ';
@endphp

<div class="w-full">
    <div class="relative">
        @if ($hasLeftIcon)
            <div class="{{ $disabled ? 'text-slate-300 dark:text-slate-600' : 'text-slate-400' }} pointer-events-none absolute top-4 left-4 z-10">
                {{ $leftIcon }}
            </div>
        @endif

        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @disabled($disabled)
            {{
                $attributes->merge([
                    'class' =>
                        'w-full rounded-xl border py-3 text-sm transition-all sm:text-base resize-none ' .
                        $paddingClasses .
                        $stateClasses .
                        ($editor ? ' tinymce' : ''),
                ])
            }}
        >
{{ $slot }}</textarea
        >

        @if ($hasRightIcon)
            <div class="pointer-events-none absolute top-4 right-4 z-10 text-slate-400">
                {{ $rightIcon }}
            </div>
        @endif
    </div>
</div>
