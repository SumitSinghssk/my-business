<x-admin.filter.bar :action="route('admin.projects.index')" search="Search by title, client or industry…">
    <x-admin.filter.select name="status" label="Status" icon="circle-dot" :options="\App\Enums\CommonStatusEnum::dotOptions()" />
</x-admin.filter.bar>
