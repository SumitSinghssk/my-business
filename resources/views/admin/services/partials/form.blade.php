@php
    use App\Enums\CommonStatusEnum;

    $status = old('status', isset($service) ? $service->status->value : CommonStatusEnum::ACTIVE->value);
    // Lists may come back from a failed submit as arrays (after normalising) or as the raw text.
    $asText = fn ($value, string $glue) => is_array($value) ? implode($glue, $value) : (string) $value;
    $highlights = $asText(old('highlights', $service->highlights ?? []), "\n");
    $tags = $asText(old('tags', $service->tags ?? []), ', ');
@endphp

<div
    x-data="serviceForm({
                title: @js(old('title', $service->title ?? '')),
                slug: @js(old('slug', $service->slug ?? '')),
            })"
    class="space-y-6"
>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <x-admin.form-label for="title" label="Service Title" required />
            <x-admin.form-input
                type="text"
                name="title"
                id="title"
                x-model="title"
                :value="old('title', $service->title ?? '')"
                placeholder="e.g. Website Development"
                :error="$errors->first('title')"
            >
                <x-slot:leftIcon>
                    <x-icons.desktop class="h-5 w-5" />
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
                :value="old('slug', $service->slug ?? '')"
                placeholder="e.g. website-development"
                :error="$errors->first('slug')"
            >
                <x-slot:leftIcon>
                    <x-icons.url class="h-5 w-5" />
                </x-slot>
            </x-admin.form-input>
            <x-admin.form-error for="slug" />
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                Public URL:
                <span class="font-mono text-slate-700 dark:text-slate-300">
                    {{ url('/services') }}/
                    <span x-text="slug || 'your-slug'"></span>
                </span>
            </p>
        </div>
    </div>

    <div>
        <x-admin.form-label for="excerpt" label="Short Summary" />
        <x-admin.form-textarea
            name="excerpt"
            id="excerpt"
            rows="2"
            placeholder="One or two sentences shown on the services page and under the title."
        >
            {{ old('excerpt', $service->excerpt ?? '') }}
        </x-admin.form-textarea>
        <x-admin.form-error for="excerpt" />
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <x-admin.form-label for="highlights" label="What's Included" />
            <x-admin.form-textarea
                name="highlights"
                id="highlights"
                rows="5"
                placeholder="One item per line, e.g.&#10;Marketing sites & landing pages&#10;Core Web Vitals & technical SEO"
            >
                {{ $highlights }}
            </x-admin.form-textarea>
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">One item per line. Shown as a checklist.</p>
            <x-admin.form-error for="highlights" />
            <x-admin.form-error for="highlights.*" />
        </div>

        <div class="space-y-5">
            <div>
                <x-admin.form-label for="tags" label="Technologies" />
                <x-admin.form-input
                    type="text"
                    name="tags"
                    id="tags"
                    :value="$tags"
                    placeholder="e.g. Laravel, Next.js, Tailwind CSS"
                    :error="$errors->first('tags')"
                />
                <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Separate with commas.</p>
                <x-admin.form-error for="tags" />
                <x-admin.form-error for="tags.*" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
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
                    <x-admin.form-label for="sort_order" label="Display Order" />
                    <x-admin.form-input
                        type="number"
                        name="sort_order"
                        id="sort_order"
                        min="0"
                        :value="old('sort_order', $service->sort_order ?? '')"
                        placeholder="Auto"
                        :error="$errors->first('sort_order')"
                    />
                    <x-admin.form-error for="sort_order" />
                </div>
            </div>
        </div>
    </div>

    <x-admin.image-upload
        name="featured_image"
        preset="service"
        label="Service image"
        :current="isset($service) && $service->featured_image ? asset('storage/' . $service->featured_image) : null"
    />

    <div>
        <x-admin.form-label for="content" label="Detail Page Content" required />
        <x-admin.form-textarea
            name="content"
            id="content"
            rows="14"
            editor
            placeholder="Write the service page. Start with a short introduction, then use Heading 2 for each section."
        >
            {{ old('content', $service->content ?? '') }}
        </x-admin.form-textarea>
        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
            Shown as the main article on the service page. Use
            <strong>Heading 2</strong>
            for section titles.
        </p>
        <x-admin.form-error for="content" />
    </div>
</div>

@push('scripts')
    <script defer>
        function serviceForm(initial) {
            return {
                title: initial.title || '',
                slug: initial.slug || '',
                slugManuallyEdited: Boolean(initial.slug),

                init() {
                    this.$watch('title', (value) => {
                        if (!this.slugManuallyEdited) {
                            this.slug = this.generateSlug(value);
                        }
                    });
                },

                onSlugInput() {
                    this.slugManuallyEdited = this.slug.trim() !== '';
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
