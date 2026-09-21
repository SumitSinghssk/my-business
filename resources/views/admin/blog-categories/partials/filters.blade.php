@php
    $activeFilters = collect(request()->only(['status', 'search']))
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
    <form x-ref="filterForm" method="GET" action="{{ route('admin.blog-categories.index') }}">
        <div class="flex flex-col items-end justify-between gap-3 sm:flex-row sm:items-center">
            <div class="w-full sm:max-w-sm">
                <x-admin.form-input
                    type="text"
                    name="search"
                    x-model="search"
                    x-on:input="submitDebounced()"
                    placeholder="Search by name or description…"
                    :value="request('search')"
                >
                    <x-slot:leftIcon>
                        <x-icons.search class="h-4 w-4" />
                    </x-slot>
                </x-admin.form-input>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-40 shrink-0">
                    <x-admin.form-select name="status" x-on:change="$refs.filterForm.submit()">
                        <option value="">All Statuses</option>
                        @foreach (\App\Enums\CommonStatusEnum::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === (string) $status->value)>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </x-admin.form-select>
                </div>

                @if ($activeFilters > 0)
                    <a
                        href="{{ route('admin.blog-categories.index') }}"
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
