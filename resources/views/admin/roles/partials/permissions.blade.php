@can('admin.permissions.view')
    <div x-show="activeTab === 'permissions'" x-cloak class="space-y-8">
        @can('admin.permissions.create')
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/50">
                <h3 class="mb-4 text-sm font-semibold text-slate-700 dark:text-slate-300">Create New Permission</h3>
                <form method="POST" action="{{ route('admin.permissions.store') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <x-admin.form-label for="permission_name" label="Permission Name" />

                        <x-admin.form-input
                            type="text"
                            id="permission_name"
                            name="name"
                            :value="old('name')"
                            placeholder="e.g. admin.posts.create"
                            :error="$errors->first('name')"
                        >
                            <x-slot:leftIcon>
                                <x-icons.key class="h-5 w-5" />
                            </x-slot>
                        </x-admin.form-input>

                        <x-admin.form-error for="name" />
                    </div>
                    <x-admin.button type="submit" variant="primary">Create Permission</x-admin.button>
                </form>
                <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">
                    Use dot-notation:
                    <code class="rounded bg-slate-200 px-1 py-0.5 dark:bg-slate-700">module.resource.action</code>
                    — e.g.
                    <code class="rounded bg-slate-200 px-1 py-0.5 dark:bg-slate-700">admin.posts.delete</code>
                </p>
            </div>
        @endcan

        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                All Permissions
                <span
                    class="ml-2 inline-flex items-center rounded-sm bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-900/40 dark:text-blue-400"
                >
                    {{ $permissions->count() }}
                </span>
            </h3>

            @if ($groupedPermissions->isEmpty())
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 py-12 text-center dark:border-slate-700"
                >
                    <x-icons.key class="mb-3 h-10 w-10 text-slate-300 dark:text-slate-600" />
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No permissions found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Create your first permission using the form above.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($groupedPermissions as $group => $groupPerms)
                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
                            <div
                                class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-5 py-3 dark:border-slate-800 dark:bg-slate-800/50"
                            >
                                <h4 class="text-xs font-semibold tracking-wider text-slate-500 uppercase dark:text-slate-400">
                                    {{ $group }}
                                </h4>
                                <span class="text-xs text-slate-400">
                                    {{ $groupPerms->count() }} item{{ $groupPerms->count() !== 1 ? 's' : '' }}
                                </span>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($groupPerms as $permission)
                                    <div class="flex items-center justify-between px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-slate-100 dark:bg-slate-800">
                                                <x-icons.key class="h-3.5 w-3.5 text-slate-500 dark:text-slate-400" />
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                                    {{ $permission->name }}
                                                </p>
                                                <p class="text-xs text-slate-400">
                                                    Used by {{ $permission->roles->count() }} role{{ $permission->roles->count() !== 1 ? 's' : '' }}
                                                </p>
                                            </div>
                                        </div>

                                        @can('admin.permissions.delete')
                                            <form
                                                method="POST"
                                                action="{{ route('admin.permissions.destroy', $permission) }}"
                                                onsubmit="return confirm('Delete permission \'{{ $permission->name }}\'?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                                    title="Delete permission"
                                                >
                                                    <x-icons.delete class="h-3.5 w-3.5" />
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endcan
