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
                <x-icons.eye class="h-4 w-4" />
                @if (! $isSmall)
                    <span>View</span>
                @endif
            </a>
        </x-admin.tooltip>
    @endif

    @if ($viewClick && $canView)
        <x-admin.tooltip text="View">
            <button type="button" x-on:click="{{ $viewClick }}" class="{{ $viewStyles }}">
                <x-icons.eye class="h-4 w-4" />
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
                <x-icons.edit class="h-4 w-4" />
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
                    <x-icons.delete class="h-4 w-4" />
                    @if (! $isSmall)
                        <span>Delete</span>
                    @endif
                </button>
            </form>
        </x-admin.tooltip>
    @endif
</div>
