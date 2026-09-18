<div class="flex flex-col">
    <div class="hidden items-center justify-end border-b border-slate-100 px-4 py-3 md:flex md:justify-between dark:border-slate-800">
        <div class="hidden items-center gap-1 md:flex">
            <x-icons.notification class="h-4 w-4 text-white" />
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Notifications</h3>
        </div>

        @can('admin.notifications.mark-all-as-read')
            <button
                x-show="$store.notif.unread > 0"
                x-on:click="$store.notif.markAllRead()"
                class="cursor-pointer text-xs font-medium text-blue-600 hover:underline dark:text-blue-400"
            >
                Mark all as read
            </button>
        @endcan
    </div>

    <div class="max-h-62.5 flex-1 overflow-y-auto">
        <template x-if="$store.notif.items.length === 0">
            <div class="p-6 text-center text-sm text-slate-500">No notifications</div>
        </template>

        <template x-for="notif in $store.notif.items" :key="notif.id">
            <a
                :href="`{{ url('admin/notifications') }}/${notif.id}/view`"
                x-on:click="$store.notif.markRead(notif.id)"
                class="block px-4 py-3 transition hover:bg-slate-50 dark:hover:bg-slate-800"
            >
                <div class="flex items-start gap-3">
                    <div class="mt-1">
                        <div x-show="!notif.seen" class="h-2 w-2 rounded-full bg-blue-500"></div>
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-900 dark:text-white" x-text="notif.title"></p>

                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400" x-text="notif.message"></p>

                        <p class="mt-1 text-[10px] text-slate-400" x-text="notif.time"></p>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <div class="hidden border-t border-slate-100 px-4 py-3 md:block dark:border-slate-800">
        <a
            href="{{ route('admin.notifications.list') }}"
            class="block w-full rounded-lg bg-slate-100 px-4 py-2 text-center text-xs font-medium text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
        >
            View All Notifications
        </a>
    </div>
</div>
