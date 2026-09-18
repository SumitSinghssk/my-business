@php
    $settings = $settings ?? [];
    $scriptSettings = $scriptSettings ?? [];
@endphp

<x-admin :breadcrumb="[['label' => 'Settings', 'url' => '#']]">
    <div>
        <x-admin.card title="System Settings" text="Manage your application configuration and integrations">
            @canany(['admin.settings.clear-cache', 'admin.settings.download-db'])
                <x-slot name="actions">
                    @can('admin.settings.download-db')
                        <div x-data="{ loading: false }">
                            <x-admin.button
                                type="button"
                                variant="secondary"
                                x-on:click="handleDbDownload($el, () => loading = true, () => loading = false)"
                                x-bind:disabled="loading"
                                x-cloak
                            >
                                <span x-show="!loading">Download DB</span>

                                <span x-show="loading" class="flex items-center gap-2">
                                    <x-icons.loading class="h-4 w-4 animate-spin" />
                                    Preparing Backup…
                                </span>
                            </x-admin.button>
                        </div>
                    @endcan

                    @can('admin.settings.clear-cache')
                        <a href="{{ route('admin.settings.clear-cache') }}">
                            <x-admin.button variant="danger">Clear Cache</x-admin.button>
                        </a>
                    @endcan
                </x-slot>
            @endcanany

            <div x-data="settingsTabs()" class="space-y-6">
                <div class="sticky top-24 z-20 border-b border-slate-200 bg-white/80 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/80">
                    <div>
                        <nav class="scrollbar-hide -mb-px flex items-center gap-2 overflow-x-auto whitespace-nowrap" aria-label="Tabs">
                            <template x-for="tab in tabs" :key="tab.id">
                                <button
                                    type="button"
                                    x-on:click="setTab(tab.id)"
                                    :class="activeTab === tab.id
                                        ? 'text-blue-600 dark:text-blue-400'
                                        : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                                    class="group relative flex cursor-pointer items-center px-4 py-4 text-sm font-bold transition-all duration-200 focus:outline-none"
                                >
                                    <span x-text="tab.label" class="tracking-tight"></span>

                                    <div
                                        x-show="activeTab === tab.id"
                                        x-transition:enter="transition duration-300 ease-out"
                                        x-transition:enter-start="scale-x-0 opacity-0"
                                        x-transition:enter-end="scale-x-100 opacity-100"
                                        class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 shadow-[0_-2px_10px_rgba(37,99,235,0.4)] dark:bg-blue-500"
                                    ></div>
                                </button>
                            </template>
                        </nav>
                    </div>
                </div>

                @can('admin.settings.basic-details.view')
                    <div x-show="activeTab === 'basic'" x-cloak>
                        @include('admin.settings.partials.basic-info')
                    </div>
                @endcan

                @can('admin.settings.scripts.view')
                    <div x-show="activeTab === 'scripts'" x-cloak>
                        @include('admin.settings.partials.scripts', ['settings' => $scriptSettings])
                    </div>
                @endcan

                @canany(['admin.settings.sitemap.view', 'admin.settings.sitemap.update'])
                    <div x-show="activeTab === 'sitemap'" x-cloak>
                        @include(
                            'admin.settings.partials.sitemap',
                            [
                                'sitemapInfo' => $sitemapInfo,
                                'sitemapExists' => $sitemapExists,
                            ]
                        )
                    </div>
                @endcanany

                @can('admin.log-settings.view')
                    <div x-show="activeTab === 'logs'" x-cloak>
                        @include('admin.settings.partials.logs')
                    </div>
                @endcan

                @canany('admin.settings.robots.view')
                    <div x-show="activeTab === 'robots'" x-cloak>
                        @include('admin.settings.partials.robots')
                    </div>
                @endcanany
            </div>
        </x-admin.card>
    </div>

    @push('scripts')
        <script>
            function handleDbDownload(el, onStart, onEnd) {
                const url = '{{ route('admin.settings.download-db') }}';

                onStart();

                fetch(url)
                    .then((response) => {
                        if (!response.ok) throw new Error('Server error');

                        const cd = response.headers.get('Content-Disposition') ?? '';
                        const match = cd.match(/filename[^;=\n]*=['\"](.*?)['\"]|filename=([^;\n]*)/i);
                        const filename = match ? match[1] || match[2] : 'backup.sql';

                        return response.blob().then((blob) => ({ blob, filename }));
                    })
                    .then(({ blob, filename }) => {
                        const a = document.createElement('a');
                        a.href = URL.createObjectURL(blob);
                        a.download = filename;

                        document.body.appendChild(a);
                        a.click();
                        a.remove();

                        URL.revokeObjectURL(a.href);
                        onEnd();
                    })
                    .catch((err) => {
                        onEnd();
                        alert('Download failed: ' + err.message);
                    });
            }
        </script>
    @endpush

    @push('scripts')
        <script>
            function settingsTabs() {
                return {
                    tabs: [
                        @can('admin.settings.basic-details.view')
                        { id: 'basic', label: 'Basic Settings' },
                        @endcan

                        @can('admin.settings.scripts.view')
                        { id: 'scripts', label: 'Scripts & CSS' },
                        @endcan

                        @can('admin.settings.sitemap.view')
                        { id: 'sitemap', label: 'Sitemap' },
                        @endcan

                        @can('admin.log-settings.view')
                        { id: 'logs', label: 'Application Logs' },
                        @endcan

                        @canany('admin.settings.robots.view')
                        { id: 'robots', label: 'Robots.txt' },
                        @endcanany
                    ],

                    activeTab: null,

                    init() {
                        const params = new URLSearchParams(window.location.search);
                        const tabFromUrl = params.get('tab');

                        const validTab = this.tabs.find(t => t.id === tabFromUrl);

                        this.activeTab = validTab ? tabFromUrl : (this.tabs.length ? this.tabs[0].id : null);
                    },

                    setTab(tabId) {
                        this.activeTab = tabId;

                        const url = new URL(window.location);
                        url.searchParams.set('tab', tabId);
                        window.history.replaceState({}, '', url);
                    }
                }
            }
        </script>
    @endpush
</x-admin>
