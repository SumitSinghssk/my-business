@php
    $headers = ['Notification', 'Status', 'Actions'];
@endphp

<x-admin :breadcrumb="[
    ['label' => 'Notifications', 'url' => route('admin.notifications.list')]
]">
    <x-admin.card title="Notifications" text="Stay updated with the latest activities and alerts" x-data>
        @php
            $hasUnread = $notifications->whereNull('seen_at')->count() > 0;
        @endphp

        @if ($hasUnread)
            <x-slot name="actions">
                <div x-show="$store.notif.unread > 0" x-cloak x-transition>
                    <x-admin.button variant="primary" type="button" x-on:click="$store.notif.markAllRead(); $dispatch('mark-all-read')">
                        <span class="flex items-center gap-1.5">Mark All as Read</span>
                    </x-admin.button>
                </div>
            </x-slot>
        @endif

        <x-admin.table :headers="$headers" :data="$notifications" emptyMessage="No notifications found.">
            @foreach ($notifications as $notification)
                <tr
                    x-data="{
                        isSeen: {{ $notification->isSeen() ? 'true' : 'false' }},
                        data: @js($notification->data),
                    }"
                    x-on:mark-all-read.window="isSeen = true"
                    class="group border-b border-slate-100 transition-colors hover:bg-slate-50/50 dark:border-slate-800/50 dark:hover:bg-slate-800/30"
                    :class="!isSeen ? 'bg-blue-50/30 dark:bg-blue-900/10' : ''"
                >
                    <td class="min-w-96 px-6 py-4">
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-bold transition-colors"
                                :class="isSeen ? 'text-slate-500 dark:text-slate-400' : 'text-slate-900 dark:text-white'"
                            >
                                {{ $notification->title }}
                            </span>

                            <span
                                class="text-xs font-medium transition-colors"
                                :class="isSeen ? 'text-slate-400 dark:text-slate-500' : 'text-slate-600 dark:text-slate-300'"
                            >
                                {{ $notification->message }}
                            </span>

                            @if ($notification->data)
                                <div class="mt-1 space-y-0.5 text-[11px] text-slate-400 dark:text-slate-500">
                                    @foreach ($notification->data as $key => $value)
                                        <div>
                                            <span class="font-semibold capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                            <span>{{ is_array($value) ? json_encode($value) : $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <template x-if="!isSeen">
                            <span
                                class="inline-flex items-center rounded-sm bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-400"
                            >
                                New
                            </span>
                        </template>

                        <template x-if="isSeen">
                            <span
                                class="inline-flex items-center rounded-sm bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                            >
                                Read
                            </span>
                        </template>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <template x-if="!isSeen">
                                <button
                                    type="button"
                                    x-on:click="
                                        $store.notif.markRead({{ $notification->id }})
                                        isSeen = true
                                    "
                                    class="cursor-pointer text-xs font-bold text-blue-600 hover:text-blue-500 hover:underline dark:text-blue-400"
                                >
                                    Mark Read
                                </button>
                            </template>

                            @if ($notification->url)
                                <a
                                    href="{{ $notification->url }}"
                                    class="text-xs font-bold text-slate-600 hover:text-slate-900 hover:underline dark:text-slate-300 dark:hover:text-white"
                                >
                                    View
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>
</x-admin>
