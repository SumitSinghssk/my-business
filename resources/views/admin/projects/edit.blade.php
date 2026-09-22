<x-admin :breadcrumb="[
    ['label' => 'Work', 'url' => route('admin.projects.index')],
    ['label' => 'Edit Project']
]">
    <x-admin.form-page
        :action="route('admin.projects.update', $project->id)"
        method="PUT"
        upload
        title="Edit project"
        :description="$project->title"
        :back="route('admin.projects.index')"
        submit="Save changes"
        submitting="Saving…"
    >
        <x-slot:actions>
            @if ($project->slug && $project->status === \App\Enums\CommonStatusEnum::ACTIVE)
                <x-admin.button variant="secondary" :href="route('work.show', $project->slug)" target="_blank" rel="noopener" icon="external-link">
                    View
                </x-admin.button>
            @endif

            @if ($project->seo)
                @can('admin.seo.edit')
                    <x-admin.button variant="secondary" :href="route('admin.seo.edit', $project->seo->id)" icon="globe">Edit SEO</x-admin.button>
                @endcan
            @else
                @can('admin.seo.create')
                    <x-admin.button
                        variant="secondary"
                        :href="route('admin.seo.create', ['model_type' => 'project', 'model_id' => $project->id])"
                        icon="globe"
                    >
                        Add SEO
                    </x-admin.button>
                @endcan
            @endif

            @can('admin.projects.delete')
                <x-admin.delete-button :route="route('admin.projects.destroy', $project)" title="Delete this project?" />
            @endcan
        </x-slot>

        @include('admin.projects.partials.form')
    </x-admin.form-page>
</x-admin>
