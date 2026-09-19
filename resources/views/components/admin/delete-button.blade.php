@props([
    'route',
    'id' => null,
    'modalName' => null,
    'size' => 'lg',
    'title' => 'Delete Confirmation',
    'message' => 'Are you sure you want to permanently delete this item? This action cannot be undone.',
    'buttonClass' => '',
])

@php
    $name = $modalName ?? 'delete-modal-' . ($id ?? Str::random(8));
    $isSmall = $size === 'sm';

    $defaultClass = $isSmall
        ? 'inline-flex h-7.5 w-7.5 cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-xs transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-600 active:scale-90 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-red-500/10 dark:hover:text-red-400'
        : 'inline-flex h-8.5 cursor-pointer items-center gap-1.5 rounded-lg border border-red-100 bg-red-50 px-3 text-xs font-semibold text-red-600 transition-all hover:bg-red-600 hover:text-white active:scale-95 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white';
@endphp

<div class="inline-block">
    <x-admin.tooltip text="Delete">
        <button
            type="button"
            x-on:click="$dispatch('open-modal', '{{ $name }}')"
            {{ $attributes->merge(['class' => $buttonClass ?: $defaultClass]) }}
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
            </svg>
            @if (! $isSmall)
                <span>Delete Item</span>
            @endif
        </button>
    </x-admin.tooltip>

    <x-modal name="{{ $name }}" :show="false" maxWidth="sm">
        <div class="p-8 text-center dark:bg-slate-900">
            <div
                class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-red-600 ring-8 ring-red-50/50 dark:bg-red-900/20 dark:text-red-500 dark:ring-red-900/10"
            >
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
            </div>

            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $message }}</p>

            <div class="mt-8 flex flex-col gap-3">
                <form action="{{ $route }}" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-lg bg-red-500 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-500/20 transition-all hover:bg-red-600 active:scale-95"
                    >
                        Confirm Permanent Deletion
                    </button>
                </form>

                <button
                    type="button"
                    x-on:click="$dispatch('close-modal', '{{ $name }}')"
                    class="w-full cursor-pointer rounded-lg bg-slate-100 py-2.5 text-sm font-semibold text-slate-600 transition-all hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                >
                    No, Go Back
                </button>
            </div>
        </div>
    </x-modal>
</div>
