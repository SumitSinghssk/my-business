@php
    $canEdit = auth()
        ->user()
        ->can('admin.blog-categories.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.blog-categories.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['Category', 'Status', 'Description'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Blog Categories', 'url' => route('admin.blog-categories.index')]
]">
    <x-admin.card title="Blog Categories" text="Manage your blog categories and sub-categories">
        @can('admin.blog-categories.create')
            <x-slot name="actions">
                <a href="{{ route('admin.blog-categories.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Create Category
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.blog-categories.partials.filters')

        <x-admin.table :headers="$headers" :data="$categories" emptyMessage="No blog categories found.">
            @foreach ($categories as $category)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if ($category->image)
                                <img
                                    src="{{ asset('storage/' . $category->image) }}"
                                    alt="{{ $category->name }}"
                                    class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700"
                                />
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                    <x-icons.pages class="h-5 w-5 text-slate-400" />
                                </div>
                            @endif

                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $category->name }}
                                </span>
                                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    {{ $category->slug }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @can('admin.blog-categories.toogle-status')
                            <div
                                x-data="{ status: '{{ $category->status }}', loading: false }"
                                x-on:click="
                                    if (loading) return
                                    loading = true

                                    axios
                                        .patch('{{ route('admin.blog-categories.toggle-status', $category->id) }}')
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
                            <x-admin.status-badge :status="$category->status" />
                        @endcan
                    </td>

                    <td class="px-6 py-4">
                        <p class="line-clamp-1 max-w-xs text-xs text-slate-400 italic dark:text-slate-500">
                            {{ $category->description ? str()->limit($category->description, 80) : '—' }}
                        </p>
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :editRoute="route('admin.blog-categories.edit', $category)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.blog-categories.destroy', $category)"
                                :deleteId="$category->id"
                                :canDelete="$canDelete"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
