@php
    $canEdit = auth()
        ->user()
        ->can('admin.pages.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.pages.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['Page', 'Status', 'Published', 'Author'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Pages', 'url' => route('admin.pages.index')]
]">
    <x-admin.card title="Pages" text="Manage your site pages">
        @can('admin.pages.create')
            <x-slot name="actions">
                <a href="{{ route('admin.pages.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Create Page
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.pages.partials.filters')

        <x-admin.table :headers="$headers" :data="$pages" emptyMessage="No pages found.">
            @foreach ($pages as $page)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if ($page->featured_image)
                                <img
                                    src="{{ asset('storage/' . $page->featured_image) }}"
                                    alt="{{ $page->title }}"
                                    class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700"
                                />
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                    <x-icons.pages class="h-5 w-5 text-slate-400" />
                                </div>
                            @endif

                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $page->title }}
                                </span>
                                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    {{ $page->slug }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @can('admin.pages.toogle-status')
                            <div
                                x-data="{ status: '{{ $page->status }}', loading: false }"
                                x-on:click="
                                    if (loading) return
                                    loading = true
                                    axios
                                        .patch('{{ route('admin.pages.toggle-status', $page->id) }}')
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
                            <x-admin.status-badge :status="$page->status" />
                        @endcan
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $page->published_at ? $page->published_at->format('d M Y') : '—' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-600 dark:text-slate-300">
                            {{ $page->user?->name ?? '—' }}
                        </span>
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :editRoute="route('admin.pages.edit', $page)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.pages.destroy', $page)"
                                :deleteId="$page->id"
                                :canDelete="$canDelete"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
