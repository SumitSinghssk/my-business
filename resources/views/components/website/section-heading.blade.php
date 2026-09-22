{{--
    Section heading: h2 + optional intro text, left-aligned on phones and centred from `lg` up.
    split: title on the left and the slot (e.g. an "All projects →" link) on the right instead.
    dark:  white text for dark sections.
    The default slot is placed under the text (or on the right when split).
--}}

@props([
    'title',
    'text' => null,
    'split' => false,
    'dark' => false,
])

@php
    $titleClass = 'section-title ' . ($dark ? 'text-white' : 'text-ink');
@endphp

<div
    {{
        $attributes->class([
            'section-head flex',
            'items-end justify-between gap-4' => $split,
            'flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center' => ! $split,
        ])
    }}
>
    <h2 class="{{ $titleClass }}">{{ $title }}</h2>

    @if ($text)
        <p @class(['font-body-md text-body-md max-w-2xl', 'text-dark-muted' => $dark, 'text-secondary' => ! $dark])>{{ $text }}</p>
    @endif

    {{ $slot }}
</div>
