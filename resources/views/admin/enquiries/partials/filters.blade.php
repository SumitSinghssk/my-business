@php
    $activeFilters = collect(request()->only(['status', 'source', 'seen', 'date_from', 'date_to', 'search']))
        ->filter()
        ->count();
@endphp

<div
    x-data="{
        open: {{ $activeFilters > 0 ? 'true' : 'false' }},
        dateOpen: false,
        search: '{{ old('search', request('search')) }}',
        dateFrom: '{{ request('date_from') }}',
        dateTo: '{{ request('date_to') }}',
        debounceTimer: null,
        submitDebounced() {
            clearTimeout(this.debounceTimer)
            this.debounceTimer = setTimeout(
                () => this.$refs.filterForm.submit(),
                400,
            )
        },
        applyDates() {
            this.dateOpen = false
            this.$refs.filterForm.submit()
        },
        clearDates() {
            this.dateFrom = ''
            this.dateTo = ''
            this.$nextTick(() => this.$refs.filterForm.submit())
        },
        get dateLabel() {
            if (this.dateFrom && this.dateTo)
                return this.dateFrom + ' → ' + this.dateTo
            if (this.dateFrom) return 'From ' + this.dateFrom
            if (this.dateTo) return 'To ' + this.dateTo
            return 'Date Range'
        },
        get hasDate() {
            return this.dateFrom || this.dateTo
        },
    }"
    class="mb-5"
>
    <form x-ref="filterForm" method="GET" action="{{ route('admin.enquiries.index') }}">
        <input type="hidden" name="date_from" x-bind:value="dateFrom" />
        <input type="hidden" name="date_to" x-bind:value="dateTo" />

        <div class="flex flex-col items-end gap-3 sm:flex-row sm:items-center">
            <div class="w-full sm:max-w-sm">
                <x-admin.form-input
                    type="text"
                    name="search"
                    x-model="search"
                    x-on:input="submitDebounced()"
                    placeholder="Search by name, email, phone, company…"
                    :value="request('search')"
                >
                    <x-slot:leftIcon>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                    </x-slot>
                </x-admin.form-input>
            </div>

            <div class="flex items-center gap-3 sm:ml-auto">
                <x-admin.button variant="secondary" type="button" x-on:click="open = !open">
                    <span class="flex items-center gap-1.5">
                        <x-icons.filters class="h-4 w-4" />
                        Filters
                        @if ($activeFilters > 0)
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 text-xs font-semibold text-white">
                                {{ $activeFilters }}
                            </span>
                        @endif
                    </span>
                </x-admin.button>

                @if ($activeFilters > 0)
                    <a
                        href="{{ route('admin.enquiries.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-red-500 transition hover:text-red-600"
                    >
                        <x-icons.close class="h-4 w-4" />
                        Clear
                    </a>
                @endif
            </div>
        </div>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition duration-150 ease-out"
            x-transition:enter-start="-translate-y-1 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-100 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-1 opacity-0"
            class="mt-3 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:grid-cols-2 lg:grid-cols-4 dark:border-slate-700/60 dark:bg-slate-800/40"
        >
            <div>
                <x-admin.form-label for="status" label="Status" />
                <x-admin.form-select name="status" id="status" x-on:change="$refs.filterForm.submit()">
                    <option value="">All statuses</option>
                    @foreach (['new' => 'New', 'seen' => 'Seen', 'pending' => 'Pending', 'closed' => 'Closed'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </x-admin.form-select>
            </div>

            <div>
                <x-admin.form-label for="source" label="Source" />
                <x-admin.form-select name="source" id="source" x-on:change="$refs.filterForm.submit()">
                    <option value="">All sources</option>
                    @foreach ($sources as $source)
                        <option value="{{ $source }}" @selected(request('source') === $source)>{{ $source }}</option>
                    @endforeach
                </x-admin.form-select>
            </div>

            <div>
                <x-admin.form-label for="seen" label="Read Status" />
                <x-admin.form-select name="seen" id="seen" x-on:change="$refs.filterForm.submit()">
                    <option value="">All</option>
                    <option value="unseen" @selected(request('seen') === 'unseen')>Unread</option>
                    <option value="seen" @selected(request('seen') === 'seen')>Read</option>
                </x-admin.form-select>
            </div>

            <div>
                <x-admin.form-label label="Date Range" />

                <div class="relative" x-on:click.outside="dateOpen = false">
                    <button
                        type="button"
                        x-on:click="dateOpen = !dateOpen"
                        @disabled($disabled ?? false)
                        class="relative w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pr-11 pl-11 text-sm text-slate-900 transition-all hover:border-slate-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none sm:text-base dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:border-slate-600 dark:focus:bg-slate-900"
                        :class="hasDate ? 'text-slate-900 dark:text-white' : 'text-slate-400'"
                    >
                        <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <x-icons.calendar class="h-4 w-4" />
                        </div>

                        <span class="block truncate text-left" x-text="dateLabel"></span>

                        <div class="absolute inset-y-0 right-4 flex items-center gap-1">
                            <x-icons.chevron-down class="h-4 w-4 transition-transform duration-200" x-bind:class="dateOpen ? 'rotate-180' : ''" />
                        </div>
                    </button>

                    <div
                        x-show="dateOpen"
                        x-cloak
                        x-transition:enter="transition duration-150 ease-out"
                        x-transition:enter-start="-translate-y-1 scale-95 opacity-0"
                        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                        x-transition:leave="transition duration-100 ease-in"
                        x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                        x-transition:leave-end="-translate-y-1 scale-95 opacity-0"
                        class="absolute right-0 z-50 mt-1 w-72 rounded-xl border border-slate-200 bg-white p-4 shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <div class="space-y-3">
                            <div>
                                <x-admin.form-label for="source" label="From" />
                                <x-admin.form-input type="date" name="form_date" x-model="dateFrom" />
                            </div>
                            <div>
                                <x-admin.form-label for="source" label="To" />
                                <x-admin.form-input type="date" name="form_date" x-model="dateTo" x-bind:min="dateFrom" />
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-2">
                            <button
                                type="button"
                                x-on:click="clearDates()"
                                class="cursor-pointer text-xs font-medium text-slate-400 transition hover:text-red-500"
                            >
                                Clear
                            </button>
                            <button
                                type="button"
                                x-on:click="applyDates()"
                                class="cursor-pointer rounded-lg bg-blue-500 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-600"
                            >
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
