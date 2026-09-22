<x-admin :breadcrumb="[
    ['label' => 'Work', 'url' => route('admin.projects.index')],
    ['label' => 'Add Project']
]">
    <x-admin.form-page
        :action="route('admin.projects.store')"
        upload
        title="New project"
        description="Add a case study with its own project page."
        :back="route('admin.projects.index')"
        submit="Add project"
        submitting="Adding project…"
    >
        @include('admin.projects.partials.form')
    </x-admin.form-page>
</x-admin>
