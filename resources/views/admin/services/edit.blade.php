<x-admin :breadcrumb="[
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => 'Edit Service']
]">
    <x-admin.card title="Edit Service" text="Update the service, its detail page content, image and SEO">
        <x-slot name="actions">
            @if ($service->slug && $service->status === \App\Enums\CommonStatusEnum::ACTIVE)
                <a href="{{ route('services.show', $service->slug) }}" target="_blank" rel="noopener">
                    <x-admin.button type="button" variant="secondary">View Page</x-admin.button>
                </a>
            @endif

            @if ($service->seo)
                @can('admin.seo.edit')
                    <a href="{{ route('admin.seo.edit', $service->seo->id) }}">
                        <x-admin.button type="button" variant="secondary">Update SEO</x-admin.button>
                    </a>
                @endcan
            @else
                @can('admin.seo.create')
                    <a href="{{ route('admin.seo.create', ['model_type' => 'service', 'model_id' => $service->id]) }}">
                        <x-admin.button type="button" variant="secondary">Add SEO</x-admin.button>
                    </a>
                @endcan
            @endif

            @can('admin.services.delete')
                <x-admin.delete-button :route="route('admin.services.destroy', $service)" />
            @endcan
        </x-slot>

        <form
            action="{{ route('admin.services.update', $service->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf
            @method('PUT')

            @include('admin.services.partials.form')

            <div
                class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-end border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
            >
                <x-admin.button>
                    <span x-text="submitting ? 'Updating Service...' : 'Update Service'">Update Service</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
