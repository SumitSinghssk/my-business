<x-admin :breadcrumb="[
    ['label' => 'Users', 'url' => route('admin.users.index')],
    ['label' => 'Create User']
]">
    <x-admin.card title="Create User" text="Add a new user account and assign roles or permissions">
        <form
            action="{{ route('admin.users.store') }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf

            @include('admin.users.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-cloak x-text="submitting ? 'Creating User...' : 'Create User'">Create User</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
