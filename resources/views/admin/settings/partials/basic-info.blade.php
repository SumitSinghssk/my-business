@php
    $canUpdate = auth()
        ->user()
        ->can("admin.settings.basic-details.update");
@endphp

<form
    @if ($canUpdate)
        action="{{ route("admin.settings.basic.update") }}"
        method="POST"
        enctype="multipart/form-data"
    @endif
    x-data="{
        submitting: false,
        canUpdate: {{ $canUpdate ? "true" : "false" }},
        phones: {{ json_encode(old("phones", $settings["phones"] ?? [""])) }},
        emails: {{ json_encode(old("emails", $settings["emails"] ?? [""])) }},
        social_links:
            {{ json_encode(old("social_links", $settings["social_links"] ?? [])) }},
        addresses:
            {{ json_encode(old("addresses", $settings["addresses"] ?? [])) }},
        addPhone() {
            if (this.canUpdate) this.phones.push('')
        },
        removePhone(i) {
            if (this.canUpdate) this.phones.splice(i, 1)
        },

        addEmail() {
            if (this.canUpdate) this.emails.push('')
        },
        removeEmail(i) {
            if (this.canUpdate) this.emails.splice(i, 1)
        },

        addSocial() {
            if (this.canUpdate) this.social_links.push({ platform: '', url: '' })
        },
        removeSocial(i) {
            if (this.canUpdate) this.social_links.splice(i, 1)
        },

        addAddress() {
            if (this.canUpdate)
                this.addresses.push({ label: '', text: '', map_iframe: '' })
        },
        removeAddress(i) {
            if (this.canUpdate) this.addresses.splice(i, 1)
        },
    }"
    x-on:submit="submitting = true"
    class="space-y-12"
>
    @csrf
    @method("POST")

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">General Information</h3>
            <p class="text-xs text-slate-500">Basic identification and location settings.</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <x-admin.form-label for="app_name" label="Application Name" required />
                <x-admin.form-input
                    type="text"
                    name="app_name"
                    id="app_name"
                    :disabled="!$canUpdate"
                    :value="old('app_name', $settings['app_name'] ?? '')"
                    placeholder="e.g. My Awesome App"
                    :error="$errors->first('app_name')"
                />
                <x-admin.form-error for="app_name" />
            </div>
        </div>
    </section>

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Branding &amp; Logos</h3>
            <p class="text-xs text-slate-500">Customize the visual identity of your dashboard.</p>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div
                x-data="{
                    preview:
                        '{{ isset($settings["logo"]["light"]) ? asset("storage/" . $settings["logo"]["light"]) : "" }}',
                }"
            >
                <x-admin.form-label label="Logo (Light Mode)" />
                <div
                    @if($canUpdate) x-on:click="$refs.lightInput.click()" @endif
                    class="{{ $canUpdate ? "cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700/50" : "cursor-default" }} relative mt-2 flex h-40 items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition dark:border-slate-700 dark:bg-slate-800"
                >
                    <template x-if="preview">
                        <img :src="preview" class="h-full w-full object-contain p-4" alt="Logo light preview" />
                    </template>
                    <template x-if="!preview">
                        <div class="text-center">
                            <x-icons.gallery class="mx-auto h-8 w-8 text-slate-400" />
                            @if ($canUpdate)
                                <span class="mt-2 block text-xs text-slate-400">Click to upload</span>
                            @endif
                        </div>
                    </template>
                    @if ($canUpdate)
                        <input
                            type="file"
                            name="logo_light"
                            x-ref="lightInput"
                            class="hidden"
                            accept="image/*"
                            x-on:change="preview = URL.createObjectURL($event.target.files[0])"
                        />
                    @endif
                </div>
            </div>

            <div
                x-data="{
                    preview:
                        '{{ isset($settings["logo"]["dark"]) ? asset("storage/" . $settings["logo"]["dark"]) : "" }}',
                }"
            >
                <x-admin.form-label label="Logo (Dark Mode)" />
                <div
                    @if($canUpdate) x-on:click="$refs.darkInput.click()" @endif
                    class="{{ $canUpdate ? "cursor-pointer hover:bg-slate-950" : "cursor-default" }} relative mt-2 flex h-40 items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-700 bg-slate-900 transition"
                >
                    <template x-if="preview">
                        <img :src="preview" class="h-full w-full object-contain p-4" alt="Logo dark preview" />
                    </template>
                    <template x-if="!preview">
                        <div class="text-center">
                            <x-icons.gallery class="mx-auto h-8 w-8 text-slate-600" />
                            @if ($canUpdate)
                                <span class="mt-2 block text-xs text-slate-600">Click to upload</span>
                            @endif
                        </div>
                    </template>
                    @if ($canUpdate)
                        <input
                            type="file"
                            name="logo_dark"
                            x-ref="darkInput"
                            class="hidden"
                            accept="image/*"
                            x-on:change="preview = URL.createObjectURL($event.target.files[0])"
                        />
                    @endif
                </div>
            </div>
        </div>

        <div
            x-data="{
                preview:
                    '{{ isset($settings["favicon"]) ? asset("storage/" . $settings["favicon"]) : "" }}',
            }"
            class="pt-2"
        >
            <x-admin.form-label label="Favicon" />
            <div class="mt-2 flex items-center gap-6">
                <div
                    @if($canUpdate) x-on:click="$refs.faviconInput.click()" @endif
                    class="{{ $canUpdate ? "cursor-pointer hover:bg-slate-100" : "cursor-default" }} flex h-16 w-16 items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 transition dark:border-slate-700 dark:bg-slate-800"
                >
                    <img x-show="preview" :src="preview" class="h-full w-full object-cover p-1" alt="Favicon preview" />
                    <x-icons.gallery x-show="!preview" class="h-5 w-5 text-slate-400" />
                    @if ($canUpdate)
                        <input
                            type="file"
                            name="favicon"
                            x-ref="faviconInput"
                            class="hidden"
                            accept="image/*"
                            x-on:change="preview = URL.createObjectURL($event.target.files[0])"
                        />
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Browser Icon</p>
                    <p class="text-xs text-slate-500">Square PNG or ICO (32×32 px recommended)</p>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Physical Addresses</h3>
                <p class="text-xs text-slate-500">Manage multiple office or store locations</p>
            </div>
            @if ($canUpdate)
                <button
                    type="button"
                    x-on:click="addAddress()"
                    class="inline-flex cursor-pointer items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                >
                    <span class="text-lg">+</span>
                    Add Address
                </button>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <template x-for="(address, index) in addresses" :key="index">
                <div
                    class="group relative flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900/50 dark:hover:border-slate-600"
                >
                    @if ($canUpdate)
                        <button
                            type="button"
                            x-on:click="removeAddress(index)"
                            class="absolute top-0 right-0 z-10 flex h-7 w-7 cursor-pointer items-center justify-center rounded-bl-md bg-white/90 text-slate-400 opacity-0 transition-all group-hover:opacity-100 hover:bg-red-50 hover:text-red-500 dark:bg-slate-800/90 dark:hover:bg-red-900/30"
                        >
                            <x-icons.close class="h-4 w-4" />
                        </button>
                    @endif

                    <div class="flex-1 space-y-4 p-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold tracking-wider text-slate-500 uppercase">Label</label>
                                <input
                                    type="text"
                                    x-model="address.label"
                                    :name="'addresses['+index+'][label]'"
                                    :disabled="!canUpdate"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                    placeholder="e.g. Head Office"
                                />
                            </div>

                            <div>
                                <label class="mb-1 block text-[10px] font-bold tracking-wider text-slate-500 uppercase">Physical Address</label>
                                <input
                                    type="text"
                                    x-model="address.text"
                                    :name="'addresses['+index+'][text]'"
                                    :disabled="!canUpdate"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                    placeholder="Street, City..."
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-[10px] font-bold tracking-wider text-slate-500 uppercase">Google Maps Embed Code</label>
                            <textarea
                                x-model="address.map_iframe"
                                :name="'addresses['+index+'][map_iframe]'"
                                :disabled="!canUpdate"
                                rows="2"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-[10px] leading-tight transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400"
                                placeholder="Paste <iframe> code here..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="h-64 w-full bg-slate-100 dark:bg-slate-800/50">
                        <template x-if="address.map_iframe && address.map_iframe.includes('<iframe')">
                            <div x-html="address.map_iframe" class="h-full w-full [&_iframe]:h-full [&_iframe]:w-full [&_iframe]:border-0"></div>
                        </template>

                        <template x-if="! address.map_iframe || ! address.map_iframe.includes('<iframe')">
                            <div
                                class="flex h-full w-full flex-col items-center justify-center border-t border-slate-100 text-center dark:border-slate-800"
                            >
                                <x-icons.location class="mb-1 h-6 w-6 text-slate-300 dark:text-slate-600" />

                                <p class="text-[10px] font-medium tracking-tight text-slate-400 uppercase">No Map Preview</p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="addresses.length === 0">
                <div
                    class="flex flex-col items-center gap-3 rounded-xl border-2 border-dashed border-slate-200 py-12 text-center md:col-span-2 dark:border-slate-800"
                >
                    <div class="rounded-full bg-slate-50 p-4 dark:bg-slate-900">
                        <x-icons.location class="h-8 w-8 text-slate-300" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">No addresses added yet</p>
                        @if ($canUpdate)
                            <button
                                type="button"
                                x-on:click="addAddress()"
                                class="mt-1 cursor-pointer text-sm font-bold text-blue-600 hover:text-blue-500 dark:text-blue-400"
                            >
                                + Add a location
                            </button>
                        @endif
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section class="space-y-8">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Contact Details</h3>
            <p class="text-xs text-slate-500">Manage how customers reach out to you.</p>
        </div>

        <div class="space-y-10">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Phone Numbers</label>
                        <p class="text-xs text-slate-500">Add one or more contact numbers</p>
                    </div>
                    @if ($canUpdate)
                        <button
                            type="button"
                            x-on:click="addPhone()"
                            class="inline-flex cursor-pointer items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                        >
                            <span class="text-lg">+</span>
                            Add Phone
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <template x-for="(phone, index) in phones" :key="index">
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <x-admin.form-input
                                    type="text"
                                    name="phones[]"
                                    x-model="phones[index]"
                                    placeholder="e.g. +1 234 567 8900"
                                    :disabled="!$canUpdate"
                                />
                            </div>
                            @if ($canUpdate)
                                <button
                                    type="button"
                                    x-on:click="removePhone(index)"
                                    class="group shrink-0 cursor-pointer rounded-lg p-2 transition hover:bg-red-50 dark:hover:bg-red-900/20"
                                >
                                    <x-icons.close class="h-4 w-4 text-slate-400 group-hover:text-red-500" />
                                </button>
                            @endif
                        </div>
                    </template>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Addresses</label>
                        <p class="text-xs text-slate-500">Primary and secondary contact emails</p>
                    </div>
                    @if ($canUpdate)
                        <button
                            type="button"
                            x-on:click="addEmail()"
                            class="inline-flex cursor-pointer items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                        >
                            <span class="text-lg">+</span>
                            Add Email
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <template x-for="(email, index) in emails" :key="index">
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <x-admin.form-input
                                    type="email"
                                    name="emails[]"
                                    x-model="emails[index]"
                                    placeholder="e.g. hello@domain.com"
                                    :disabled="!$canUpdate"
                                />
                            </div>
                            @if ($canUpdate)
                                <button
                                    type="button"
                                    x-on:click="removeEmail(index)"
                                    class="group shrink-0 cursor-pointer rounded-lg p-2 transition hover:bg-red-50 dark:hover:bg-red-900/20"
                                >
                                    <x-icons.close class="h-4 w-4 text-slate-400 group-hover:text-red-500" />
                                </button>
                            @endif
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Social Media</h3>
                    <p class="text-xs text-slate-500">Connect your profiles for the website footer.</p>
                </div>
                @if ($canUpdate)
                    <button
                        type="button"
                        x-on:click="addSocial()"
                        class="inline-flex cursor-pointer items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                    >
                        <span class="text-lg">+</span>
                        Add Platform
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <template x-for="(social, index) in social_links" :key="index">
                <div
                    class="group relative flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800/50"
                >
                    @if ($canUpdate)
                        <button
                            type="button"
                            x-on:click="removeSocial(index)"
                            class="absolute top-4 right-4 cursor-pointer rounded-md p-1 text-slate-300 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20"
                        >
                            <x-icons.close class="h-4 w-4" />
                        </button>
                    @endif

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold text-slate-400 uppercase">Platform</label>
                            <input type="hidden" :name="'social_links['+index+'][platform]'" x-model="social.platform" />
                            <div class="relative">
                                <select
                                    x-model="social.platform"
                                    :disabled="!canUpdate"
                                    class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none disabled:opacity-70 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                >
                                    <option value="" disabled>Select platform…</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="X (Twitter)">X (Twitter)</option>
                                    <option value="LinkedIn">LinkedIn</option>
                                    <option value="YouTube">YouTube</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Pinterest">Pinterest</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="GitHub">GitHub</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold text-slate-400 uppercase">Profile URL</label>
                            <input
                                type="url"
                                :name="'social_links['+index+'][url]'"
                                x-model="social.url"
                                :disabled="!canUpdate"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none disabled:opacity-70 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                placeholder="https://…"
                            />
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="social_links.length === 0">
                <div
                    class="flex flex-col items-center gap-3 rounded-2xl border-2 border-dashed border-slate-200 py-12 text-center md:col-span-2 dark:border-slate-700"
                >
                    <div class="rounded-full bg-slate-50 p-4 dark:bg-slate-800">
                        <x-icons.url class="h-8 w-8 text-slate-300" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">No social platforms linked</p>
                        @if ($canUpdate)
                            <button
                                type="button"
                                x-on:click="addSocial()"
                                class="mt-1 cursor-pointer text-sm font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400"
                            >
                                Add your first platform
                            </button>
                        @endif
                    </div>
                </div>
            </template>
        </div>
    </section>

    @if ($canUpdate)
        <div
            class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-between border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
        >
            <div class="flex w-full items-center justify-end gap-4">
                <x-admin.button>
                    <span x-text="submitting ? 'Saving…' : 'Save Settings'">Save Settings</span>
                </x-admin.button>
            </div>
        </div>
    @endif
</form>
