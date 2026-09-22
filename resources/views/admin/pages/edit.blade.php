<x-admin :breadcrumb="[
    ['label' => 'Pages', 'url' => route('admin.pages.index')],
    ['label' => 'Edit Page']
]">
    <x-admin.form-page
        :action="route('admin.pages.update', $page->id)"
        method="PUT"
        upload
        title="Edit page"
        :description="$page->title"
        :back="route('admin.pages.index')"
        submit="Save changes"
        submitting="Saving…"
    >
        <x-slot:actions>
            @if ($page->slug && $page->status === \App\Enums\CommonStatusEnum::ACTIVE && (! $page->published_at || $page->published_at->isPast()))
                <x-admin.button variant="secondary" :href="route('page.show', $page->slug)" target="_blank" rel="noopener" icon="external-link">
                    View
                </x-admin.button>
            @endif

            @if ($page->seo)
                @can('admin.seo.edit')
                    <x-admin.button variant="secondary" :href="route('admin.seo.edit', $page->seo->id)" icon="globe">Edit SEO</x-admin.button>
                @endcan
            @else
                @can('admin.seo.create')
                    <x-admin.button
                        variant="secondary"
                        :href="route('admin.seo.create', ['model_type' => 'page', 'model_id' => $page->id])"
                        icon="globe"
                    >
                        Add SEO
                    </x-admin.button>
                @endcan
            @endif

            @can('admin.pages.delete')
                <x-admin.delete-button :route="route('admin.pages.destroy', $page)" title="Delete this page?" />
            @endcan
        </x-slot>

        @include('admin.pages.partials.form')
    </x-admin.form-page>
</x-admin>
