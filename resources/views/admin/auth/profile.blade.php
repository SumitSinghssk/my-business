<x-admin :breadcrumb="[
    ['label' => 'Profile'],
    ]">
    @php
        $canUpdate = auth()
            ->user()
            ->can("profile.update");
    @endphp

    <div class="mx-auto max-w-5xl space-y-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="px-2">
                <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Account Information</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Update your account's profile information and email address.</p>
            </div>

            <div class="lg:col-span-2">
                <form
                    @if ($canUpdate)
                        method="POST"
                        action="{{ route("admin.profile.update") }}"
                        x-data="{ submitting: false }"
                        x-on:submit="submitting = true"
                        enctype="multipart/form-data"
                    @endcan
                    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
                >
                    @csrf
                    @method("PATCH")

                    <div class="space-y-5 p-6 sm:p-8">
                        <div
                            class="flex justify-center"
                            x-data="{
                                preview:
                                    '{{ auth()->user()->avatar ? asset("storage/" . auth()->user()->avatar) : "" }}',
                                removeAvatar: false,
                                handleFileChange(event) {
                                    const file = event.target.files[0]
                                    if (file) {
                                        this.preview = URL.createObjectURL(file)
                                        this.removeAvatar = false
                                    }
                                },
                                clearPreview() {
                                    this.preview = ''
                                    this.removeAvatar = true
                                    this.$refs.file.value = '' // Reset file input
                                },
                            }"
                        >
                            <div class="group relative">
                                <input type="file" name="avatar" accept="image/*" class="hidden" x-ref="file" x-on:change="handleFileChange" />

                                <input type="hidden" name="remove_avatar" :value="removeAvatar ? 1 : 0" />

                                <div
                                    x-on:click="$refs.file.click()"
                                    class="relative flex h-28 w-28 cursor-pointer items-center justify-center overflow-hidden rounded-full border-4 border-white bg-slate-100 shadow-md transition-all dark:border-slate-800 dark:bg-slate-700"
                                >
                                    <template x-if="preview">
                                        <img :src="preview" class="h-full w-full object-cover" />
                                    </template>

                                    <template x-if="!preview">
                                        <x-icons.account-circle class="h-12 w-12 text-slate-400" />
                                    </template>

                                    <div
                                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                    >
                                        <x-icons.camera class="h-6 w-6 text-white" />
                                        <span class="text-[10px] font-medium tracking-wider text-white uppercase">Update</span>
                                    </div>
                                </div>

                                <template x-if="preview">
                                    <button
                                        type="button"
                                        x-on:click="clearPreview()"
                                        class="absolute top-2 right-1 flex h-5 w-5 cursor-pointer items-center justify-center rounded-full bg-red-500 text-white shadow-lg ring-2 ring-white transition-transform hover:scale-110 hover:bg-red-600 focus:outline-none dark:ring-slate-900"
                                        title="Remove image"
                                    >
                                        <x-icons.close class="h-4 w-4" />
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div>
                            <x-admin.form-label for="name" label="Full Name" />

                            <x-admin.form-input
                                type="text"
                                name="name"
                                :value="old('name', auth()->user()->name)"
                                placeholder="Your Name"
                                :disabled="!$canUpdate"
                                :error="$errors->first('name')"
                            >
                                <x-slot:leftIcon>
                                    <x-icons.account-circle class="h-5 w-5" />
                                </x-slot>
                            </x-admin.form-input>

                            <x-admin.form-error for="name" />
                        </div>

                        <div>
                            <x-admin.form-label for="email" label="Email Address" />

                            <x-admin.form-input
                                type="email"
                                name="email"
                                disabled
                                :value="old('email', auth()->user()->email)"
                                placeholder="admin@devsales.com"
                            >
                                <x-slot:leftIcon>
                                    <x-icons.mail class="h-5 w-5" />
                                </x-slot>
                            </x-admin.form-input>

                            <x-admin.form-error for="email" />
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <x-admin.form-input
                                name="social_links[twitter]"
                                :value="old('social_links.twitter', auth()->user()->social_links['twitter'] ?? '')"
                                placeholder="Twitter URL"
                            />

                            <x-admin.form-input
                                name="social_links[linkedin]"
                                :value="old('social_links.linkedin', auth()->user()->social_links['linkedin'] ?? '')"
                                placeholder="LinkedIn URL"
                            />

                            <x-admin.form-input
                                name="social_links[github]"
                                :value="old('social_links.github', auth()->user()->social_links['github'] ?? '')"
                                placeholder="GitHub URL"
                            />

                            <x-admin.form-input
                                name="social_links[instagram]"
                                :value="old('social_links.instagram', auth()->user()->social_links['instagram'] ?? '')"
                                placeholder="Instagram URL"
                            />

                            <x-admin.form-input
                                name="social_links[facebook]"
                                :value="old('social_links.facebook', auth()->user()->social_links['facebook'] ?? '')"
                                placeholder="Facebook URL"
                            />

                            <x-admin.form-input
                                name="social_links[youtube]"
                                :value="old('social_links.youtube', auth()->user()->social_links['youtube'] ?? '')"
                                placeholder="YouTube URL"
                            />
                        </div>

                        <div>
                            <x-admin.form-label for="bio" label="Bio" />

                            <x-admin.form-textarea name="bio" rows="3" placeholder="Write something about yourself..." :disabled="!$canUpdate">
                                {{ old("bio", auth()->user()->bio) }}
                            </x-admin.form-textarea>

                            <x-admin.form-error for="bio" />
                        </div>
                    </div>

                    @if ($canUpdate)
                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/50">
                            <x-admin.button class="w-full">
                                <span x-text="submitting ? 'Saving...' : 'Save Changes'">Save Changes</span>
                            </x-admin.button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @can("profile.update-password")
            <div class="hidden border-t border-slate-200 sm:block dark:border-slate-800"></div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="px-2">
                    <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Update Password</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                </div>

                <div class="lg:col-span-2">
                    <form
                        method="POST"
                        action="{{ route("admin.update-password") }}"
                        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
                        x-data="{ submitting: false }"
                        x-on:submit="submitting = true"
                    >
                        @csrf
                        @method("PUT")

                        <div class="space-y-5 p-6 sm:p-8">
                            <div>
                                <x-admin.form-label for="current_password" label="Current Password" />

                                <x-admin.form-input
                                    type="password"
                                    name="current_password"
                                    placeholder="••••••••"
                                    toggle
                                    :error="$errors->updatePassword->first('current_password')"
                                />
                                <x-admin.form-error for="current_password" />
                            </div>

                            <div>
                                <x-admin.form-label for="password" label="New Password" />

                                <x-admin.form-input
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    toggle
                                    :error="$errors->updatePassword->first('password')"
                                />
                                <x-admin.form-error for="password" />
                            </div>

                            <div>
                                <x-admin.form-label for="password_confirmation" label="Confirm New Password" />

                                <x-admin.form-input type="password" name="password_confirmation" placeholder="••••••••" toggle />
                                <x-admin.form-error for="password_confirmation" />
                            </div>
                        </div>

                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/50">
                            <x-admin.button class="w-full">
                                <span x-text="submitting ? 'Updating...' : 'Update Password'">Update Password</span>
                            </x-admin.button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
    </div>
</x-admin>
