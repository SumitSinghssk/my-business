<div
    x-data="blogCategoryForm({
                preview:
                    '{{ isset($blogCategory) && $blogCategory->image ? asset('storage/' . $blogCategory->image) : '' }}',
                name: `{{ old('name', $blogCategory->name ?? '') }}`,
                slug: `{{ old('slug', $blogCategory->slug ?? '') }}`,
            })"
    class="space-y-8"
>
    <div class="col-span-9 space-y-5">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <x-admin.form-label for="name" label="Category Name" required />
                <x-admin.form-input
                    type="text"
                    name="name"
                    id="name"
                    x-model="name"
                    :value="old('name', $blogCategory->name ?? '')"
                    placeholder="e.g. Technology, Lifestyle"
                    :error="$errors->first('name')"
                >
                    <x-slot:leftIcon>
                        <x-icons.pages class="h-5 w-5" />
                    </x-slot>
                </x-admin.form-input>
                <x-admin.form-error for="name" />
            </div>

            <div>
                <x-admin.form-label for="slug" label="Slug" required />
                <x-admin.form-input
                    type="text"
                    name="slug"
                    id="slug"
                    x-model="slug"
                    x-on:input="onSlugInput"
                    :value="old('slug', $blogCategory->slug ?? '')"
                    placeholder="e.g. technology, life-style"
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
                <x-admin.form-label for="parent_id" label="Parent Category" />
                <select
                    name="parent_id"
                    id="parent_id"
                    class="focus:border-primary-400 focus:ring-primary-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:ring-2 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                >
                    <option value="">— None (Top-level) —</option>
                    @foreach ($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $blogCategory->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                <x-admin.form-error for="parent_id" />
            </div>

            <div>
                <x-admin.form-label for="status" label="Status" required />

                @php
                    use App\Enums\CommonStatusEnum;

                    $status = old('status', $blogCategory->status ?? CommonStatusEnum::ACTIVE->value);
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
        </div>

        <div>
            <x-admin.form-label for="description" label="Description" />
            <x-admin.form-textarea name="description" id="description" rows="3" placeholder="Brief description of this category...">
                {{ old('description', $blogCategory->description ?? '') }}
            </x-admin.form-textarea>
            <x-admin.form-error for="description" />
        </div>
    </div>

    <div class="mb-4 space-y-4">
        <div>
            <x-admin.image-upload
                name="image"
                preset="category"
                label="Category image"
                :current="isset($blogCategory) && $blogCategory->image ? asset('storage/' . $blogCategory->image) : null"
            />
        </div>
    </div>
</div>

<script>
    function blogCategoryForm(initial) {
        return {
            preview: initial.preview || '',
            removeImage: false,

            name: initial.name || '',
            slug: initial.slug || '',
            slugManuallyEdited: false,

            init() {
                this.$watch('name', (value) => {
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
