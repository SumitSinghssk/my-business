{{--
    Text input with label and error message. Extra attributes (autocomplete, placeholder, required…) go on the <input>;
    `class` goes on the wrapper (e.g. sm:col-span-2).
--}}

@props([
    "name",
    "label",
    "type" => "text",
])

@php
    // Previous input as text only: a tampered submit (name[]=x) must not break the form.
    $value = is_string($old = old($name)) ? $old : "";
@endphp

<x-website.form.field :name="$name" :label="$label" :class="$attributes->get('class')">
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value }}"
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
        {{ $attributes->except("class")->class(["form-control", $errors->has($name) ? "border-error" : "border-line"]) }}
    />
</x-website.form.field>
