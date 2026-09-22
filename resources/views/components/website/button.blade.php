{{--
    Website button / button-style link.
    variant: primary (black) | outline (white, bordered) | outline-dark (on dark backgrounds) | inverse (white on dark) | accent (brand blue)
    fluid:   full width on phones, auto width from `sm` up (default true)
    Renders an <a> when `href` is given, otherwise a <button>.
--}}

@props(['href' => null, 'variant' => 'primary', 'fluid' => true, 'type' => 'button'])

@php
    $classes = [
        'px-space-xl font-label-md text-label-md inline-flex items-center justify-center py-4 tracking-wider uppercase',
        match ($variant) {
            'outline' => 'border-line text-ink hover:border-ink border bg-white transition-all',
            'outline-dark' => 'border border-white/20 bg-transparent text-white transition-all hover:border-white',
            'inverse' => 'hover:bg-primary-container text-ink bg-white transition-all hover:text-white',
            'accent' => 'bg-primary-container hover:bg-primary text-white transition-colors',
            default => 'hover:bg-primary-container bg-ink text-white transition-colors',
        },
        $fluid ? 'w-full sm:w-auto' : '',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
