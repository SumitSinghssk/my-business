<x-admin :breadcrumb="[
    ['label' => 'Pages', 'url' => route('admin.pages.index')],
    ['label' => 'Create Page']
]">
    <x-admin.card title="Create Page" text="Write and publish a new page">
        <form
            action="{{ route('admin.pages.store') }}"
            enctype="multipart/form-data"
            method="POST"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf

            @include('admin.pages.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Creating Page...' : 'Create Page'"></span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
