{{--
    Create / edit page: page header (outside the form, so header actions like Delete can hold their
    own forms), the form, and a sticky save bar. The slot is the form body; lay it out with
    <x-admin.form-grid> (main column + side column) and <x-admin.card> sections.
    
    <x-admin.form-page :action="route('admin.blogs.update', $blog)" method="PUT" upload
    title="Edit post" description="…" icon="newspaper" :back="route('admin.blogs.index')"
    submit="Save changes" submitting="Saving…">
    <x-slot:actions>…</x-slot>
    …fields…
    </x-admin.form-page>
--}}

@props([
    'action',
    'method' => 'POST',
    'upload' => false,
    'title',
    'description' => null,
    'icon' => null,
    'back' => null,
    'submit' => 'Save',
    'submitting' => 'Saving…',
    'formData' => '{ submitting: false }',
])

<div>
    <x-admin.page-header :title="$title" :description="$description" :icon="$icon" :back="$back">
        @isset($actions)
            <x-slot:actions>
                {{ $actions }}
            </x-slot>
        @endisset
    </x-admin.page-header>

    <form
        action="{{ $action }}"
        method="POST"
        @if ($upload) enctype="multipart/form-data" @endif
        x-data="{{ $formData }}"
        x-on:submit="submitting = true"
        {{ $attributes }}
    >
        @csrf
        @unless (strtoupper($method) === 'POST')
            @method($method)
        @endunless

        {{ $slot }}

        <div
            class="sticky -bottom-4.5 z-30 mt-6 flex items-center justify-between gap-3 border-t border-slate-200/80 bg-slate-50/90 px-4 py-3 backdrop-blur-md sm:-mx-6 sm:px-6 lg:px-8 dark:border-slate-800 dark:bg-slate-950/90"
        >
            <p class="hidden items-center gap-1.5 text-xs text-slate-500 sm:flex dark:text-slate-400">
                <x-admin.icon name="info" class="h-3.5 w-3.5" />
                Fields marked
                <span class="font-semibold text-red-500">*</span>
                are required.
            </p>

            <div class="flex w-full items-center justify-end gap-2 sm:w-auto">
                @if ($back)
                    <x-admin.button variant="secondary" :href="$back">Cancel</x-admin.button>
                @endif

                <x-admin.button icon="check">
                    <span x-text="submitting ? @js($submitting) : @js($submit)">{{ $submit }}</span>
                </x-admin.button>
            </div>
        </div>
    </form>
</div>
