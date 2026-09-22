@php
    $canEdit = auth()
        ->user()
        ->can('admin.services.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.services.delete');
    $canToggle = auth()
        ->user()
        ->can('admin.services.toogle-status');
    $canManage = $canEdit || $canDelete;

    $headers = ['Service', 'Status', 'Order', 'Updated'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Services', 'url' => route('admin.services.index')]
]">
    <x-admin.page-header
        title="Services"
        description="The services shown on your website and their detail pages."
        icon="layers"
        :count="$services->total()"
    >
        @can('admin.services.create')
            <x-slot:actions>
                <x-admin.button :href="route('admin.services.create')" icon="plus">New service</x-admin.button>
            </x-slot>
        @endcan
    </x-admin.page-header>

    <x-admin.table :headers="$headers" :data="$services" emptyMessage="No services found" emptyIcon="layers">
        <x-slot:toolbar>
            @include('admin.services.partials.filters')
        </x-slot>

        @foreach ($services as $service)
            <tr>
                <td class="max-w-md">
                    <div class="flex items-center gap-3">
                        <x-admin.thumb
                            :src="$service->featured_image ? asset('storage/' . $service->featured_image) : null"
                            icon="layers"
                            class="h-10 w-14"
                        />

                        <div class="min-w-0">
                            @if ($canEdit)
                                <a
                                    href="{{ route('admin.services.edit', $service) }}"
                                    class="line-clamp-1 font-medium text-slate-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                >
                                    {{ $service->title }}
                                </a>
                            @else
                                <span class="line-clamp-1 font-medium text-slate-900 dark:text-white">{{ $service->title }}</span>
                            @endif
                            <span class="mt-0.5 flex items-center gap-1 text-xs text-slate-400">
                                <x-admin.icon name="link" class="h-3 w-3 shrink-0" />
                                <span class="truncate">/services/{{ $service->slug }}</span>
                            </span>
                        </div>
                    </div>
                </td>

                <td>
                    <x-admin.status-toggle
                        :url="route('admin.services.toggle-status', $service->id)"
                        :status="$service->status"
                        :can="$canToggle"
                    />
                </td>

                <td>
                    <span
                        class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 tabular-nums dark:bg-slate-800 dark:text-slate-300"
                        title="Display order"
                    >
                        <x-admin.icon name="hash" class="h-3 w-3 text-slate-400" />
                        {{ $service->sort_order }}
                    </span>
                </td>

                <td class="whitespace-nowrap">
                    @if ($service->updated_at)
                        <span class="block text-slate-700 dark:text-slate-200">{{ $service->updated_at->format('d M Y') }}</span>
                        <span class="text-xs text-slate-400">{{ $service->updated_at->diffForHumans() }}</span>
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </td>

                @if ($canManage)
                    <td>
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
</x-admin>
