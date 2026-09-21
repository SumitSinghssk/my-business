<div
    x-data="blogForm({
                preview:
                    '{{ isset($blog) && $blog->featured_image ? asset('storage/' . $blog->featured_image) : '' }}',
                title: `{{ old('title', $blog->title ?? '') }}`,
                slug: `{{ old('slug', $blog->slug ?? '') }}`,
            })"
    class="space-y-8"
>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <x-admin.form-label for="title" label="Post Title" required />
            <x-admin.form-input
                type="text"
                name="title"
                id="title"
                x-model="title"
                :value="old('title', $blog->title ?? '')"
                placeholder="e.g. Getting Started with Laravel"
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
                :value="old('slug', $blog->slug ?? '')"
                placeholder="e.g. getting-started-with-laravel"
                :error="$errors->first('slug')"
            >
                <x-slot:leftIcon>
                    <x-icons.url class="h-5 w-5" />
                </x-slot>
            </x-admin.form-input>
            <x-admin.form-error for="slug" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div>
            @php
                use App\Enums\CommonStatusEnum;
                $status = old('status', $blog->status ?? CommonStatusEnum::ACTIVE->value);
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
                :value="old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '')"
                :error="$errors->first('published_at')"
            />
            <x-admin.form-error for="published_at" />
        </div>

        <div>
            <x-admin.form-label for="categories" label="Categories" />
            <x-admin.multi-select-categories :categories="$categories" :selectedCategoryIds="old('category_ids', $selectedCategoryIds ?? [])" />
            <x-admin.form-error for="category_ids" />
        </div>
    </div>

    <div>
        <x-admin.form-label for="excerpt" label="Excerpt" />
        <x-admin.form-input
            type="text"
            name="excerpt"
            id="excerpt"
            :value="old('excerpt', $blog->excerpt ?? '')"
            placeholder="Short summary shown in listings..."
            :error="$errors->first('excerpt')"
        />
        <x-admin.form-error for="excerpt" />
    </div>

    <div class="space-y-4">
        <div>
            <x-admin.image-upload
                name="featured_image"
                preset="blog"
                label="Featured image"
                :current="isset($blog) && $blog->featured_image ? asset('storage/' . $blog->featured_image) : null"
            />
        </div>
    </div>

    <div class="mb-4">
        <x-admin.form-label for="content" label="Content" required />
        <x-admin.form-textarea name="content" id="content" rows="12" editor placeholder="Write your blog post content here...">
            {{ old('content', $blog->content ?? '') }}
        </x-admin.form-textarea>
        <x-admin.form-error for="content" />
    </div>
</div>

@push('scripts')
    <script defer>
        function blogForm(initial) {
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
