{{-- Textarea with label and error message. Extra attributes go on the <textarea>; `class` goes on the wrapper. --}}

@props([
    "name",
    "label",
    "rows" => 6,
])

@php
    $value = is_string($old = old($name)) ? $old : "";
@endphp

<x-website.form.field :name="$name" :label="$label" :class="$attributes->get('class')">
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
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
        x-on:input="clear(@js($name))"
        {{ $attributes->except("class")->class(["form-control resize-y", $errors->has($name) ? "border-error" : "border-line"]) }}
    >
{{ $value }}</textarea
    >
</x-website.form.field>
