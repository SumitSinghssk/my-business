<div
    x-data="{
        activeTab: 'basic',
        tabs: [
            { id: 'basic', label: 'Basic Info', icon: 'info' },
            { id: 'og', label: 'OG Image', icon: 'image' },
            { id: 'schema', label: 'Schema', icon: 'code' },
            { id: 'scripts', label: 'Scripts & CSS', icon: 'terminal' },
            { id: 'faqs', label: 'FAQs', icon: 'question' },
        ],
        faqs: {{ isset($seo) && $seo->faqs ? json_encode($seo->faqs) : '[]' }},
        addFaq() {
            this.faqs.push({ question: '', answer: '' })
        },
        removeFaq(index) {
            this.faqs.splice(index, 1)
        },
        get faqsJson() {
            return JSON.stringify(this.faqs)
        },
    }"
    class="space-y-6"
>
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
                        :class="activeTab === tab.id ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                        class="transition-colors duration-200"
                    >
                        <template x-if="tab.icon === 'info'">
                            <x-icons.info class="h-4.5 w-4.5" />
                        </template>

                        <template x-if="tab.icon === 'image'">
                            <x-icons.gallery class="h-4.5 w-4.5" />
                        </template>

                        <template x-if="tab.icon === 'code'">
                            <x-icons.code class="h-4.5 w-4.5" />
                        </template>

                        <template x-if="tab.icon === 'terminal'">
                            <x-icons.terminal class="h-4.5 w-4.5" />
                        </template>

                        <template x-if="tab.icon === 'question'">
                            <x-icons.faq class="h-4.5 w-4.5" />
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

    <div x-show="activeTab === 'basic'" x-cloak class="space-y-5">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <x-admin.form-label for="page" label="Page Name" required />
                <x-admin.form-input
                    type="text"
                    name="page"
                    id="page"
                    :value="old('page', $seo->page ?? $defaultData['page'] ?? '')"
                    placeholder="e.g. Home, About, Contact"
                    :error="$errors->first('page')"
                >
                    <x-slot:leftIcon>
                        <x-icons.pages class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
                <x-admin.form-error for="page" />
            </div>

            <div>
                <x-admin.form-label for="slug" label="Slug" required />
                <x-admin.form-input
                    type="text"
                    name="slug"
                    id="slug"
                    :value="old('slug', $seo->slug ?? $defaultData['slug'] ?? '')"
                    placeholder="e.g. home, about-us"
                    :error="$errors->first('slug')"
                >
                    <x-slot:leftIcon>
                        <x-icons.url class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
                <x-admin.form-error for="slug" />
            </div>
        </div>

        <div>
            <x-admin.form-label for="meta_title" label="Meta Title" />
            <x-admin.form-input
                type="text"
                name="meta_title"
                id="meta_title"
                :value="old('meta_title', $seo->meta_title ?? '')"
                placeholder="SEO page title (50–60 chars recommended)"
                :error="$errors->first('meta_title')"
            >
                <x-slot:leftIcon>
                    <x-icons.note class="h-5 w-5" />
                </x-slot>
            </x-admin.form-input>
            <x-admin.form-error for="meta_title" />
        </div>

        <div>
            <x-admin.form-label for="meta_description" label="Meta Description" />
            <x-admin.form-textarea
                name="meta_description"
                id="meta_description"
                rows="3"
                placeholder="Brief description of the page (150–160 chars recommended)..."
            >
                {{ old('meta_description', $seo->meta_description ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="meta_description" />
        </div>

        <div class="flex items-center justify-between rounded-lg border border-slate-200 p-4 dark:border-slate-700">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Allow Search Engine Indexing</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">If disabled, this page will not appear in Google (noindex).</p>
            </div>

            <label class="relative inline-flex cursor-pointer items-center">
                <input type="hidden" name="index" value="0" />
                <input
                    type="checkbox"
                    name="index"
                    value="1"
                    class="peer sr-only"
                    {{ old('index', $seo->index ?? true) ? 'checked' : '' }}
                />
                <div
                    class="peer h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-blue-600 after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"
                ></div>
            </label>
        </div>
    </div>

    <div
        x-show="activeTab === 'og'"
        x-cloak
        x-data="{
            preview:
                '{{ isset($seo) && $seo->og_image ? asset('storage/' . $seo->og_image) : '' }}',
            removeOgImage: false,
            handleFileChange(event) {
                const file = event.target.files[0]
                if (file) {
                    this.preview = URL.createObjectURL(file)
                    this.removeOgImage = false
                }
            },
            clearPreview() {
                this.preview = ''
                this.removeOgImage = true
                this.$refs.ogFile.value = ''
            },
        }"
        class="space-y-5"
    >
        <div>
            <x-admin.form-label label="OG / Social Share Image" />
            <p class="mb-3 text-xs text-slate-500 dark:text-slate-400">
                Recommended size: 1200 × 630 px. Used when the page is shared on social media.
            </p>

            <input type="file" name="og_image" accept="image/*" class="hidden" x-ref="ogFile" x-on:change="handleFileChange" />
            <input type="hidden" name="remove_og_image" :value="removeOgImage ? 1 : 0" />

            <div
                x-on:click="$refs.ogFile.click()"
                class="group hover:border-primary-400 hover:bg-primary-50/30 dark:hover:border-primary-500 relative flex cursor-pointer flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition-colors dark:border-slate-700 dark:bg-slate-800/50"
                style="min-height: 200px"
            >
                <template x-if="preview">
                    <img :src="preview" class="h-full w-full object-contain" style="max-height: 300px" />
                </template>

                <template x-if="!preview">
                    <div class="flex flex-col items-center gap-2 p-8 text-center text-slate-400">
                        <x-icons.gallery class="h-12 w-12" />

                        <p class="text-sm font-medium">Click to upload OG image</p>
                        <p class="text-xs">JPG, PNG, WebP supported</p>
                    </div>
                </template>

                <template x-if="preview">
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                        <span class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm">Change Image</span>
                    </div>
                </template>
            </div>

            <template x-if="preview">
                <button
                    type="button"
                    x-on:click="clearPreview()"
                    class="mt-2 inline-flex cursor-pointer items-center gap-1.5 text-sm text-red-500 hover:text-red-600"
                >
                    <x-icons.delete class="w- h-4" />
                    Remove image
                </button>
            </template>

            <x-admin.form-error for="og_image" />
        </div>
    </div>

    <div x-show="activeTab === 'schema'" x-cloak class="space-y-5">
        <div>
            <x-admin.form-label for="schema" label="JSON-LD Schema Markup" />
            <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">
                Paste valid JSON-LD structured data (e.g. Organization, Article, BreadcrumbList).
            </p>
            <x-admin.form-textarea
                name="schema"
                id="schema"
                rows="14"
                placeholder='<script>{
                    "@context": "https://schema.org",
                    "@type": "Organization",
                    "name": "Your Company",
                    "url": "https://example.com"
                }</script>'
                class="font-mono text-sm"
            >
                {{ old('schema', $seo->schema ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="schema" />
        </div>
    </div>

    <div x-show="activeTab === 'scripts'" x-cloak class="space-y-5">
        <div>
            <x-admin.form-label for="header_scripts" label="Header Scripts" />
            <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">
                Scripts injected inside
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;head&gt;</code>
                (e.g. analytics, tag manager).
            </p>
            <x-admin.form-textarea
                name="header_scripts"
                id="header_scripts"
                rows="6"
                placeholder="<!-- e.g. Google Tag Manager -->&#10;<script>...</script?>"
                class="font-mono text-sm"
            >
                {{ old('header_scripts', $seo->header_scripts ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="header_scripts" />
        </div>

        <div class="border-t border-slate-100 pt-5 dark:border-slate-800">
            <x-admin.form-label for="footer_scripts" label="Footer Scripts" />
            <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">
                Scripts injected before
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;/body&gt;</code>
                .
            </p>
            <x-admin.form-textarea
                name="footer_scripts"
                id="footer_scripts"
                rows="6"
                placeholder="<!-- e.g. chat widget, heatmap -->&#10;<script>...</script>"
                class="font-mono text-sm"
            >
                {{ old('footer_scripts', $seo->footer_scripts ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="footer_scripts" />
        </div>

        <div class="border-t border-slate-100 pt-5 dark:border-slate-800">
            <x-admin.form-label for="custom_css" label="Custom CSS" />
            <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">
                Page-specific CSS injected in the head. Wrap in
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;style&gt;</code>
                tags or write raw CSS.
            </p>
            <x-admin.form-textarea name="custom_css" id="custom_css" rows="6" placeholder=".hero { background: red; }" class="font-mono text-sm">
                {{ old('custom_css', $seo->custom_css ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="custom_css" />
        </div>
    </div>

    <div x-show="activeTab === 'faqs'" x-cloak class="space-y-4">
        <input type="hidden" name="faqs" :value="faqsJson" />

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">FAQ Items</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Added FAQs will be included in the page's structured FAQ schema.</p>
            </div>
        </div>

        <div class="space-y-3">
            <template x-for="(faq, index) in faqs" :key="index">
                <div class="relative rounded-xl border border-slate-200 bg-slate-50/60 p-4 dark:border-slate-700 dark:bg-slate-800/40">
                    <button
                        type="button"
                        x-on:click="removeFaq(index)"
                        class="absolute top-3 right-3 flex h-6 w-6 cursor-pointer items-center justify-center rounded-full text-slate-400 transition-colors hover:bg-red-100 hover:text-red-500 dark:hover:bg-red-900/30"
                        title="Remove FAQ"
                    >
                        <x-icons.close class="h-4 w-4" />
                    </button>

                    <div class="space-y-3 pr-8">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">
                                Question
                                <span x-text="'#' + (index + 1)"></span>
                            </label>
                            <input
                                type="text"
                                x-model="faq.question"
                                placeholder="What is your question?"
                                class="focus:border-primary-400 focus:ring-primary-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:ring-2 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Answer</label>
                            <textarea
                                x-model="faq.answer"
                                rows="3"
                                placeholder="Provide a clear, concise answer..."
                                class="focus:border-primary-400 focus:ring-primary-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:ring-2 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="faqs.length === 0">
                <div
                    class="flex flex-col items-center gap-2 rounded-xl border-2 border-dashed border-slate-200 py-10 text-center text-slate-400 dark:border-slate-700"
                >
                    <x-icons.faq class="h-10 w-10" />
                    <p class="text-sm">No FAQs added yet.</p>
                    <button
                        type="button"
                        x-on:click="addFaq()"
                        class="text-primary-600 hover:text-primary-700 dark:text-primary-400 cursor-pointer text-sm underline underline-offset-2"
                    >
                        Add your first FAQ
                    </button>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-center">
            <button
                type="button"
                x-on:click="addFaq()"
                class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-slate-300 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-900 transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add FAQ
            </button>
        </div>
    </div>
</div>
