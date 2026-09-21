<x-admin :breadcrumb="[
    ['label' => 'Work', 'url' => route('admin.projects.index')],
    ['label' => 'Add Project']
]">
    <x-admin.card title="Add Project" text="Add a case study with its own project page">
        <form
            action="{{ route('admin.projects.store') }}"
            enctype="multipart/form-data"
            method="POST"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf

            @include('admin.projects.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Adding Project...' : 'Add Project'">Add Project</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
