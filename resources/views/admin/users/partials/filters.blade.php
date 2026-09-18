@php
    $activeFilters = collect(request()->only(['status', 'role', 'search']))
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
    <form x-ref="filterForm" method="GET" action="{{ route('admin.users.index') }}">
        <div class="flex flex-col items-end justify-between gap-3 sm:flex-row sm:items-center">
            <div class="w-full sm:max-w-sm">
                <x-admin.form-input
                    type="text"
                    name="search"
                    x-model="search"
                    x-on:input="submitDebounced()"
                    placeholder="Search by name or email…"
                    :value="request('search')"
                >
                    <x-slot:leftIcon>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                    </x-slot>
                </x-admin.form-input>
            </div>

            <div class="flex w-full items-center gap-3 sm:w-auto">
                <div class="w-full sm:w-40">
                    <x-admin.form-select name="role" x-on:change="$refs.filterForm.submit()">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                                {{ ucfirst($role->name) }}
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
                        href="{{ route('admin.users.index') }}"
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
