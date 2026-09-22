<x-admin :breadcrumb="[
    ['label' => 'Users', 'url' => route('admin.users.index')],
    ['label' => 'Edit User']
]">
    <x-admin.form-page
        :action="route('admin.users.update', $user)"
        method="PUT"
        upload
        title="Edit user"
        :description="$user->name . ' · ' . $user->email"
        :back="route('admin.users.index')"
        submit="Save changes"
        submitting="Saving…"
    >
        @can('admin.users.delete')
            @if ($user->id !== auth()->id())
                <x-slot:actions>
                    <x-admin.delete-button :route="route('admin.users.destroy', $user)" title="Delete this user?" />
                </x-slot>
            @endif
        @endcan

        @include('admin.users.partials.form')
    </x-admin.form-page>
</x-admin>
