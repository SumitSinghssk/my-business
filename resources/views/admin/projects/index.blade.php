@php
    $canEdit = auth()
        ->user()
        ->can('admin.projects.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.projects.delete');
    $canToggle = auth()
        ->user()
        ->can('admin.projects.toogle-status');
    $canManage = $canEdit || $canDelete;

    $headers = ['Project', 'Service', 'Status', 'Order'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Work', 'url' => route('admin.projects.index')]
]">
    <x-admin.page-header
        title="Work"
        description="The case studies shown on the Work page of your website."
        icon="briefcase"
        :count="$projects->total()"
    >
        @can('admin.projects.create')
            <x-slot:actions>
                <x-admin.button :href="route('admin.projects.create')" icon="plus">New project</x-admin.button>
            </x-slot>
        @endcan
    </x-admin.page-header>

    <x-admin.table :headers="$headers" :data="$projects" emptyMessage="No projects found" emptyIcon="briefcase">
        <x-slot:toolbar>
            @include('admin.projects.partials.filters')
        </x-slot>

        @foreach ($projects as $project)
            @php
                $meta = collect([$project->client, $project->industry, $project->year])
                    ->filter()
                    ->implode(' · ');
            @endphp

            <tr>
                <td class="max-w-md">
                    <div class="flex items-center gap-3">
                        <x-admin.thumb
                            :src="$project->featured_image ? asset('storage/' . $project->featured_image) : null"
                            icon="briefcase"
                            class="h-10 w-14"
                        />

                        <div class="min-w-0">
                            <div class="flex min-w-0 items-center gap-1.5">
                                @if ($canEdit)
                                    <a
                                        href="{{ route('admin.projects.edit', $project) }}"
                                        class="line-clamp-1 font-medium text-slate-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                    >
                                        {{ $project->title }}
                                    </a>
                                @else
                                    <span class="line-clamp-1 font-medium text-slate-900 dark:text-white">{{ $project->title }}</span>
                                @endif
                                @if ($project->is_featured)
                                    <span
                                        class="inline-flex shrink-0 items-center gap-1 rounded-md bg-amber-50 px-1.5 py-0.5 text-[11px] font-medium text-amber-700 ring-1 ring-amber-200 ring-inset dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20"
                                        title="Featured on the home page"
                                    >
                                        <x-admin.icon name="star" class="h-3 w-3" />
                                        Home
                                    </span>
                                @endif
                            </div>
                            <span class="mt-0.5 flex items-center gap-1 text-xs text-slate-400">
                                @if ($meta)
                                    <x-admin.icon name="building" class="h-3 w-3 shrink-0" />
                                    <span class="truncate">{{ $meta }}</span>
                                @else
                                    <x-admin.icon name="link" class="h-3 w-3 shrink-0" />
                                    <span class="truncate">/work/{{ $project->slug }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </td>

                <td>
                    @if ($project->service)
                        <span
                            class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-white px-1.5 py-0.5 text-xs font-medium whitespace-nowrap text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                        >
                            <x-admin.icon name="layers" class="h-3 w-3 text-slate-400" />
                            {{ $project->service->title }}
                        </span>
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </td>

                <td>
                    <x-admin.status-toggle
                        :url="route('admin.projects.toggle-status', $project->id)"
                        :status="$project->status"
                        :can="$canToggle"
                    />
                </td>

                <td>
                    <span
                        class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 tabular-nums dark:bg-slate-800 dark:text-slate-300"
                        title="Display order"
                    >
                        <x-admin.icon name="hash" class="h-3 w-3 text-slate-400" />
                        {{ $project->sort_order }}
                    </span>
                </td>

                @if ($canManage)
                    <td>
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
</x-admin>
