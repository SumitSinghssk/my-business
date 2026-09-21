<x-admin :breadcrumb="[
    ['label' => 'Work', 'url' => route('admin.projects.index')],
    ['label' => 'Edit Project']
]">
    <x-admin.card title="Edit Project" text="Update the case study, its image and SEO">
        <x-slot name="actions">
            @if ($project->slug && $project->status === \App\Enums\CommonStatusEnum::ACTIVE)
                <a href="{{ route('work.show', $project->slug) }}" target="_blank" rel="noopener">
                    <x-admin.button type="button" variant="secondary">View Page</x-admin.button>
                </a>
            @endif

            @if ($project->seo)
                @can('admin.seo.edit')
                    <a href="{{ route('admin.seo.edit', $project->seo->id) }}">
                        <x-admin.button type="button" variant="secondary">Update SEO</x-admin.button>
                    </a>
                @endcan
            @else
                @can('admin.seo.create')
                    <a href="{{ route('admin.seo.create', ['model_type' => 'project', 'model_id' => $project->id]) }}">
                        <x-admin.button type="button" variant="secondary">Add SEO</x-admin.button>
                    </a>
                @endcan
            @endif

            @can('admin.projects.delete')
                <x-admin.delete-button :route="route('admin.projects.destroy', $project)" />
            @endcan
        </x-slot>

        <form
            action="{{ route('admin.projects.update', $project->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.projects.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Updating Project...' : 'Update Project'">Update Project</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
