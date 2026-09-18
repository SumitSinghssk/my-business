<div
    x-data="pageForm({
                preview:
                    '{{ isset($page) && $page->featured_image ? asset('storage/' . $page->featured_image) : '' }}',
                title: `{{ old('title', $page->title ?? '') }}`,
                slug: `{{ old('slug', $page->slug ?? '') }}`,
            })"
    class="space-y-8"
>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <x-admin.form-label for="title" label="Page Title" required />
            <x-admin.form-input
                type="text"
                name="title"
                id="title"
                x-model="title"
                :value="old('title', $page->title ?? '')"
                placeholder="e.g. About Us"
                :error="$errors->first('title')"
            >
                <x-slot:leftIcon>
                    <x-icons.pages class="h-5 w-5" />
                </x-slot>
            </x-admin.form-input>
            <x-admin.form-error for="title" />
        </div>

        <div>
            <x-admin.form-label for="slug" label="Slug" required />
            <x-admin.form-input
                type="text"
                name="slug"
                id="slug"
                x-model="slug"
                x-on:input="onSlugInput"
                :value="old('slug', $page->slug ?? '')"
                placeholder="e.g. about-us"
                :error="$errors->first('slug')"
            >
                <x-slot:leftIcon>
                    <x-icons.url class="h-5 w-5" />
                </x-slot>
            </x-admin.form-input>
            <x-admin.form-error for="slug" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            @php
                use App\Enums\CommonStatusEnum;
                $status = old('status', $page->status ?? CommonStatusEnum::ACTIVE->value);
            @endphp

            <x-admin.form-label for="status" label="Status" required />
            <x-admin.form-select name="status" id="status">
                @foreach (CommonStatusEnum::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}" @selected($status === $statusOption->value)>
                        {{ $statusOption->label() }}
                    </option>
                @endforeach
            </x-admin.form-select>
            <x-admin.form-error for="status" />
        </div>

        <div>
            <x-admin.form-label for="published_at" label="Published At" />
            <x-admin.form-input
                type="datetime-local"
                name="published_at"
                id="published_at"
                :value="old('published_at', isset($page) && $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '')"
                :error="$errors->first('published_at')"
            />
            <x-admin.form-error for="published_at" />
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <x-admin.form-label label="Featured Image" />
            <p class="mb-3 text-xs text-slate-500 dark:text-slate-400">Recommended size: 1200 x 630 px. JPG, JPEG, PNG, WebP, GIF</p>

            <input type="file" name="featured_image" accept="image/*" class="hidden" x-ref="imageFile" x-on:change="handleFileChange" />
            <input type="hidden" name="remove_image" :value="removeImage ? 1 : 0" />

            <div
                x-on:click="$refs.imageFile.click()"
                class="group relative flex max-w-2xl cursor-pointer flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition-colors hover:border-blue-400 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-blue-500/40 hover:dark:bg-slate-800/30"
                style="min-height: 180px"
            >
                <template x-if="preview">
                    <img :src="preview" class="h-full w-full object-contain p-2" style="max-height: 300px" />
                </template>

                <template x-if="!preview">
                    <div class="flex flex-col items-center gap-2 p-8 text-center text-slate-400">
                        <x-icons.gallery class="h-10 w-10" />
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Click to upload featured image</p>
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
                    class="mt-3 inline-flex cursor-pointer items-center gap-1.5 text-sm font-medium text-red-500 hover:text-red-600"
                >
                    <x-icons.delete class="h-4 w-4" />
                    Remove image
                </button>
            </template>

            <x-admin.form-error for="featured_image" />
        </div>
    </div>

    <div class="mb-4">
        <x-admin.form-label for="content" label="Content" required />
        <x-admin.form-textarea name="content" id="content" rows="12" editor placeholder="Write your page content here...">
            {{ old('content', $page->content ?? '') }}
        </x-admin.form-textarea>
        <x-admin.form-error for="content" />
    </div>
</div>

@push('scripts')
    <script defer>
        function pageForm(initial) {
            return {
                preview: initial.preview || '',
                removeImage: false,

                title: initial.title || '',
                slug: initial.slug || '',
                slugManuallyEdited: false,

                init() {
                    this.$watch('title', (value) => {
                        if (!this.slugManuallyEdited) {
                            this.slug = this.generateSlug(value);
                        }
                    });
                },

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.preview = URL.createObjectURL(file);
                        this.removeImage = false;
                    }
                },

                clearPreview() {
                    this.preview = '';
                    this.removeImage = true;
                    this.$refs.imageFile.value = '';
                },

                onSlugInput() {
                    this.slugManuallyEdited = true;
                    if (this.slug.trim() === '') {
                        this.slugManuallyEdited = false;
                    }
                },

                generateSlug(value) {
                    return value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                },
            };
        }
    </script>
@endpush
