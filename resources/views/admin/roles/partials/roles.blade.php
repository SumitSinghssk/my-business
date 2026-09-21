@can('admin.roles.view')
    <div x-show="activeTab === 'roles'" x-cloak class="space-y-8">
        @can('admin.roles.create')
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/50">
                <h3 class="mb-4 text-sm font-semibold text-slate-700 dark:text-slate-300">Create New Role</h3>
                <form method="POST" action="{{ route('admin.roles.store') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <x-admin.form-label for="role_name" label="Role Name" />

                        <x-admin.form-input
                            type="text"
                            id="role_name"
                            name="name"
                            :value="old('name')"
                            placeholder="e.g. editor, moderator"
                            :error="$errors->first('name')"
                        >
                            <x-slot:leftIcon>
                                <x-icons.account-circle class="h-5 w-5" />
                            </x-slot>
                        </x-admin.form-input>

                        <x-admin.form-error for="name" />
                    </div>

                    <x-admin.button type="submit" variant="primary">Create Role</x-admin.button>
                </form>
            </div>
        @endcan

        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                All Roles
                <span
                    class="ml-2 inline-flex items-center rounded-sm bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-900/40 dark:text-blue-400"
                >
                    {{ $roles->count() }}
                </span>
            </h3>

            @forelse ($roles as $role)
                <div
                    x-data="{ open: false }"
                    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
                >
                    <div class="flex items-center justify-between px-5 py-4">
                        <div
                            class="{{ Auth::user()->can('admin.roles.update') ? 'cursor-pointer' : '' }} flex items-center gap-3"
                            @can('admin.roles.update') x-on:click="open = !open" @endcan
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40">
                                <x-icons.verified-user class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $role->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $role->permissions->count() }} permission{{ $role->permissions->count() !== 1 ? 's' : '' }} assigned
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @can('admin.roles.update')
                                <button
                                    type="button"
                                    x-on:click.stop="open = !open"
                                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                                    :title="open ? 'Collapse' : 'Manage permissions'"
                                >
                                    <x-icons.chevron-down x-bind:class="open ? 'rotate-180' : ''" class="h-4 w-4 transition-transform duration-200" />
                                </button>
                            @endcan

                            @can('admin.roles.delete')
                                @if (! in_array($role->name, ['super admin', 'super-admin']))
                                    <form
                                        method="POST"
                                        action="{{ route('admin.roles.destroy', $role) }}"
                                        onsubmit="return confirm('Delete role \'{{ $role->name }}\'? This cannot be undone.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                            title="Delete role"
                                        >
                                            <x-icons.delete class="h-4 w-4" />
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition duration-200 ease-out"
                        x-transition:enter-start="-translate-y-1 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:leave="transition duration-150 ease-in"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="-translate-y-1 opacity-0"
                    >
                        @can('admin.roles.update')
                            <div class="border-t border-slate-100 px-5 pt-4 pb-5 dark:border-slate-800">
                                <p class="mb-4 text-xs text-slate-500 dark:text-slate-400">
                                    Toggle checkboxes to grant or revoke permissions. Changes are saved instantly.
                                </p>

                                @if ($groupedPermissions->isEmpty())
                                    <p class="text-sm text-slate-400">No permissions found. Create some in the Permissions tab.</p>
                                @else
                                    <div class="space-y-5">
                                        @foreach ($groupedPermissions as $group => $groupPerms)
                                            <div>
                                                <h4 class="mb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase dark:text-slate-500">
                                                    {{ $group }}
                                                </h4>
                                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                                    @foreach ($groupPerms as $permission)
                                                        <label
                                                            x-data="permissionToggle(
                                                                        '{{ route('admin.roles.toggle-permission', $role) }}',
                                                                        '{{ $permission->name }}',
                                                                        {{ $role->hasPermissionTo($permission->name) ? 'true' : 'false' }},
                                                                    )"
                                                            class="flex cursor-pointer items-center gap-2.5 rounded-lg border px-3 py-2 text-xs transition-colors"
                                                            :class="checked
                                                                                ? 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20'
                                                                                : 'border-slate-200 bg-white hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-slate-600'"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                class="sr-only"
                                                                :checked="checked"
                                                                x-on:change="toggle()"
                                                                :disabled="loading"
                                                            />
                                                            <span
                                                                class="flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-colors"
                                                                :class="checked
                                                                                    ? 'border-blue-500 bg-blue-500 dark:border-blue-400 dark:bg-blue-500'
                                                                                    : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-700'"
                                                            >
                                                                <x-icons.check x-show="checked && !loading" class="h-2.5 w-2.5 text-white" />
                                                                <x-icons.loading x-show="loading" class="h-3 w-3 animate-spin text-blue-500" />
                                                            </span>
                                                            <span
                                                                class="truncate leading-none font-medium"
                                                                :class="checked ? 'text-blue-700 dark:text-blue-300' : 'text-slate-600 dark:text-slate-400'"
                                                                title="{{ $permission->name }}"
                                                            >
                                                                {{ $permission->name }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endcan
                    </div>
                </div>
            @empty
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 py-12 text-center dark:border-slate-700"
                >
                    <x-icons.shield-check class="mb-3 h-10 w-10 text-slate-300 dark:text-slate-600" />
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No roles found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Create your first role using the form above.</p>
                </div>
            @endforelse
        </div>
    </div>
@endcan
