<x-admin :breadcrumb="[
    ['label' => 'SEO', 'url' => route('admin.seo.index')],
    ['label' => 'Create SEO']
]">
    <x-admin.card title="Create SEO" text="Add SEO metadata, scripts, and structured data">
        <form
            action="{{ route('admin.seo.store') }}"
            enctype="multipart/form-data"
            method="POST"
            x-data="{ submitting: false }"
            x-on:submit="submitting = true"
        >
            @csrf

            @include('admin.seo.partials.form')

            <div class="flex justify-end">
                <x-admin.button>
                    <span x-text="submitting ? 'Creating SEO...' : 'Create SEO'">Create SEO</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin>
