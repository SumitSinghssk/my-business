@php
    $canEdit = auth()
        ->user()
        ->can('admin.blogs.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.blogs.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['Post', 'Categories', 'Status', 'Published', 'Author'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Blogs', 'url' => route('admin.blogs.index')]
]">
    <x-admin.card title="Blog Posts" text="Manage your blog posts">
        @can('admin.blogs.create')
            <x-slot name="actions">
                <a href="{{ route('admin.blogs.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Create Post
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.blogs.partials.filters')

        <x-admin.table :headers="$headers" :data="$blogs" emptyMessage="No blog posts found.">
            @foreach ($blogs as $blog)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if ($blog->featured_image)
                                <img
                                    src="{{ asset('storage/' . $blog->featured_image) }}"
                                    alt="{{ $blog->title }}"
                                    class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700"
                                />
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                    <x-icons.pages class="h-5 w-5 text-slate-400" />
                                </div>
                            @endif

                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $blog->title }}
                                </span>
                                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    {{ $blog->slug }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($blog->categories as $category)
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300"
                                >
                                    {{ $category->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 italic">—</span>
                            @endforelse
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @can('admin.blogs.toogle-status')
                            <div
                                x-data="{ status: '{{ $blog->status }}', loading: false }"
                                x-on:click="
                                    if (loading) return
                                    loading = true

                                    axios
                                        .patch('{{ route('admin.blogs.toggle-status', $blog->id) }}')
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
                            <x-admin.status-badge :status="$blog->status" />
                        @endcan
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $blog->published_at ? $blog->published_at->format('d M Y') : '—' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-600 dark:text-slate-300">
                            {{ $blog->author?->name ?? '—' }}
                        </span>
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :editRoute="route('admin.blogs.edit', $blog)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.blogs.destroy', $blog)"
                                :deleteId="$blog->id"
                                :canDelete="$canDelete"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
