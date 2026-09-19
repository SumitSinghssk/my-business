<x-admin :breadcrumb="[
    ['label' => 'SEO', 'url' => route('admin.seo.index')],
    ['label' => 'Edit SEO']
]">
    <x-admin.card title="Edit SEO" text="Update SEO metadata, scripts, and structured data">
        @can('admin.seo.delete')
            <x-slot name="actions">
                <x-admin.delete-button :route="route('admin.seo.destroy', $seo)" />
            </x-slot>
        @endcan

        <form
            action="{{ route('admin.seo.update', $seo->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.seo.partials.form')

            <div class="flex items-center justify-end">
                <x-admin.button>
                    <span x-text="submitting ? 'Updating SEO...' : 'Update SEO'">Update SEO</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
