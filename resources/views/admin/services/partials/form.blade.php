@php
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
>
    <x-admin.form-grid>
        <x-admin.card title="Service details" text="The name, web address and a short summary." icon="briefcase">
            <div class="space-y-5">
                <div class="space-y-5">
                    <x-admin.form.input
                        name="title"
                        label="Service title"
                        required
                        x-model="title"
                        :value="$service->title ?? ''"
                        placeholder="e.g. Website Development"
                    >
                        <x-slot:leftIcon>
                            <x-admin.icon name="monitor" class="h-4 w-4" />
                        </x-slot>
                    </x-admin.form.input>

                    <div class="min-w-0">
                        <x-admin.form.input
                            name="slug"
                            label="Slug"
                            required
                            x-model="slug"
                            x-on:input="onSlugInput"
                            :value="$service->slug ?? ''"
                            placeholder="e.g. website-development"
                        >
                            <x-slot:leftIcon>
                                <x-admin.icon name="link" class="h-4 w-4" />
                            </x-slot>
                        </x-admin.form.input>
                        <p class="mt-1.5 flex min-w-0 items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <x-admin.icon name="globe" class="h-3.5 w-3.5 shrink-0" />
                            <span class="shrink-0">Public URL:</span>
                            <span class="truncate font-mono text-slate-700 dark:text-slate-300">
                                {{ url('/services') }}/
                                <span x-text="slug || 'your-slug'"></span>
                            </span>
                        </p>
                    </div>
                </div>

                <x-admin.form.textarea
                    name="excerpt"
                    label="Short summary"
                    rows="2"
                    :value="$service->excerpt ?? ''"
                    placeholder="One or two sentences shown on the services page and under the title."
                />
            </div>
        </x-admin.card>

        <x-admin.card title="What's included" text="The checklist and technologies shown on the service page." icon="list">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.form.textarea
                    name="highlights"
                    label="What's included"
                    rows="5"
                    :value="$highlights"
                    hint="One item per line. Shown as a checklist."
                    placeholder="One item per line, e.g.&#10;Marketing sites & landing pages&#10;Core Web Vitals & technical SEO"
                />

                <x-admin.form.input
                    name="tags"
                    label="Technologies"
                    :value="$tags"
                    hint="Separate with commas."
                    placeholder="e.g. Laravel, Next.js, Tailwind CSS"
                >
                    <x-slot:leftIcon>
                        <x-admin.icon name="code" class="h-4 w-4" />
                    </x-slot>
                </x-admin.form.input>
            </div>
        </x-admin.card>

        <x-admin.card title="Detail page" text="The main article on the service page." icon="file-text">
            <x-admin.form.textarea
                name="content"
                label="Detail page content"
                required
                rows="14"
                editor
                :value="$service->content ?? ''"
                hint="Shown as the main article on the service page. Use Heading 2 for section titles."
                placeholder="Write the service page. Start with a short introduction, then use Heading 2 for each section."
            />
        </x-admin.card>

        <x-slot:aside>
            <x-admin.card title="Visibility" icon="eye">
                <div class="space-y-5">
                    <x-admin.form.select
                        name="status"
                        label="Status"
                        required
                        :options="\App\Enums\CommonStatusEnum::dotOptions()"
                        :value="isset($service) ? $service->status->value : \App\Enums\CommonStatusEnum::ACTIVE->value"
                    />

                    <x-admin.form.input
                        type="number"
                        name="sort_order"
                        label="Display order"
                        min="0"
                        :value="$service->sort_order ?? ''"
                        placeholder="Auto"
                        hint="Lower numbers show first."
                    >
                        <x-slot:leftIcon>
                            <x-admin.icon name="hash" class="h-4 w-4" />
                        </x-slot>
                    </x-admin.form.input>
                </div>
            </x-admin.card>

            <x-admin.card title="Service image" icon="image">
                <x-admin.image-upload
                    name="featured_image"
                    preset="service"
                    label="Service image"
                    :current="isset($service) && $service->featured_image ? asset('storage/' . $service->featured_image) : null"
                />
            </x-admin.card>
        </x-slot>
    </x-admin.form-grid>
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
