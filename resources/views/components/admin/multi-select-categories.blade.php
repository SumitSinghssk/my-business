@props([
    'name' => 'category_ids',
    'categories' => [],
    'selectedCategoryIds' => [],
    'placeholder' => 'Select categories...',
    'disabled' => false,
])

@php
    $hasLeftIcon = isset($leftIcon);
    $paddingClasses = $hasLeftIcon ? 'pl-11' : 'pl-4';

    $stateClasses = ! $disabled
        ? 'border-slate-200 bg-slate-50/50 text-slate-900 focus-within:border-blue-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus-within:bg-slate-900'
        : 'cursor-not-allowed border-slate-200 bg-slate-100 text-slate-400 dark:border-slate-700 dark:bg-slate-800/50';
@endphp

<div
    x-data="{
        open: false,
        search: '',
        selected: @js(old($name, $selectedCategoryIds ?? [])),
        categories: @js(
                    $categories
                        ->map(
                            fn ($p) => [
                                'id' => $p->id,
                                'name' => $p->name,
                                'children' => $p->children->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values(),
                            ],
                        )
                        ->values()
                ),

        get allItems() {
            const items = []
            this.categories.forEach((p) => {
                items.push(p)
                ;(p.children || []).forEach((c) => items.push(c))
            })
            return items
        },

        nameOf(id) {
            return this.allItems.find((i) => i.id == id)?.name ?? id
        },

        toggle(id) {
            if ({{ $disabled ? 'true' : 'false' }}) return
            id = parseInt(id)
            this.selected.includes(id)
                ? (this.selected = this.selected.filter((s) => s !== id))
                : this.selected.push(id)
        },

        remove(id) {
            if ({{ $disabled ? 'true' : 'false' }}) return
            this.selected = this.selected.filter((s) => s !== parseInt(id))
        },

        get filtered() {
            if (! this.search) return this.categories
            const q = this.search.toLowerCase()
            return this.categories
                .map((p) => {
                    const pMatch = p.name.toLowerCase().includes(q)
                    const kids = (p.children || []).filter((c) =>
                        c.name.toLowerCase().includes(q),
                    )
                    if (pMatch || kids.length)
                        return { ...p, children: pMatch ? p.children : kids }
                    return null
                })
                .filter(Boolean)
        },
    }"
    x-on:click.outside="open = false; search = ''"
    class="relative w-full"
>
    <template x-for="id in selected" :key="id">
        <input type="hidden" name="{{ $name }}[]" :value="id" />
    </template>

    <div class="relative w-full">
        @if ($hasLeftIcon)
            <div
                class="{{ $disabled ? 'text-slate-300 dark:text-slate-600' : 'text-slate-400' }} pointer-events-none absolute inset-y-0 left-4 z-10 flex items-center"
            >
                {{ $leftIcon }}
            </div>
        @endif

        <div
            x-on:click="
                if (! {{ $disabled ? 'true' : 'false' }}) {
                    open = ! open
                    if (open) $nextTick(() => $refs.searchInput?.focus())
                }
            "
            class="{{ $paddingClasses }} {{ $stateClasses }} relative flex min-h-12.5 w-full cursor-pointer flex-wrap items-center gap-2 rounded-xl border py-2 pr-10 text-sm transition-all sm:text-base"
        >
            <template x-if="selected.length === 0">
                <span class="text-slate-400">{{ $placeholder }}</span>
            </template>

            <div class="flex flex-wrap gap-1.5">
                <template x-for="id in selected" :key="id">
                    <span
                        class="inline-flex items-center gap-1 rounded-lg border border-blue-200 bg-blue-50/50 py-0.5 pr-1 pl-2 text-xs font-medium text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-400"
                    >
                        <span x-text="nameOf(id)"></span>
                        <button
                            type="button"
                            x-on:click.stop="remove(id)"
                            class="flex h-4 w-4 cursor-pointer items-center justify-center rounded-md hover:bg-blue-200/50 dark:hover:bg-blue-400/20"
                        >
                            <x-icons.close class="h-2 w-2" />
                        </button>
                    </span>
                </template>
            </div>
        </div>

        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
            <x-icons.chevron-down class="h-4 w-4 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
        </div>
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="translate-y-2 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        class="absolute right-0 left-0 z-60 mt-1 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900"
    >
        <div
            class="sticky top-0 flex items-center gap-2 border-b border-slate-100 bg-slate-50/50 px-3 py-2 dark:border-slate-800 dark:bg-slate-800/50"
        >
            <x-icons.search class="h-4 w-4 shrink-0 text-slate-400" />
            <input
                x-ref="searchInput"
                x-model="search"
                x-on:click.stop
                type="text"
                placeholder="Search categories..."
                class="w-full bg-transparent text-sm text-slate-900 placeholder-slate-400 outline-none dark:text-white"
            />
        </div>

        <div class="max-h-62.5 overflow-y-auto p-1">
            <template x-if="filtered.length === 0">
                <div class="px-4 py-8 text-center text-sm text-slate-400">No results found</div>
            </template>

            <template x-for="parent in filtered" :key="parent.id">
                <div class="mb-1 last:mb-0">
                    <div
                        x-on:click.stop="toggle(parent.id)"
                        class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
                        :class="selected.includes(parent.id)
                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'"
                    >
                        <div
                            class="relative flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-all"
                            :class="selected.includes(parent.id) ? 'border-blue-600 bg-blue-600' : 'border-slate-300 dark:border-slate-600'"
                        >
                            <x-icons.check x-show="selected.includes(parent.id)" class="h-2.5 w-2.5 text-white" stroke-width="2" />
                        </div>
                        <span class="font-semibold" x-text="parent.name"></span>
                    </div>

                    <template x-for="child in parent.children" :key="child.id">
                        <div
                            x-on:click.stop="toggle(child.id)"
                            class="mt-1 flex cursor-pointer items-center gap-3 rounded-lg py-2 pr-3 pl-9 text-sm transition-colors"
                            :class="selected.includes(child.id)
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'"
                        >
                            <div
                                class="relative flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-all"
                                :class="selected.includes(child.id) ? 'border-blue-600 bg-blue-600' : 'border-slate-300 dark:border-slate-600'"
                            >
                                <x-icons.check x-show="selected.includes(child.id)" class="h-2.5 w-2.5 text-white" stroke-width="2" />
                            </div>
                            <span x-text="child.name"></span>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
