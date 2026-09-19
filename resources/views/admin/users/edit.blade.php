<x-admin :breadcrumb="[
    ['label' => 'Users', 'url' => route('admin.users.index')],
    ['label' => 'Edit User']
]">
    <x-admin.card title="Edit User" text="Update user account details, roles, and permissions">
        @can('admin.users.delete')
            <x-slot name="actions">
                @if ($user->id !== auth()->id())
                    <x-admin.delete-button :route="route('admin.users.destroy', $user)" />
                @endif
            </x-slot>
        @endcan

        <form
            action="{{ route('admin.users.update', $user) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.users.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-cloak x-text="submitting ? 'Updating User...' : 'Update User'">Update User</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
