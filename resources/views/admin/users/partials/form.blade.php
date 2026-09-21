@php
    $isEdit = isset($user);
@endphp

<div
    x-data="{
        activeTab: 'details',
        tabs: [
            { id: 'details', label: 'Details', icon: 'user' },
            { id: 'password', label: 'Password', icon: 'lock' },
            { id: 'roles', label: 'Roles', icon: 'shield' },
            { id: 'permissions', label: 'Permissions', icon: 'key' },
        ],

        allRoles: {{ Js::from($rolesWithPermissions) }},
        selectedRoles: {{ Js::from(old('roles', $userRoles ?? [])) }},
        directPermissions:
            {{ Js::from(old('permissions', $userPermissions ?? [])) }},

        init() {
            this.syncRolePermissionsToDir()
        },

        syncRolePermissionsToDir() {
            this.rolePermissions.forEach((p) => {
                if (! this.directPermissions.includes(p)) {
                    this.directPermissions.push(p)
                }
            })
        },

        get rolePermissions() {
            const perms = new Set()
            this.allRoles
                .filter((r) => this.selectedRoles.includes(r.name))
                .forEach((r) => r.permissions.forEach((p) => perms.add(p)))
            return perms
        },

        isFromRole(permName) {
            return this.rolePermissions.has(permName)
        },

        isPermChecked(permName) {
            return this.directPermissions.includes(permName)
        },

        togglePermission(permName) {
            const idx = this.directPermissions.indexOf(permName)
            if (idx === -1) {
                this.directPermissions.push(permName)
            } else {
                this.directPermissions.splice(idx, 1)
            }
        },

        toggleRole(roleName) {
            const idx = this.selectedRoles.indexOf(roleName)
            if (idx === -1) {
                this.selectedRoles.push(roleName)
                const role = this.allRoles.find((r) => r.name === roleName)
                if (role) {
                    role.permissions.forEach((p) => {
                        if (! this.directPermissions.includes(p)) {
                            this.directPermissions.push(p)
                        }
                    })
                }
            } else {
                this.selectedRoles.splice(idx, 1)
            }
        },

        isRoleSelected(roleName) {
            return this.selectedRoles.includes(roleName)
        },
    }"
    class="space-y-6"
>
    <template x-for="role in selectedRoles" :key="'r_' + role">
        <input type="hidden" name="roles[]" :value="role" />
    </template>
    <template x-for="perm in directPermissions" :key="'p_' + perm">
        <input type="hidden" name="permissions[]" :value="perm" />
    </template>

    <div class="sticky top-24 z-20 border-b border-slate-200 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80">
        <nav class="scrollbar-hide -mb-px flex items-center gap-2 overflow-x-auto whitespace-nowrap" aria-label="Tabs">
            <template x-for="tab in tabs" :key="tab.id">
                <button
                    type="button"
                    x-on:click="activeTab = tab.id"
                    :class="activeTab === tab.id
                        ? 'text-blue-600 dark:text-blue-400'
                        : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="group relative flex cursor-pointer items-center gap-2.5 px-4 py-4 text-sm font-bold transition-all duration-200 focus:outline-none"
                >
                    <div
                        :class="activeTab === tab.id
                            ? 'text-blue-600 dark:text-blue-400'
                            : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                        class="transition-colors duration-200"
                    >
                        <template x-if="tab.icon === 'user'">
                            <x-icons.account-circle class="h-4 w-4" />
                        </template>
                        <template x-if="tab.icon === 'lock'">
                            <x-icons.lock class="h-4 w-4" />
                        </template>
                        <template x-if="tab.icon === 'shield'">
                            <x-icons.verified-user class="h-4 w-4" />
                        </template>
                        <template x-if="tab.icon === 'key'">
                            <x-icons.key class="h-4 w-4" />
                        </template>
                    </div>
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

    <div x-show="activeTab === 'details'" x-cloak class="space-y-5">
        <x-admin.image-upload
            name="avatar"
            preset="avatar"
            remove-name="remove_avatar"
            label="Avatar"
            class="max-w-xs"
            :current="isset($user) && $user->avatar ? asset('storage/' . $user->avatar) : null"
        />

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <x-admin.form-label for="name" label="Full Name" required />
                <x-admin.form-input
                    type="text"
                    name="name"
                    id="name"
                    :value="old('name', $user->name ?? '')"
                    placeholder="e.g. John Doe"
                    :error="$errors->first('name')"
                >
                    <x-slot:leftIcon>
                        <x-icons.account-circle class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
                <x-admin.form-error for="name" />
            </div>

            <div>
                <x-admin.form-label for="email" label="Email Address" required />
                <x-admin.form-input
                    type="email"
                    name="email"
                    id="email"
                    :value="old('email', $user->email ?? '')"
                    placeholder="e.g. john@example.com"
                    :error="$errors->first('email')"
                >
                    <x-slot:leftIcon>
                        <x-icons.mail class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
                <x-admin.form-error for="email" />
            </div>
        </div>

        <div>
            <x-admin.form-label for="status" label="Status" required />
            @php
                use App\Enums\CommonStatusEnum;

                $status = old('status', $user->status->value ?? CommonStatusEnum::ACTIVE->value);
            @endphp

            <x-admin.form-select name="status" id="status">
                @foreach (CommonStatusEnum::cases() as $case)
                    <option value="{{ $case->value }}" @selected($status === $case->value)>
                        {{ $case->label() }}
                    </option>
                @endforeach
            </x-admin.form-select>
            <x-admin.form-error for="status" />
        </div>

        <div>
            <x-admin.form-label for="bio" label="Bio" />
            <x-admin.form-textarea name="bio" id="bio" rows="3" placeholder="Short bio or description...">
                {{ old('bio', $user->bio ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="bio" />
        </div>
    </div>

    <div x-show="activeTab === 'password'" x-cloak class="space-y-5">
        @if ($isEdit)
            <div
                class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-400"
            >
                <x-icons.lock class="h-4 w-4" />
                Leave blank to keep the current password unchanged.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <x-admin.form-label for="password" label="Password" :required="!$isEdit" />
                <x-admin.form-input
                    type="password"
                    name="password"
                    id="password"
                    autocomplete="new-password"
                    :placeholder="$isEdit ? 'Leave blank to keep current' : 'Min. 8 characters'"
                    :error="$errors->first('password')"
                >
                    <x-slot:leftIcon>
                        <x-icons.lock class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
                <x-admin.form-error for="password" />
            </div>

            <div>
                <x-admin.form-label for="password_confirmation" label="Confirm Password" :required="!$isEdit" />
                <x-admin.form-input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    :placeholder="$isEdit ? 'Leave blank to keep current' : 'Repeat password'"
                >
                    <x-slot:leftIcon>
                        <x-icons.lock class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'roles'" x-cloak class="space-y-5">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Assign one or more roles. Permissions from selected roles will be automatically checked in the Permissions tab.
        </p>

        @if ($roles->isEmpty())
            <div
                class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 py-12 text-center dark:border-slate-700"
            >
                <x-icons.verified-user class="mb-3 h-10 w-10 text-slate-300 dark:text-slate-600" />
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No roles available</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                    <a href="{{ route('admin.roles.index') }}" class="text-blue-500 hover:underline">Create roles</a>
                    first.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($roles as $role)
                    <label
                        x-on:click.prevent="toggleRole('{{ $role->name }}')"
                        :class="isRoleSelected('{{ $role->name }}')
                            ? 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20'
                            : 'border-slate-200 bg-white hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-slate-600'"
                        class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 transition-colors"
                    >
                        <span
                            class="flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-colors"
                            :class="isRoleSelected('{{ $role->name }}')
                                ? 'border-blue-500 bg-blue-500'
                                : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-700'"
                        >
                            <x-icons.check x-show="isRoleSelected('{{ $role->name }}')" class="h-2.5 w-2.5 text-white" />
                        </span>
                        <div>
                            <p
                                class="text-sm font-medium transition-colors"
                                :class="isRoleSelected('{{ $role->name }}')
                                    ? 'text-blue-700 dark:text-blue-300'
                                    : 'text-slate-700 dark:text-slate-300'"
                            >
                                {{ $role->name }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $role->permissions->count() }} permission{{ $role->permissions->count() !== 1 ? 's' : '' }}
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        @endif
    </div>

    <div x-show="activeTab === 'permissions'" x-cloak class="space-y-5">
        <div
            class="flex items-start gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-400"
        >
            <x-icons.info class="mt-0.5 h-4 w-4 shrink-0" />

            Permissions from assigned roles are pre-selected. You can freely add or remove any permission individually.
        </div>

        @if ($groupedPermissions->isEmpty())
            <div
                class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 py-12 text-center dark:border-slate-700"
            >
                <x-icons.key class="mb-3 h-10 w-10 text-slate-300 dark:text-slate-600" />
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No permissions available</p>
            </div>
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
                                    x-on:click.prevent="togglePermission('{{ $permission->name }}')"
                                    :class="isPermChecked('{{ $permission->name }}')
                                        ? 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20'
                                        : 'border-slate-200 bg-white hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-slate-600'"
                                    class="flex cursor-pointer items-center gap-2.5 rounded-lg border px-3 py-2 text-xs transition-colors"
                                >
                                    <span
                                        class="flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-colors"
                                        :class="isPermChecked('{{ $permission->name }}')
                                            ? 'border-blue-500 bg-blue-500'
                                            : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-700'"
                                    >
                                        <x-icons.check x-show="isPermChecked('{{ $permission->name }}')" class="h-2.5 w-2.5 text-white" />
                                    </span>
                                    <span
                                        class="truncate leading-none font-medium"
                                        :class="isPermChecked('{{ $permission->name }}')
                                            ? 'text-blue-700 dark:text-blue-300'
                                            : 'text-slate-600 dark:text-slate-400'"
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
</div>

<div class="mt-6"></div>
