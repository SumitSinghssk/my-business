@props(['text' => '', 'position' => 'top'])

@php
    $positionClasses = match ($position) {
        'bottom' => 'top-full left-1/2 mt-3 -translate-x-1/2',
        'left' => 'top-1/2 right-full mr-3 -translate-y-1/2',
        'right' => 'top-1/2 left-full ml-3 -translate-y-1/2',
        default => 'bottom-full left-1/2 mb-3 -translate-x-1/2',
    };

    $animation = 'scale-95 opacity-0 group-hover/tooltip:scale-100 group-hover/tooltip:opacity-100';
@endphp

<div class="group/tooltip relative inline-flex">
    {{ $slot }}
    @if ($text)
        <div class="{{ $positionClasses }} {{ $animation }} pointer-events-none absolute z-[200] transition-all duration-200 ease-out">
            <div
                class="relative rounded-lg bg-slate-900 px-3 py-1.5 text-[11px] font-bold tracking-wide text-white shadow-2xl dark:bg-white dark:text-slate-900"
            >
                {{ $text }}
                <div
                    class="{{
                        match ($position) {
                            'bottom' => '-top-1 left-1/2 -translate-x-1/2',
                            'left' => 'top-1/2 -right-1 -translate-y-1/2',
                            'right' => 'top-1/2 -left-1 -translate-y-1/2',
                            default => '-bottom-1 left-1/2 -translate-x-1/2',
                        }
                    }} absolute h-2 w-2 rotate-45 bg-inherit"
                ></div>
            </div>
        </div>
    @endif
</div>
