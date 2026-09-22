<x-admin :breadcrumb="[
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => 'Create Service']
]">
    <x-admin.form-page
        :action="route('admin.services.store')"
        upload
        title="New service"
        description="Add a service with its own detail page."
        :back="route('admin.services.index')"
        submit="Create service"
        submitting="Creating service…"
    >
        @include('admin.services.partials.form')
    </x-admin.form-page>
</x-admin>
