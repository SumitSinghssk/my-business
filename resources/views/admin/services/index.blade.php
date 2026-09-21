@php
    $canEdit = auth()
        ->user()
        ->can('admin.services.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.services.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['Service', 'Status', 'Order', 'Updated'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Services', 'url' => route('admin.services.index')]
]">
    <x-admin.card title="Services" text="Manage the services shown on the website and their detail pages">
        @can('admin.services.create')
            <x-slot name="actions">
                <a href="{{ route('admin.services.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Create Service
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.services.partials.filters')

        <x-admin.table :headers="$headers" :data="$services" emptyMessage="No services found.">
            @foreach ($services as $service)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if ($service->featured_image)
                                <img
                                    src="{{ asset('storage/' . $service->featured_image) }}"
                                    alt="{{ $service->title }}"
                                    class="aspect-16/10 w-16 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700"
                                />
                            @else
                                <div class="flex aspect-16/10 w-16 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                    <x-icons.desktop class="h-5 w-5 text-slate-400" />
                                </div>
                            @endif

                            <div class="flex min-w-0 flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $service->title }}</span>
                                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">/services/{{ $service->slug }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @can('admin.services.toogle-status')
                            <div
                                x-data="{ status: '{{ $service->status->value }}', loading: false }"
                                x-on:click="
                                    if (loading) return
                                    loading = true
                                    axios
                                        .patch('{{ route('admin.services.toggle-status', $service->id) }}')
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
                            <x-admin.status-badge :status="$service->status" />
                        @endcan
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $service->sort_order }}</span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $service->updated_at?->format('d M Y') ?? '—' }}</span>
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :viewRoute="$service->slug ? route('services.show', $service->slug) : null"
                                :editRoute="route('admin.services.edit', $service)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.services.destroy', $service)"
                                :deleteId="$service->id"
                                :canDelete="$canDelete"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
