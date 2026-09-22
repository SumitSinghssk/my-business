{{-- Underlined uppercase link, e.g. "All Projects →" next to a section heading. --}}

@props([
    'href',
])

<a
    href="{{ $href }}"
    {{ $attributes->class('font-label-md text-label-md hover:text-primary border-ink text-ink inline-flex shrink-0 items-center gap-1 border-b pb-1 font-semibold tracking-wider uppercase transition-colors') }}
>
    {{ $slot }}
</a>
