{{--
    Filter toolbar for list pages: a GET form holding filter chips (x-admin.filter.select / date-range),
    a search button that drops down a search box, and "Reset" while anything is filtered.
    Picking a chip value submits straight away; empty values are left out of the URL.
    
    <x-admin.filter.bar search="Search posts by title…">
    <x-admin.filter.select name="status" label="Status" icon="circle-dot" :options="$statusOptions" />
    </x-admin.filter.bar>
--}}

@props([
    'action' => null,
    'search' => null,
    'searchName' => 'search',
])

@php
    $action ??= url()->current();
    $active = collect(request()->except(['page', 'per_page']))
        ->filter(fn ($v) => filled($v))
        ->count();
@endphp

<form
    method="GET"
    action="{{ $action }}"
    role="search"
    x-data="{
        submit() {
            for (const field of this.$el.elements) {
                if (field.name && field.value === '') field.disabled = true
            }
            this.$el.submit()
        },
    }"
    x-on:change="if ($event.target.closest('[data-filter]')) submit()"
    x-on:filter-submit="submit()"
    x-on:submit.prevent="submit()"
    {{ $attributes->class('flex flex-wrap items-center gap-2') }}
>
    <span class="mr-0.5 hidden items-center gap-1.5 text-xs font-medium text-slate-400 md:flex">
        <x-admin.icon name="filter" class="h-4 w-4" />
        Filter
    </span>

    {{ $slot }}

    @if ($active)
        <a
            href="{{ $action }}"
            class="inline-flex h-8 items-center gap-1 rounded-lg px-2 text-xs font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
        >
            <x-admin.icon name="x" class="h-3.5 w-3.5" />
            Reset
        </a>
    @endif

    <div class="ml-auto flex items-center gap-2">
        {{ $end ?? '' }}

        @if ($search)
            <x-admin.filter.search :name="$searchName" :placeholder="$search" />
        @endif
    </div>
</form>
