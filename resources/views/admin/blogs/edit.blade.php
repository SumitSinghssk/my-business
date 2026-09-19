<x-admin :breadcrumb="[
    ['label' => 'Blogs', 'url' => route('admin.blogs.index')],
    ['label' => 'Edit Post']
]">
    <x-admin.card title="Edit Blog Post" text="Update post details, content, image, and SEO metadata">
        <x-slot name="actions">
            @if ($blog->seo)
                @can('admin.seo.edit')
                    <a href="{{ route('admin.seo.edit', $blog->seo->id) }}">
                        <x-admin.button type="button" variant="secondary">Update SEO</x-admin.button>
                    </a>
                @endcan
            @else
                @can('admin.seo.create')
                    <a href="{{ route('admin.seo.create', ['model_type' => 'blog', 'model_id' => $blog->id]) }}">
                        <x-admin.button type="button" variant="secondary">Add SEO</x-admin.button>
                    </a>
                @endcan
            @endif

            @can('admin.blogs.delete')
                <x-admin.delete-button :route="route('admin.blogs.destroy', $blog)" />
            @endcan
        </x-slot>

        <form
            action="{{ route('admin.blogs.update', $blog->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.blogs.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Updating Post...' : 'Update Post'">Update Post</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
