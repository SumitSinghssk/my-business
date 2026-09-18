@props(['for' => '', 'label' => '', 'required' => false, 'class' => '', 'dynamic' => false])

<label
    @if(!$dynamic && $for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'mb-2.5 block text-[11px] font-bold tracking-widest text-slate-500 uppercase dark:text-slate-400 ' . $class]) }}
>
    {{ $label ?: $slot }}
    @if ($required)
        <span class="ml-1 text-red-500" title="Required field">*</span>
    @endif
</label>
