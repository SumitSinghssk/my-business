@props([
    'user' => null,
    'size' => 'h-10 w-10',
    'text' => 'font-label-md text-label-md',
])

@php
    $name = $user?->name ?? 'Editorial Team';
    $initials = collect(preg_split('/\s+/', trim($name)))
        ->filter()
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

@if ($user?->avatar)
    <img
        src="{{ asset('storage/' . $user->avatar) }}"
        alt="{{ $name }}"
        {{ $attributes->class([$size, 'border-line shrink-0 border object-cover']) }}
    />
@else
    <span {{ $attributes->class([$size, $text, 'bg-ink flex shrink-0 items-center justify-center font-semibold text-white']) }} aria-hidden="true">
        {{ $initials }}
    </span>
@endif
