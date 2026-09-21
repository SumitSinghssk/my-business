<x-admin.card title="Robots.txt" text="Crawling rules served at /robots.txt. The Sitemap line is added automatically with your live domain.">
    <form method="POST" action="{{ route('admin.settings.robots.update') }}">
        @csrf

        <div class="space-y-4">
            <x-admin.form-textarea name="content" id="content" rows="12" placeholder="User-agent: *&#10;Disallow:">
                {{ old('content', $robotsContent ?? '') }}
            </x-admin.form-textarea>

            @can('admin.settings.robots.update')
                <div class="flex items-center gap-2">
                    <x-admin.button type="submit">Save Robots.txt</x-admin.button>
                </div>
            @endcan
        </div>
    </form>
</x-admin.card>
