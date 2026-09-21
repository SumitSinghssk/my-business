@php
    $canEdit = auth()
        ->user()
        ->can('admin.projects.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.projects.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['Project', 'Service', 'Status', 'Order'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Work', 'url' => route('admin.projects.index')]
]">
    <x-admin.card title="Work" text="Manage the case studies shown on the Work page">
        @can('admin.projects.create')
            <x-slot name="actions">
                <a href="{{ route('admin.projects.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Add Project
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.projects.partials.filters')

        <x-admin.table :headers="$headers" :data="$projects" emptyMessage="No projects found.">
            @foreach ($projects as $project)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if ($project->featured_image)
                                <img
                                    src="{{ asset('storage/' . $project->featured_image) }}"
                                    alt="{{ $project->title }}"
                                    class="aspect-16/10 w-16 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700"
                                />
                            @else
                                <div class="flex aspect-16/10 w-16 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                    <x-icons.gallery class="h-5 w-5 text-slate-400" />
                                </div>
                            @endif

                            <div class="flex min-w-0 flex-col">
                                <span class="flex items-center gap-1.5 text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $project->title }}
                                    @if ($project->is_featured)
                                        <span
                                            class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-700 uppercase dark:bg-amber-500/15 dark:text-amber-300"
                                        >
                                            Home
                                        </span>
                                    @endif
                                </span>
                                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    {{ collect([$project->client, $project->industry, $project->year])->filter()->implode(' · ') ?:'/work/' . $project->slug }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $project->service?->title ?? '—' }}</span>
                    </td>

                    <td class="px-6 py-4">
                        @can('admin.projects.toogle-status')
                            <div
                                x-data="{ status: '{{ $project->status->value }}', loading: false }"
                                x-on:click="
                                    if (loading) return
                                    loading = true
                                    axios
                                        .patch('{{ route('admin.projects.toggle-status', $project->id) }}')
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
                            <x-admin.status-badge :status="$project->status" />
                        @endcan
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $project->sort_order }}</span>
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :viewRoute="$project->slug ? route('work.show', $project->slug) : null"
                                :editRoute="route('admin.projects.edit', $project)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.projects.destroy', $project)"
                                :deleteId="$project->id"
                                :canDelete="$canDelete"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
