@php
    $canEdit = auth()
        ->user()
        ->can('admin.seo.edit');
    $canDelete = auth()
        ->user()
        ->can('admin.seo.delete');
    $canManage = $canEdit || $canDelete;

    $headers = ['Page & Info', 'SEO Details'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'SEO Settings', 'url' => route('admin.seo.index')]
]">
    <x-admin.card title="SEO Settings" text="Manage your global SEO metadata and social assets">
        @can('admin.seo.create')
            <x-slot name="actions">
                <a href="{{ route('admin.seo.create') }}">
                    <x-admin.button variant="primary">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">+</span>
                            Create Record
                        </span>
                    </x-admin.button>
                </a>
            </x-slot>
        @endcan

        @include('admin.seo.partials.filters')

        <x-admin.table :headers="$headers" :data="$seos" emptyMessage="No SEO records found.">
            @foreach ($seos as $seo)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">
                                {{ $seo->page }}
                            </span>
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                {{ $seo->slug }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex max-w-sm flex-col">
                            <p class="line-clamp-1 text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $seo->meta_title }}
                            </p>
                            <p class="line-clamp-1 text-xs text-slate-400 italic dark:text-slate-500">
                                {{ Str::limit($seo->meta_description, 80) }}
                            </p>
                        </div>
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :editRoute="route('admin.seo.edit', $seo)"
                                :canEdit="$canEdit"
                                :deleteRoute="route('admin.seo.destroy', $seo)"
                                :deleteId="$seo->id"
                                :canDelete="$canDelete"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
