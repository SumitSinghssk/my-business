@php
    $logFiles = $logFiles ?? [];
    $canView = auth()
        ->user()
        ->can('admin.log-settings.view');
    $canDelete = auth()
        ->user()
        ->can('admin.log-settings.delete');
    $canManage = $canView || $canDelete;

    $headers = ['Filename', 'Size', 'Last Modified'];
    if ($canManage) {
        $headers[] = 'Actions';
    }
@endphp

<div x-data="logSettings()" x-init="init()" class="space-y-6">
    <x-admin.card title="Application Logs" text="View and manage server-side log files.">
        <x-slot name="actions">
            <a href="{{ request()->fullUrl() }}">
                <x-admin.button variant="secondary">
                    <span class="flex items-center gap-1.5">
                        <x-icons.refresh class="h-3.5 w-3.5" />
                        Refresh
                    </span>
                </x-admin.button>
            </a>

            @can('admin.log-settings.delete')
                @if (count($logFiles) > 0)
                    <form
                        method="POST"
                        action="{{ route('admin.settings.logs.destroy-all') }}"
                        x-data
                        x-on:submit.prevent="
                                if (confirm('Delete ALL log files? This cannot be undone.')) $el.submit()
                            "
                    >
                        @csrf
                        @method('DELETE')

                        <x-admin.button variant="danger" class="flex items-center gap-1">
                            <x-icons.delete class="h-4 w-4" />
                            Delete All
                        </x-admin.button>
                    </form>
                @endif
            @endcan
        </x-slot>

        <x-admin.table :headers="$headers" :data="$logFiles" emptyMessage="No log files found.">
            @foreach ($logFiles as $log)
                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <x-icons.pages class="h-4 w-4 shrink-0 text-blue-400" />
                            <span class="font-mono text-xs text-slate-800 dark:text-slate-200">
                                {{ $log['filename'] }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                        {{ $log['size'] }}
                    </td>

                    <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                        {{ $log['modified'] }}
                    </td>

                    @if ($canManage)
                        <td class="px-6 py-4">
                            <x-admin.row-actions
                                size="sm"
                                :viewClick="'openViewer(\'' . $log['filename'] . '\')'"
                                :canView="auth()->user()->can('admin.log-settings.view')"
                                :deleteFormAction="route('admin.settings.logs.destroy')"
                                :deleteFormField="['name' => 'file', 'value' => $log['filename']]"
                                :deleteConfirmMessage="'Delete ' . $log['filename'] . '? This cannot be undone.'"
                                :canDelete="auth()->user()->can('admin.log-settings.delete')"
                            />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>

    <div
        x-show="viewerOpen"
        x-cloak
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm dark:bg-black/70"
        x-on:click="closeViewer()"
        x-on:keydown.escape.window="closeViewer()"
    >
        <div
            class="flex h-[85vh] w-full max-w-5xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10"
            x-on:click.stop
        >
            <div
                class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="flex items-center gap-3">
                    <x-icons.pages class="h-5 w-5 text-blue-500 dark:text-blue-400" />

                    <div>
                        <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white" x-text="viewerFilename"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-show="logData">
                            <span x-text="logData?.size"></span>
                            ·
                            <span x-text="rawLines.length"></span>
                            lines (last 500)
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button
                        type="button"
                        x-on:click="fetchLog()"
                        x-bind:disabled="viewerLoading"
                        title="Refresh"
                        class="cursor-pointer rounded-md p-1.5 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-800 disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                        <x-icons.refresh x-bind:class="viewerLoading ? 'animate-spin' : ''" class="h-4 w-4" />
                    </button>

                    <button
                        type="button"
                        x-on:click="closeViewer()"
                        title="Close"
                        class="cursor-pointer rounded-md p-1.5 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                        <x-icons.close class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div class="shrink-0 border-b border-gray-200 bg-gray-50 px-5 py-2 dark:border-gray-700 dark:bg-gray-800">
                <x-admin.form-input
                    type="text"
                    name="page"
                    id="page"
                    x-model="filter"
                    x-bind:disabled="viewerLoading"
                    placeholder="Filter log lines…"
                >
                    <x-slot:leftIcon>
                        <x-icons.search class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
            </div>

            <div class="flex-1 overflow-y-auto bg-white px-5 py-3 dark:bg-gray-900">
                <template x-if="viewerLoading">
                    <div class="flex h-full items-center justify-center">
                        <x-icons.loading class="h-6 w-6 animate-spin text-blue-500 dark:text-blue-400" />
                    </div>
                </template>

                <template x-if="!viewerLoading && viewerError">
                    <div class="mt-12 text-center">
                        <p class="text-sm text-red-500 dark:text-red-400" x-text="viewerError"></p>
                        <button
                            type="button"
                            x-on:click="fetchLog()"
                            class="mt-3 text-xs text-gray-500 underline hover:text-gray-800 dark:text-gray-400 dark:hover:text-white"
                        >
                            Try again
                        </button>
                    </div>
                </template>

                <template x-if="!viewerLoading && !viewerError && filteredEntries.length === 0">
                    <p class="mt-12 text-center text-sm text-gray-400 dark:text-gray-500">No matching log entries.</p>
                </template>

                <template x-if="!viewerLoading && !viewerError">
                    <div>
                        <template x-for="(entry, i) in filteredEntries" :key="i">
                            <div class="mb-1 font-mono text-xs">
                                <div
                                    x-bind:class="
                                        [
                                            levelClass(entry.head),
                                            entry.trace.length ? 'cursor-pointer select-none' : '',
                                        ]
                                    "
                                    class="flex items-start gap-2 rounded-md px-2 py-1"
                                    x-on:click="entry.trace.length && toggleTrace(i)"
                                >
                                    <template x-if="entry.trace.length">
                                        <span class="mt-0.5 shrink-0 text-gray-400 dark:text-gray-500">
                                            <x-icons.chevron-down
                                                class="h-3 w-3 transition-transform duration-200"
                                                x-bind:class="{ 'rotate-180': !collapsed.includes(i) }"
                                            />
                                        </span>
                                    </template>
                                    <span class="leading-relaxed break-all whitespace-pre-wrap" x-text="entry.head"></span>
                                </div>

                                <template x-if="entry.trace.length && ! collapsed.includes(i)">
                                    <div class="mt-0.5 ml-5 rounded-md bg-gray-50 px-3 py-2 dark:bg-gray-800">
                                        <template x-for="(t, j) in entry.trace" :key="j">
                                            <p
                                                class="leading-relaxed break-all whitespace-pre-wrap text-gray-500 dark:text-gray-400"
                                                x-text="t"
                                            ></p>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script defer>
        function logSettings() {
            return {
                viewerOpen: false,
                viewerFilename: '',
                viewerLoading: false,
                viewerError: null,
                logData: null,
                filter: '',
                collapsed: [],

                get rawLines() {
                    return this.logData ? this.logData.content.split('\n').filter(Boolean) : [];
                },

                get grouped() {
                    const groups = [];
                    this.rawLines.forEach((line) => {
                        const isTrace = line.startsWith('#') || line.startsWith('Stack trace:') || line.startsWith('"');

                        if (isTrace && groups.length > 0) {
                            groups[groups.length - 1].trace.push(line);
                        } else {
                            groups.push({ head: line, trace: [] });
                        }
                    });
                    return groups;
                },

                get filteredEntries() {
                    if (!this.filter) return this.grouped;
                    const q = this.filter.toLowerCase();
                    return this.grouped.filter((g) => g.head.toLowerCase().includes(q));
                },

                init() {},

                openViewer(filename) {
                    this.viewerFilename = filename;
                    this.viewerOpen = true;
                    this.filter = '';
                    this.collapsed = [];
                    this.fetchLog();
                },

                closeViewer() {
                    this.viewerOpen = false;
                    this.viewerFilename = '';
                    this.logData = null;
                    this.viewerError = null;
                },

                fetchLog() {
                    this.viewerLoading = true;
                    this.viewerError = null;

                    axios
                        .get(`{{ route('admin.settings.logs.show') }}`, {
                            params: {
                                file: this.viewerFilename,
                            },
                        })
                        .then((res) => {
                            this.logData = res.data;
                        })
                        .catch((err) => {
                            this.viewerError = err.response?.data?.error || err.message || 'Server error';
                        })
                        .finally(() => {
                            this.viewerLoading = false;
                        });
                },

                toggleTrace(i) {
                    const idx = this.collapsed.indexOf(i);
                    if (idx === -1) {
                        this.collapsed.push(i);
                    } else {
                        this.collapsed.splice(idx, 1);
                    }
                },

                levelClass(line) {
                    const LEVELS = {
                        EMERGENCY: 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                        ALERT: 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                        CRITICAL: 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                        ERROR: 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                        WARNING: 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                        NOTICE: 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300',
                        INFO: 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-300',
                        DEBUG: 'bg-gray-100 text-gray-600 dark:bg-gray-700/60 dark:text-gray-300',
                    };
                    const upper = line.toUpperCase();
                    for (const [level, cls] of Object.entries(LEVELS)) {
                        if (upper.includes('.' + level) || upper.includes('[' + level + ']')) return cls;
                    }
                    return 'text-gray-700 dark:text-gray-300';
                },
            };
        }
    </script>
@endpush
