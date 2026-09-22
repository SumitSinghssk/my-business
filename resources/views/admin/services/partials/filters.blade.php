<x-admin.filter.bar :action="route('admin.services.index')" search="Search services by title or excerpt…">
    <x-admin.filter.select name="status" label="Status" icon="circle-dot" :options="\App\Enums\CommonStatusEnum::dotOptions()" />
</x-admin.filter.bar>
