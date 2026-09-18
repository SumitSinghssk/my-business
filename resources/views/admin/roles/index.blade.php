@php
    $roles = $roles ?? collect();
    $permissions = $permissions ?? collect();
    $groupedPermissions = $groupedPermissions ?? collect();
@endphp

<x-admin :breadcrumb="[['label' => 'Roles & Permissions', 'url' => '#']]">
    <div class="space-y-6">
        <x-admin.card title="Roles & Permissions" text="Manage roles, permissions, and assign permissions to roles">
            <div x-data="rolesPermissionsTabs()" class="space-y-6">
                <div class="sticky top-24 z-20 border-b border-slate-200 bg-white/80 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/80">
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

                @include('admin.roles.partials.roles')
                @include('admin.roles.partials.permissions')
            </div>
        </x-admin.card>
    </div>

    <script>
        function rolesPermissionsTabs() {
            return {
                tabs: [
                    @can('admin.roles.view')
                    { id: 'roles', label: 'Roles' },
                    @endcan
                    @can('admin.permissions.view')
                    { id: 'permissions', label: 'Permissions' },
                    @endcan
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
            };
        }

        function permissionToggle(url, permissionName, initialState) {
            return {
                checked: initialState,
                loading: false,

                async toggle() {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ permission: permissionName }),
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.checked = data.action === 'granted';
                        } else {
                            console.error('Toggle failed', data);
                        }
                    } catch (err) {
                        console.error('Toggle error', err);
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
</x-admin>
