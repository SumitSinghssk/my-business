<x-admin :breadcrumb="[
    ['label' => 'Blog Categories', 'url' => route('admin.blog-categories.index')],
    ['label' => 'Create Category']
]">
    <x-admin.card title="Create Blog Category" text="Add a new category to organise your blog posts">
        <form
            action="{{ route('admin.blog-categories.store') }}"
            enctype="multipart/form-data"
            method="POST"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf

            @include('admin.blog-categories.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Creating Category...' : 'Create Category'"></span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
