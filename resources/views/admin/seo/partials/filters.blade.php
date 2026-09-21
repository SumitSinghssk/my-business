@php
    $activeFilters = collect(request()->only(['index', 'search']))
        ->filter(fn ($v) => $v !== null && $v !== '')
        ->count();
@endphp

<div
    x-data="{
        search: '{{ old('search', request('search')) }}',
        debounceTimer: null,
        submitDebounced() {
            clearTimeout(this.debounceTimer)
            this.debounceTimer = setTimeout(
                () => this.$refs.filterForm.submit(),
                400,
            )
        },
    }"
    class="mb-5"
>
    <form x-ref="filterForm" method="GET" action="{{ route('admin.seo.index') }}">
        <div class="flex flex-col items-end justify-between gap-3 sm:flex-row sm:items-center">
            <div class="w-full sm:max-w-sm">
                <x-admin.form-input
                    type="text"
                    name="search"
                    x-model="search"
                    x-on:input="submitDebounced()"
                    placeholder="Search by page, slug, meta title…"
                    :value="request('search')"
                >
                    <x-slot:leftIcon>
                        <x-icons.search class="h-4 w-4" />
                    </x-slot>
                </x-admin.form-input>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-36 shrink-0">
                    <x-admin.form-select name="index" x-on:change="$refs.filterForm.submit()">
                        <option value="">All</option>
                        <option value="1" @selected(request('index') === '1')>Indexed</option>
                        <option value="0" @selected(request('index') === '0')>No Index</option>
                    </x-admin.form-select>
                </div>

                @if ($activeFilters > 0)
                    <a
                        href="{{ route('admin.seo.index') }}"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-red-500 transition hover:text-red-600"
                    >
                        <x-icons.close class="h-4 w-4" />
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>
