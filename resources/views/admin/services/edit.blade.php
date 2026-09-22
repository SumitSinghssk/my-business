<x-admin :breadcrumb="[
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => 'Edit Service']
]">
    <x-admin.form-page
        :action="route('admin.services.update', $service->id)"
        method="PUT"
        upload
        title="Edit service"
        :description="$service->title"
        :back="route('admin.services.index')"
        submit="Save changes"
        submitting="Saving…"
    >
        <x-slot:actions>
            @if ($service->slug && $service->status === \App\Enums\CommonStatusEnum::ACTIVE)
                <x-admin.button
                    variant="secondary"
                    :href="route('services.show', $service->slug)"
                    target="_blank"
                    rel="noopener"
                    icon="external-link"
                >
                    View
                </x-admin.button>
            @endif

            @if ($service->seo)
                @can('admin.seo.edit')
                    <x-admin.button variant="secondary" :href="route('admin.seo.edit', $service->seo->id)" icon="globe">Edit SEO</x-admin.button>
                @endcan
            @else
                @can('admin.seo.create')
                    <x-admin.button
                        variant="secondary"
                        :href="route('admin.seo.create', ['model_type' => 'service', 'model_id' => $service->id])"
                        icon="globe"
                    >
                        Add SEO
                    </x-admin.button>
                @endcan
            @endif

            @can('admin.services.delete')
                <x-admin.delete-button :route="route('admin.services.destroy', $service)" title="Delete this service?" />
            @endcan
        </x-slot>

        @include('admin.services.partials.form')
    </x-admin.form-page>
</x-admin>
