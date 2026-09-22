{{--
    Two-column form body: main content on the left, a narrower side column (status, dates, image…)
    on the right from xl up; stacked below that.
    
    <x-admin.form-grid>
    …main cards…
    <x-slot:aside>…side cards…</x-slot>
    </x-admin.form-grid>
--}}

<div {{ $attributes->class('grid grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]') }}>
    <div class="min-w-0 space-y-6">
        {{ $slot }}
    </div>

    @isset($aside)
        <aside class="min-w-0 space-y-6 xl:sticky xl:top-20">
            {{ $aside }}
        </aside>
    @endisset
</div>
