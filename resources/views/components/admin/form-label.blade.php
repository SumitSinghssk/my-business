@props(['for' => '', 'label' => '', 'required' => false, 'class' => '', 'dynamic' => false])

<label
    @if(!$dynamic && $for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300 ' . $class]) }}
>
    {{ $label ?: $slot }}
    @if ($required)
        <span class="ml-1 text-red-500" title="Required field">*</span>
    @endif
</label>
