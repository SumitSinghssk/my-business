{{--
    Label + control (slot) + validation message for one website form field.
    Must sit inside an x-data that provides error(name) and clear(name), like contactForm in resources/js/app.js.
--}}

@props([
    'name',
    'label',
])

<div {{ $attributes }}>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    {{ $slot }}
    {{-- Server-rendered for a normal POST; kept in sync by the form's Alpine error()/clear() when it submits with fetch. --}}
    <p
        id="{{ $name }}-error"
        class="font-body-sm text-body-sm text-error mt-1"
        @unless ($errors->has($name)) hidden @endunless
        x-bind:hidden="! error(@js($name))"
        x-text="error(@js($name))"
    >
        {{ $errors->first($name) }}
    </p>
</div>
