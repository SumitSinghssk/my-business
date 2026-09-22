@php
    $settings = $settings ?? [];
    $scriptSettings = $scriptSettings ?? [];

    // Section nav. Ids are the ?tab= values other pages link to (e.g. ?tab=sitemap) — keep them stable.
    $tabs = [];
    if (
        auth()
            ->user()
            ->can('admin.settings.basic-details.view')
    ) {
        $tabs[] = [
            'id' => 'basic',
            'label' => 'Business profile',
            'hint' => 'Name, logos, contact & social',
            'icon' => 'building',
            // In-page jumps to the cards inside the Business profile form.
            'sections' => [
                ['id' => 'general', 'label' => 'General', 'icon' => 'settings'],
                ['id' => 'branding', 'label' => 'Branding', 'icon' => 'palette'],
                ['id' => 'addresses', 'label' => 'Addresses', 'icon' => 'map-pin'],
                ['id' => 'contact', 'label' => 'Contact', 'icon' => 'phone'],
                ['id' => 'social', 'label' => 'Social', 'icon' => 'share'],
            ],
        ];
    }
    if (
        auth()
            ->user()
            ->can('admin.settings.scripts.view')
    ) {
        $tabs[] = ['id' => 'scripts', 'label' => 'Scripts & CSS', 'hint' => 'Tracking tags and custom styles', 'icon' => 'code'];
    }
    if (
        auth()
            ->user()
            ->can('admin.settings.sitemap.view')
    ) {
        $tabs[] = ['id' => 'sitemap', 'label' => 'Sitemap', 'hint' => 'Generate, download or upload', 'icon' => 'sitemap'];
    }
    if (
        auth()
            ->user()
            ->can('admin.log-settings.view')
    ) {
        $tabs[] = ['id' => 'logs', 'label' => 'Application logs', 'hint' => 'Inspect server log files', 'icon' => 'scroll'];
    }
    if (
        auth()
            ->user()
            ->canany('admin.settings.robots.view')
    ) {
        $tabs[] = ['id' => 'robots', 'label' => 'Robots.txt', 'hint' => 'Crawler rules for search engines', 'icon' => 'bot'];
    }
@endphp

<x-admin :breadcrumb="[['label' => 'Settings', 'url' => '#']]">
    <x-admin.page-header title="Settings" description="Manage your business profile, site integrations and system tools." icon="settings">
        @canany(['admin.settings.clear-cache', 'admin.settings.download-db'])
            <x-slot:actions>
                @can('admin.settings.download-db')
                    <div x-data="{ loading: false }">
                        <x-admin.button
                            type="button"
                            variant="secondary"
                            x-on:click="handleDbDownload($el, () => loading = true, () => loading = false)"
                            x-bind:disabled="loading"
                            x-cloak
                        >
                            <span x-show="!loading" class="flex items-center gap-1.5">
                                <x-admin.icon name="download" class="h-4 w-4" />
                                Download DB
                            </span>

                            <span x-show="loading" class="flex items-center gap-1.5">
                                <x-admin.icon name="refresh" class="h-4 w-4 animate-spin" />
                                Preparing backup…
                            </span>
                        </x-admin.button>
                    </div>
                @endcan

                @can('admin.settings.clear-cache')
                    <form method="POST" action="{{ route('admin.settings.clear-cache') }}">
                        @csrf
                        <x-admin.button type="submit" variant="danger-outline" icon="refresh">Clear cache</x-admin.button>
                    </form>
                @endcan
            </x-slot>
        @endcanany
    </x-admin.page-header>

    <div x-data="settingsTabs()" class="grid grid-cols-1 gap-6 lg:grid-cols-[15rem_minmax(0,1fr)] lg:items-start">
        {{-- Section nav: horizontal scrolling row on mobile, vertical list on desktop. --}}
        <nav
            aria-label="Settings sections"
            class="-mx-4 border-b border-slate-200 px-4 sm:mx-0 sm:px-0 lg:sticky lg:top-20 lg:border-b-0 dark:border-slate-800"
        >
            <ul class="scrollbar-hide -mb-px flex gap-1 overflow-x-auto lg:mb-0 lg:flex-col lg:overflow-visible">
                @foreach ($tabs as $tab)
                    <li class="shrink-0">
                        <button
                            type="button"
                            x-on:click="setTab(@js($tab['id']))"
                            x-bind:aria-current="activeTab === @js($tab['id']) ? 'page' : null"
                            x-bind:class="
                                activeTab === @js($tab['id'])
                                    ? 'border-blue-600 text-blue-700 lg:border-slate-200/80 lg:bg-white lg:shadow-xs dark:border-blue-400 dark:text-blue-300 lg:dark:border-slate-800 lg:dark:bg-slate-900'
                                    : 'border-transparent text-slate-600 hover:text-slate-900 lg:hover:bg-white/70 dark:text-slate-400 dark:hover:text-white lg:dark:hover:bg-slate-900/60'
                            "
                            class="group flex w-full cursor-pointer items-center gap-2.5 border-b-2 px-3 py-3 text-left text-sm font-medium whitespace-nowrap transition focus:outline-none focus-visible:ring-3 focus-visible:ring-blue-500/30 lg:rounded-lg lg:border lg:py-2.5"
                        >
                            <span
                                x-bind:class="
                                    activeTab === @js($tab['id'])
                                        ? 'lg:border-blue-100 lg:bg-blue-50 text-blue-600 lg:dark:border-blue-500/20 lg:dark:bg-blue-500/10 dark:text-blue-300'
                                        : 'text-slate-400 group-hover:text-slate-600 lg:border-slate-200 lg:bg-white dark:group-hover:text-slate-200 lg:dark:border-slate-800 lg:dark:bg-slate-900'
                                "
                                class="flex shrink-0 items-center justify-center transition lg:h-8 lg:w-8 lg:rounded-lg lg:border"
                            >
                                <x-admin.icon :name="$tab['icon']" class="h-4 w-4" />
                            </span>
                            <span class="min-w-0">
                                <span class="block">{{ $tab['label'] }}</span>
                                <span class="hidden truncate text-xs font-normal text-slate-500 lg:block dark:text-slate-400">
                                    {{ $tab['hint'] }}
                                </span>
                            </span>
                        </button>

                        @if (! empty($tab['sections']))
                            <ul
                                x-show="activeTab === @js($tab['id'])"
                                x-cloak
                                class="mt-1 mb-2 ml-7 hidden space-y-0.5 border-l border-slate-200 pl-3 lg:block dark:border-slate-800"
                            >
                                @foreach ($tab['sections'] as $section)
                                    <li>
                                        <a
                                            href="#settings-{{ $section['id'] }}"
                                            class="flex items-center gap-2 rounded-md px-2 py-1.5 text-xs font-medium text-slate-500 transition hover:bg-white hover:text-slate-900 focus:outline-none focus-visible:ring-3 focus-visible:ring-blue-500/30 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white"
                                        >
                                            <x-admin.icon :name="$section['icon']" class="h-3.5 w-3.5" />
                                            {{ $section['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="min-w-0">
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
    </div>

    @push('scripts')
        <script>
            function handleDbDownload(el, onStart, onEnd) {
                const url = '{{ route('admin.settings.download-db') }}';

                onStart();

                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                })
                    .then((response) => {
                        if (!response.ok) {
                            return response
                                .json()
                                .catch(() => ({}))
                                .then((data) => {
                                    throw new Error(data.message || 'Server error');
                                });
                        }

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
                    tabs: @js(collect($tabs)->map(fn ($t) => ['id' => $t['id'], 'label' => $t['label']])->values()),

                    activeTab: null,

                    init() {
                        const params = new URLSearchParams(window.location.search);
                        const tabFromUrl = params.get('tab');

                        const validTab = this.tabs.find((t) => t.id === tabFromUrl);

                        this.activeTab = validTab ? tabFromUrl : this.tabs.length ? this.tabs[0].id : null;

                        // Mobile: bring the active tab into view in the horizontally scrolling row.
                        this.$nextTick(() => {
                            const current = this.$root.querySelector('nav [aria-current=page]');
                            const row = current?.closest('ul');
                            if (current && row && row.scrollWidth > row.clientWidth) {
                                row.scrollLeft = current.offsetLeft - (row.clientWidth - current.offsetWidth) / 2;
                            }
                        });
                    },

                    setTab(tabId) {
                        this.activeTab = tabId;

                        const url = new URL(window.location);
                        url.searchParams.set('tab', tabId);
                        window.history.replaceState({}, '', url);
                    },
                };
            }
        </script>
    @endpush
</x-admin>
