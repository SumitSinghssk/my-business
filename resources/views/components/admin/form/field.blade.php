{{--
    Layout shared by every admin form control: label (+ description), the control (slot), then the
    error message, or the hint when there is no error. `for` is the control's id; the error and hint
    get "{for}-error" / "{for}-hint", which the control points to with aria-describedby.
--}}

@props([
    'label' => null,
    'for' => null,
    'required' => false,
    'description' => null,
    'hint' => null,
    'error' => null,
])

<div {{ $attributes->class('w-full') }}>
    @if (filled($label))
        <x-admin.form.label :for="$for" :required="$required" :description="$description">{{ $label }}</x-admin.form.label>
    @endif

    {{ $slot }}

    @if ($error)
        <x-admin.form.error :id="$for ? $for . '-error' : null" :message="$error" />
    @elseif (filled($hint))
        <p @if ($for) id="{{ $for }}-hint" @endif class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
</div>
