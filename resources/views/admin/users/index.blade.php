@php
    $canEdit = auth()
        ->user()
        ->can('admin.users.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.users.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['User', 'Roles', 'Status'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Users', 'url' => route('admin.users.index')]
]">
    <x-admin.card title="Users" text="Manage user accounts, roles, and permissions">
        @can('admin.users.create')
            <x-slot name="actions">
                <a href="{{ route('admin.users.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Create User
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.users.partials.filters')

        <x-admin.table :headers="$headers" :data="$users" emptyMessage="No users found.">
            @foreach ($users as $user)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-8 w-8 rounded-full object-cover" />
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span
                                            class="ml-1 inline-flex items-center rounded-sm bg-blue-100 px-1.5 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-900/40 dark:text-blue-400"
                                        >
                                            You
                                        </span>
                                    @endif
                                </span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $user->email }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($user->roles as $role)
                                <span
                                    class="inline-flex items-center rounded-sm bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 capitalize dark:bg-blue-900/30 dark:text-blue-400"
                                >
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 dark:text-slate-500">No roles</span>
                            @endforelse
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @can('admin.users.toogle-status')
                            <div
                                x-data="{ status: '{{ $user->status }}', loading: false }"
                                x-on:click="
                                    if (loading) return
                                    loading = true

                                    axios
                                        .patch('{{ route('admin.users.toggle-status', $user->id) }}')
                                        .then((res) => {
                                            status = res.data.status
                                        })
                                        .catch((err) => console.error(err))
                                        .finally(() => (loading = false))
                                "
                                :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer inline'"
                            >
                                <template x-if="status === 'active'">
                                    <x-admin.status-badge status="active" />
                                </template>

                                <template x-if="status === 'inactive'">
                                    <x-admin.status-badge status="inactive" />
                                </template>
                            </div>
                        @else
                            <x-admin.status-badge :status="$user->status" />
                        @endcan
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :editRoute="route('admin.users.edit', $user)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.users.destroy', $user)"
                                :deleteId="$user->id"
                                :canDelete="$canDelete && $user->id !== auth()->id()"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
