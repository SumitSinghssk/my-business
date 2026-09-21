@php
    $actionConfig = [
        'login' => ['label' => 'Login', 'dot' => 'bg-green-500', 'bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-400', 'icon' => 'icons.login'],
        'logout' => ['label' => 'Logout', 'dot' => 'bg-slate-400', 'bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-700 dark:text-slate-300', 'icon' => 'icons.logout'],
        'failed_login' => ['label' => 'Failed Login', 'dot' => 'bg-red-500', 'bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-400', 'icon' => 'icons.close'],
        'viewed' => ['label' => 'Viewed', 'dot' => 'bg-blue-500', 'bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'icon' => 'icons.visibility'],
        'created' => ['label' => 'Created', 'dot' => 'bg-green-500', 'bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-400', 'icon' => 'icons.add'],
        'updated' => ['label' => 'Updated', 'dot' => 'bg-yellow-500', 'bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-700 dark:text-yellow-400', 'icon' => 'icons.update'],
        'deleted' => ['label' => 'Deleted', 'dot' => 'bg-red-500', 'bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-400', 'icon' => 'icons.delete'],
    ];

    $cfg = $actionConfig[$log->action] ?? [
        'label' => $log->action,
        'dot' => 'bg-slate-400',
        'bg' => 'bg-slate-100 dark:bg-slate-700',
        'text' => 'text-slate-700 dark:text-slate-300',
        'icon' => 'icons.default',
    ];

    $isSession = in_array($log->action, ['login', 'logout', 'failed_login']);
    $isCrud = in_array($log->action, ['created', 'updated', 'deleted']);

    $renderVal = function ($val): string {
        if ($val === null) {
            return '—';
        }
        if (is_array($val) || is_object($val)) {
            return json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        }
        return (string) $val;
    };

    $oldArr = is_array($log->old_values) ? $log->old_values : [];
    $newArr = is_array($log->new_values) ? $log->new_values : [];
    $diffKeys = array_unique(array_merge(array_keys($oldArr), array_keys($newArr)));

    $methodColors = [
        'GET' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        'POST' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        'PUT' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
        'PATCH' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
        'DELETE' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    ];
@endphp

<x-admin
    :breadcrumb="[
        ['label' => 'Activity Logs',  'url' => route('admin.activity-logs.index')],
        ['label' => 'Log #' . $log->id, 'url' => '#'],
    ]"
>
    <x-admin.card class="mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="{{ $cfg['bg'] }} {{ $cfg['text'] }} flex h-14 w-14 items-center justify-center rounded-2xl">
                    <x-dynamic-component :component="$cfg['icon']" class="h-7 w-7" />
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $cfg['label'] }}</h1>
                        @if ($log->is_suspicious)
                            <span
                                class="inline-flex items-center gap-1 rounded-sm bg-red-100 px-2 py-0.5 text-xs font-medium text-red-600 dark:bg-red-900/30 dark:text-red-400"
                            >
                                <x-icons.warning class="h-3 w-3" />
                                Suspicious Activity
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $log->description ?? ($log->page_title ?? ($log->model_name ?? "Log #{$log->id}")) }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Log ID</p>
                <p class="font-mono text-sm font-semibold text-slate-700 dark:text-slate-300">#{{ $log->id }}</p>
            </div>
        </div>
    </x-admin.card>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
        <div class="space-y-4 lg:col-span-8">
            <x-admin.card>
                <x-slot name="title">
                    <div class="flex items-center gap-2 dark:text-white">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <x-icons.account-circle class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                        </div>
                        <span>User</span>
                    </div>
                </x-slot>

                @php
                    $infoRow = function (string $label, ?string $value = null, ?string $slotHtml = null): string {
                        $content = $value !== null ? '<span class="ml-4 text-right text-sm font-medium text-slate-900 dark:text-white">' . e($value) . '</span>' : $slotHtml ?? '<span class="ml-4 text-right text-sm font-medium text-slate-900 dark:text-white">—</span>';
                        return '<div class="flex items-start justify-between border-b border-slate-100 py-2.5 last:border-0 dark:border-slate-700"><span class="shrink-0 text-sm text-slate-500 dark:text-slate-400">' . e($label) . '</span>' . $content . '</div>';
                    };
                @endphp

                {!! $infoRow('Name', $log->user?->name ?? 'Guest / Unknown') !!}
                {!! $infoRow('Email', $log->user?->email ?? ($log->email ?? '—')) !!}
                {!! $infoRow('User ID', $log->user_id ? '#' . $log->user_id : '—') !!}
            </x-admin.card>

            <x-admin.card>
                <x-slot name="title">
                    <div class="flex items-center gap-2 dark:text-white">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                            <x-icons.url class="h-4 w-4 text-purple-600 dark:text-purple-400" />
                        </div>
                        <span>Request</span>
                    </div>
                </x-slot>

                {!! $infoRow('Page Title', $log->page_title ?? '—') !!}

                <div class="flex items-start justify-between border-b border-slate-100 py-2.5 last:border-0 dark:border-slate-700">
                    <span class="shrink-0 text-sm text-slate-500 dark:text-slate-400">URL</span>
                    <span class="ml-4 max-w-xs font-mono text-xs break-all text-slate-900 dark:text-white">{{ $log->url ?? '—' }}</span>
                </div>

                <div class="flex items-start justify-between border-b border-slate-100 py-2.5 last:border-0 dark:border-slate-700">
                    <span class="shrink-0 text-sm text-slate-500 dark:text-slate-400">Method</span>

                    @if ($log->method)
                        <span class="{{ $methodColors[$log->method] ?? 'bg-slate-100 text-slate-600' }} rounded px-1.5 py-0.5 text-xs font-bold">
                            {{ $log->method }}
                        </span>
                    @else
                        <span class="ml-4 text-right text-sm font-medium text-slate-900 dark:text-white">—</span>
                    @endif
                </div>

                <div class="flex items-start justify-between border-b border-slate-100 py-2.5 last:border-0 dark:border-slate-700">
                    <span class="shrink-0 text-sm text-slate-500 dark:text-slate-400">IP Address</span>
                    <span class="font-mono text-sm font-medium text-slate-900 dark:text-white">{{ $log->ip_address ?? '—' }}</span>
                </div>

                {!! $infoRow('Description', $log->description ?? '—') !!}
            </x-admin.card>

            @if ($isCrud)
                <x-admin.card>
                    <x-slot name="title">
                        <div class="flex items-center gap-2 dark:text-white">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                                <x-icons.edit class="h-4 w-4 text-yellow-600 dark:text-yellow-400" />
                            </div>
                            <span>Changes</span>
                        </div>
                    </x-slot>

                    {!! $infoRow('Model', $log->model_type ? class_basename($log->model_type) : '—') !!}
                    {!! $infoRow('Model ID', $log->model_id ? '#' . $log->model_id : '—') !!}
                    {!! $infoRow('Name', $log->model_name ?? '—') !!}

                    @if ($log->old_values || $log->new_values)
                        <div class="mt-4">
                            @if (empty($diffKeys))
                                <p class="text-sm text-slate-400">No changes recorded.</p>
                            @else
                                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/60">
                                                <th class="px-4 py-2 text-left text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                                    Field
                                                </th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold tracking-wider text-red-400 uppercase">
                                                    Before
                                                </th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold tracking-wider text-green-500 uppercase">
                                                    After
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($diffKeys as $key)
                                                @php
                                                    $oldVal = $oldArr[$key] ?? null;
                                                    $newVal = $newArr[$key] ?? null;
                                                    $changed = json_encode($oldVal) !== json_encode($newVal);
                                                    $oldStr = $renderVal($oldVal);
                                                    $newStr = $renderVal($newVal);
                                                    $isMulti = str_contains($oldStr, "\n") || str_contains($newStr, "\n");
                                                @endphp

                                                <tr
                                                    class="{{ $changed ? '' : 'opacity-50' }} border-b border-slate-100 last:border-0 dark:border-slate-800"
                                                >
                                                    <td class="px-4 py-2.5 align-top font-medium text-slate-700 capitalize dark:text-slate-300">
                                                        {{ str_replace('_', ' ', $key) }}
                                                    </td>
                                                    <td class="px-4 py-2.5 align-top">
                                                        <div
                                                            class="max-w-70 rounded-lg bg-red-50 px-2 py-1.5 text-red-700 dark:bg-red-900/20 dark:text-red-400"
                                                        >
                                                            @if ($isMulti)
                                                                <pre
                                                                    class="max-h-40 overflow-auto font-mono text-[10px] leading-tight whitespace-pre-wrap"
                                                                >
                                                                    {{ $oldStr }}
                                                                </pre>
                                                            @else
                                                                <span class="text-xs">{{ $oldStr }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2.5 align-top">
                                                        <div
                                                            class="max-w-70 rounded-lg bg-green-50 px-2 py-1.5 text-green-700 dark:bg-green-900/20 dark:text-green-400"
                                                        >
                                                            @if ($isMulti)
                                                                <pre
                                                                    class="max-h-40 overflow-auto font-mono text-[10px] leading-tight whitespace-pre-wrap"
                                                                >
                                                                    {{ $newStr }}
                                                                </pre>
                                                            @else
                                                                <span class="text-xs">{{ $newStr }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="mt-3 text-sm text-slate-400">No change data recorded.</p>
                    @endif
                </x-admin.card>
            @endif

            @if ($isSession && ($log->login_at || $log->logout_at))
                <x-admin.card>
                    <x-slot name="title">
                        <div class="flex items-center gap-2 dark:text-white">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                                <x-icons.clock class="h-4 w-4 text-green-600 dark:text-green-400" />
                            </div>
                            <span>Session</span>
                        </div>
                    </x-slot>
                    {!! $infoRow('Login At', $log->login_at?->format('Y-m-d H:i:s') ?? '—') !!}
                    {!! $infoRow('Logout At', $log->logout_at?->format('Y-m-d H:i:s') ?? 'Still active') !!}
                    {!! $infoRow('Duration', $log->session_duration_formatted ?? '—') !!}
                    <div class="flex items-start justify-between border-b border-slate-100 py-2.5 last:border-0 dark:border-slate-700">
                        <span class="shrink-0 text-sm text-slate-500 dark:text-slate-400">Session ID</span>
                        <span class="ml-4 max-w-50 truncate font-mono text-xs text-slate-900 dark:text-white">
                            {{ $log->session_id ?? '—' }}
                        </span>
                    </div>
                </x-admin.card>
            @endif
        </div>

        <div class="space-y-4 lg:col-span-4">
            <x-admin.card>
                <x-slot name="title">
                    <div class="flex items-center gap-2 dark:text-white">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700">
                            <x-icons.clock class="h-4 w-4 text-slate-600 dark:text-white" />
                        </div>
                        <span>Timestamp</span>
                    </div>
                </x-slot>
                {!! $infoRow('Logged At', $log->created_at_formatted) !!}
                <div class="flex items-start justify-between border-b border-slate-100 py-2.5 last:border-0 dark:border-slate-700">
                    <span class="shrink-0 text-sm text-slate-500 dark:text-slate-400">Security</span>
                    @if ($log->is_suspicious)
                        <span class="inline-flex items-center gap-1 text-red-500">
                            <x-icons.warning class="h-3 w-3" />
                            Suspicious
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">
                            <x-icons.check-circle class="h-3.5 w-3.5" />
                            Normal
                        </span>
                    @endif
                </div>
            </x-admin.card>

            @if ($isSession)
                <x-admin.card>
                    <x-slot name="title">
                        <div class="flex items-center gap-2 dark:text-white">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                                <x-icons.desktop class="h-4 w-4 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <span>Device</span>
                        </div>
                    </x-slot>
                    {!! $infoRow('Device', $log->device ?? ($log->device_type ?? '—')) !!}
                    {!! $infoRow('Browser', collect([$log->browser, $log->browser_version])->filter()->implode(' ') ?:'—',) !!}
                    {!! $infoRow('Platform', $log->platform ?? '—') !!}
                    @if ($log->user_agent)
                        <div class="mt-2 border-t border-slate-100 pt-2 dark:border-slate-700">
                            <p class="text-xs break-all text-slate-400">{{ $log->user_agent }}</p>
                        </div>
                    @endif
                </x-admin.card>

                <x-admin.card>
                    <x-slot name="title">
                        <div class="flex items-center gap-2 dark:text-white">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900/30">
                                <x-icons.location class="h-4 w-4 text-rose-600 dark:text-rose-400" />
                            </div>
                            <span>Location</span>
                        </div>
                    </x-slot>
                    {!! $infoRow('City', $log->city ?? '—') !!}
                    {!! $infoRow('Region', $log->region ?? '—') !!}
                    {!! $infoRow('Country', $log->country ?? '—') !!}
                    {!! $infoRow('Timezone', $log->timezone ?? '—') !!}
                    {!! $infoRow('ISP', $log->isp ?? '—') !!}
                    @if ($log->latitude && $log->longitude)
                        <div class="mt-3">
                            <a
                                href="https://maps.google.com/?q={{ $log->latitude }},{{ $log->longitude }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-slate-200 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800"
                            >
                                <x-icons.link class="h-3.5 w-3.5" />
                                View on Google Maps
                            </a>
                        </div>
                    @endif
                </x-admin.card>
            @endif

            @if ($log->is_suspicious)
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800/40 dark:bg-red-900/20">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/40">
                            <x-icons.warning class="h-4 w-4 text-red-600 dark:text-red-400" />
                        </div>
                        <p class="text-sm font-semibold text-red-700 dark:text-red-300">Security Alert</p>
                    </div>
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                        This login originated from an IP address never used before by this user. It may indicate unauthorized access.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-admin>
