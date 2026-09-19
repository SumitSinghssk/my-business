<x-admin :breadcrumb="[
    ['label' => 'Pages', 'url' => route('admin.pages.index')],
    ['label' => 'Edit Page']
]">
    <x-admin.card title="Edit Page" text="Update page details, content, image, and SEO metadata">
        <x-slot name="actions">
            @if ($page->seo)
                @can('admin.seo.edit')
                    <a href="{{ route('admin.seo.edit', $page->seo->id) }}">
                        <x-admin.button type="button" variant="secondary">Update SEO</x-admin.button>
                    </a>
                @endcan
            @else
                @can('admin.seo.create')
                    <a href="{{ route('admin.seo.create', ['model_type' => 'page', 'model_id' => $page->id]) }}">
                        <x-admin.button type="button" variant="secondary">Add SEO</x-admin.button>
                    </a>
                @endcan
            @endif

            @can('admin.pages.delete')
                <x-admin.delete-button :route="route('admin.pages.destroy', $page)" />
            @endcan
        </x-slot>

        <form
            action="{{ route('admin.pages.update', $page->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.pages.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Updating Page...' : 'Update Page'">Update Page</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
