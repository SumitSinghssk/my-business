@props([
    'size' => 'lg',

    'viewRoute' => null,
    'viewClick' => null,
    'canView' => true,

    'editRoute' => null,
    'canEdit' => true,

    'deleteRoute' => null,
    'deleteId' => null,
    'deleteModalName' => null,

    'deleteFormAction' => null,
    'deleteFormField' => null,
    'deleteConfirmMessage' => 'Are you sure? This cannot be undone.',

    'canDelete' => true,
])

@php
    $isSmall = $size === 'sm';
    $baseClass = $isSmall
        ? 'inline-flex h-7.5 w-7.5 items-center justify-center rounded-lg border shadow-xs transition-all duration-200 active:scale-90'
        : 'inline-flex h-8.5 items-center gap-1.5 rounded-lg border px-3 text-xs font-semibold shadow-sm transition-all duration-200 active:scale-95';

    $viewStyles = "$baseClass border-slate-200 bg-white text-slate-600 hover:border-sky-200 hover:bg-sky-50 hover:text-sky-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-sky-500/10 dark:hover:text-sky-400 cursor-pointer";
    $editStyles = "$baseClass border-slate-200 bg-white text-slate-600 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 cursor-pointer";
    $deleteFormStyles = "$baseClass border-slate-200 bg-white text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-red-500/10 dark:hover:text-red-400 cursor-pointer";
@endphp

<div class="flex items-center gap-2 sm:gap-3">
    @if ($viewRoute && $canView)
        <x-admin.tooltip text="View">
            <a href="{{ $viewRoute }}" class="{{ $viewStyles }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                </svg>
                @if (! $isSmall)
                    <span>View</span>
                @endif
            </a>
        </x-admin.tooltip>
    @endif

    @if ($viewClick && $canView)
        <x-admin.tooltip text="View">
            <button type="button" x-on:click="{{ $viewClick }}" class="{{ $viewStyles }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                </svg>
                @if (! $isSmall)
                    <span>View</span>
                @endif
            </button>
        </x-admin.tooltip>
    @endif

    {{-- Edit --}}
    @if ($editRoute && $canEdit)
        <x-admin.tooltip text="Edit">
            <a href="{{ $editRoute }}" class="{{ $editStyles }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                </svg>
                @if (! $isSmall)
                    <span>Edit</span>
                @endif
            </a>
        </x-admin.tooltip>
    @endif

    {{-- Delete: standard modal/component-based --}}
    @if ($deleteRoute && $canDelete)
        <x-admin.delete-button :route="$deleteRoute" :id="$deleteId" :modalName="$deleteModalName" :size="$size" />
    @endif

    {{-- Delete: form POST-based (e.g. log file deletion) --}}
    @if ($deleteFormAction && $canDelete)
        <x-admin.tooltip text="Delete">
            <form
                method="POST"
                action="{{ $deleteFormAction }}"
                x-data
                x-on:submit.prevent="if (confirm('{{ $deleteConfirmMessage }}')) $el.submit()"
            >
                @csrf
                @method('DELETE')
                @if ($deleteFormField)
                    <input type="hidden" name="{{ $deleteFormField['name'] }}" value="{{ $deleteFormField['value'] }}" />
                @endif

                <button type="submit" class="{{ $deleteFormStyles }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                    @if (! $isSmall)
                        <span>Delete</span>
                    @endif
                </button>
            </form>
        </x-admin.tooltip>
    @endif
</div>
