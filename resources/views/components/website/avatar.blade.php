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
        {{ $attributes->class([$size, 'shrink-0 border border-[#E1E5EA] object-cover']) }}
    />
@else
    <span
        {{ $attributes->class([$size, $text, 'flex shrink-0 items-center justify-center bg-[#0A0A0A] font-semibold text-white']) }}
        aria-hidden="true"
    >
        {{ $initials }}
    </span>
@endif
