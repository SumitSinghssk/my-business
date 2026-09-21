@php
    $canClear = auth()
        ->user()
        ->can('admin.activity-logs.clear');
    $canView = auth()
        ->user()
        ->can('admin.activity-logs.view');

    $actionIconName = function (string $action): string {
        $icons = [
            'login' => 'icons.login',
            'logout' => 'icons.logout',
            'failed_login' => 'icons.close',
            'viewed' => 'icons.visibility',
            'created' => 'icons.add',
            'updated' => 'icons.update',
            'deleted' => 'icons.delete',
        ];

        return $icons[$action] ?? 'icons.default';
    };

    $actionBadgeConfig = function (string $action) {
        $config = [
            'login' => ['label' => 'Login', 'color' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            'logout' => ['label' => 'Logout', 'color' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'],
            'failed_login' => ['label' => 'Failed Login', 'color' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
            'viewed' => ['label' => 'Viewed', 'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
            'created' => ['label' => 'Created', 'color' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            'updated' => ['label' => 'Updated', 'color' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'],
            'deleted' => ['label' => 'Deleted', 'color' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
        ];
        return $config[$action] ?? ['label' => ucfirst($action), 'color' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400'];
    };

    $headers = ['User', 'Action', 'Detail', 'IP / Location', 'Device', 'Time'];
    if ($canView) {
        $headers[] = 'Actions';
    }
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Activity Logs', 'url' => '#'],
]">
    <x-admin.card title="Activity Logs" text="Track all admin actions, logins, and system events">
        @if ($canClear)
            <x-slot name="actions">
                <form
                    method="POST"
                    action="{{ route('admin.activity-logs.clear') }}"
                    x-data
                    x-on:submit.prevent="if (confirm('Permanently delete ALL activity logs? This cannot be undone.')) $el.submit();"
                >
                    @csrf
                    @method('DELETE')
                    <x-admin.button variant="danger" type="submit">Clear All Logs</x-admin.button>
                </form>
            </x-slot>
        @endif

        <x-admin.table :headers="$headers" :data="$logs" emptyMessage="No activity logs found.">
            @foreach ($logs as $log)
                @php
                    $isSession = in_array($log->action, ['login', 'logout', 'failed_login']);
                @endphp

                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">
                                {{ $log->user?->name ?? 'Guest' }}
                            </span>
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                {{ $log->user?->email ?? ($log->email ?? '—') }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            @php
                                $cfg = $actionBadgeConfig($log->action);
                                $iconName = $actionIconName($log->action);
                            @endphp

                            <div>
                                <span class="{{ $cfg['color'] }} inline-flex items-center gap-1 rounded-sm px-2 py-0.5 text-xs font-medium">
                                    <x-dynamic-component :component="$iconName" class="h-3 w-3" />
                                    {{ $cfg['label'] }}
                                </span>
                            </div>

                            @if ($log->is_suspicious)
                                <span class="flex items-center gap-1 text-[10px] font-bold text-red-500 uppercase">Suspicious</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex max-w-50 flex-col">
                            <p class="truncate text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $log->page_title ?? ($log->model_name ?? ($log->description ?? '—')) }}
                            </p>
                            @if ($log->model_name && $log->page_title)
                                <p class="text-[10px] tracking-tight text-slate-400 uppercase">{{ $log->model_name }}</p>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-mono text-xs text-slate-600 dark:text-slate-400">{{ $log->ip_address ?? '—' }}</span>
                            @if ($isSession && $log->city)
                                <span class="flex items-center gap-1 text-[10px] text-slate-400">
                                    <x-icons.location class="h-3 w-3" />
                                    {{ collect([$log->city, $log->country_code])->filter()->implode(', ') }}
                                </span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @if ($isSession)
                            <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
                                @if ($log->device_type === 'mobile')
                                    <x-icons.mobile class="h-4 w-4 text-slate-400" />
                                @elseif ($log->device_type === 'tablet')
                                    <x-icons.tablet class="h-4 w-4 text-slate-400" />
                                @else
                                    <x-icons.desktop class="h-4 w-4 text-slate-400" />
                                @endif
                                <span class="max-w-25 truncate">{{ $log->browser ?? '—' }}</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            {{ $log->created_at_formatted }}
                        </span>
                    </td>

                    @if ($canView)
                        <td class="px-6 py-4">
                            <x-admin.row-actions size="sm" :viewRoute="route('admin.activity-logs.show', $log->id)" :canView="$canView" />
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
