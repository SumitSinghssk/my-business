@php
    $activeFilters = collect(request()->only(['status', 'category_id', 'search']))
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
    <form x-ref="filterForm" method="GET" action="{{ route('admin.blogs.index') }}">
        <div class="flex flex-col items-end justify-between gap-3 sm:flex-row sm:items-center">
            <div class="w-full sm:max-w-sm">
                <x-admin.form-input
                    type="text"
                    name="search"
                    x-model="search"
                    x-on:input="submitDebounced()"
                    placeholder="Search blogs by title…"
                    :value="request('search')"
                >
                    <x-slot:leftIcon>
                        <x-icons.search class="h-4 w-4" />
                    </x-slot>
                </x-admin.form-input>
            </div>

            <div class="flex w-full items-center gap-3 sm:w-auto">
                <div class="w-full sm:w-44">
                    <x-admin.form-select name="category_id" x-on:change="$refs.filterForm.submit()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-admin.form-select>
                </div>

                <div class="w-full sm:w-36">
                    <x-admin.form-select name="status" x-on:change="$refs.filterForm.submit()">
                        <option value="">All Status</option>
                        @foreach (\App\Enums\CommonStatusEnum::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === (string) $status->value)>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </x-admin.form-select>
                </div>

                @if ($activeFilters > 0)
                    <a
                        href="{{ route('admin.blogs.index') }}"
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
