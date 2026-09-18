<x-admin :breadcrumb="[
    ['label' => 'Blog Categories', 'url' => route('admin.blog-categories.index')],
    ['label' => 'Edit Category']
]">
    <x-admin.card title="Edit Blog Category" text="Update category details, image, and SEO metadata">
        <x-slot name="actions">
            @if ($blogCategory->seo)
                @can('admin.seo.edit')
                    <a href="{{ route('admin.seo.edit', $blogCategory->seo->id) }}">
                        <x-admin.button type="button" variant="secondary">Update SEO</x-admin.button>
                    </a>
                @endcan
            @else
                @can('admin.seo.create')
                    <a href="{{ route('admin.seo.create', ['model_type' => 'blog_category', 'model_id' => $blogCategory->id]) }}">
                        <x-admin.button type="button" variant="secondary">Add SEO</x-admin.button>
                    </a>
                @endcan
            @endif

            @can('admin.blog-categories.delete')
                <x-admin.delete-button :route="route('admin.blog-categories.destroy', $blogCategory)" />
            @endcan
        </x-slot>

        <form
            action="{{ route('admin.blog-categories.update', $blogCategory->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.blog-categories.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Updating Category...' : 'Update Category'"></span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
