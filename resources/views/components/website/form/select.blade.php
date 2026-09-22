{{--
    Select with label and error message.
    options:     [value => label]
    placeholder: first, empty option
    selected:    value to pre-select when there is no old input
--}}

@props([
    "name",
    "label",
    "options" => [],
    "placeholder" => null,
    "selected" => null,
])

@php
    $current = old($name, $selected);
@endphp

<x-website.form.field :name="$name" :label="$label" :class="$attributes->get('class')">
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @error($name)
            aria-invalid="true"
            aria-describedby="{{ $name }}-error"
        @enderror
        x-bind:class="{
            'border-error': error(@js($name)),
            'border-line': ! error(@js($name)),
        }"
        x-bind:aria-invalid="error(@js($name)) ? 'true' : null"
        x-bind:aria-describedby="error(@js($name)) ? @js($name . "-error") : null"
        x-on:change="clear(@js($name))"
        {{ $attributes->except("class")->class(["form-control cursor-pointer", $errors->has($name) ? "border-error" : "border-line"]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected($current === $value)>{{ $text }}</option>
        @endforeach
    </select>
</x-website.form.field>
