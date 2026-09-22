@php
    // Lists may come back from a failed submit as arrays (after normalising) or as the raw text.
    $tags = old('tags', $project->tags ?? []);
    $tags = is_array($tags) ? implode(', ', $tags) : (string) $tags;
    $results = old('results', $project->results ?? []);
    $results = is_array($results)
        ? collect($results)
            ->map(fn ($r) => trim(($r['value'] ?? '') . ' | ' . ($r['label'] ?? ''), ' |'))
            ->implode("\n")
        : (string) $results;
@endphp

<div
    x-data="projectForm({
                title: @js(old('title', $project->title ?? '')),
                slug: @js(old('slug', $project->slug ?? '')),
            })"
>
    <x-admin.form-grid>
        <x-admin.card title="Project details" text="The name, web address and a short summary." icon="briefcase">
            <div class="space-y-5">
                <div class="space-y-5">
                    <x-admin.form.input
                        name="title"
                        label="Project title"
                        required
                        x-model="title"
                        :value="$project->title ?? ''"
                        placeholder="e.g. Orion Systems"
                    >
                        <x-slot:leftIcon>
                            <x-admin.icon name="layers" class="h-4 w-4" />
                        </x-slot>
                    </x-admin.form.input>

                    <div class="min-w-0">
                        <x-admin.form.input
                            name="slug"
                            label="Slug"
                            required
                            x-model="slug"
                            x-on:input="onSlugInput"
                            :value="$project->slug ?? ''"
                            placeholder="e.g. orion-systems"
                        >
                            <x-slot:leftIcon>
                                <x-admin.icon name="link" class="h-4 w-4" />
                            </x-slot>
                        </x-admin.form.input>
                        <p class="mt-1.5 flex min-w-0 items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <x-admin.icon name="globe" class="h-3.5 w-3.5 shrink-0" />
                            <span class="shrink-0">Public URL:</span>
                            <span class="truncate font-mono text-slate-700 dark:text-slate-300">
                                {{ url('/work') }}/
                                <span x-text="slug || 'your-slug'"></span>
                            </span>
                        </p>
                    </div>
                </div>

                <x-admin.form.textarea
                    name="excerpt"
                    label="Short summary"
                    rows="2"
                    :value="$project->excerpt ?? ''"
                    placeholder="One or two sentences shown on project cards and under the title."
                />
            </div>
        </x-admin.card>

        <x-admin.card title="Client" text="Who the work was for and when." icon="building">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <x-admin.form.input name="client" label="Client" :value="$project->client ?? ''" placeholder="e.g. Orion Systems Inc." />
                <x-admin.form.input name="industry" label="Industry" :value="$project->industry ?? ''" placeholder="e.g. Enterprise SaaS" />
                <x-admin.form.input
                    name="year"
                    label="Year"
                    inputmode="numeric"
                    maxlength="4"
                    :value="$project->year ?? ''"
                    placeholder="e.g. 2026"
                />
            </div>
        </x-admin.card>

        <x-admin.card title="Results and stack" text="Headline numbers, technologies and the live site." icon="trending-up">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.form.textarea
                    name="results"
                    label="Key results"
                    rows="4"
                    :value="$results"
                    hint="Up to 4 lines, written as value | label. Shown as big numbers on the project page."
                    placeholder="One per line as value | label, e.g.&#10;64% | lower cloud spend&#10;24,000 | nodes monitored"
                />

                <div class="space-y-5">
                    <x-admin.form.input
                        name="tags"
                        label="Technologies"
                        :value="$tags"
                        hint="Separate with commas."
                        placeholder="e.g. Next.js, Go, PostgreSQL"
                    >
                        <x-slot:leftIcon>
                            <x-admin.icon name="code" class="h-4 w-4" />
                        </x-slot>
                    </x-admin.form.input>

                    <x-admin.form.input
                        type="url"
                        name="project_url"
                        label="Live project URL"
                        :value="$project->project_url ?? ''"
                        placeholder="https://"
                    >
                        <x-slot:leftIcon>
                            <x-admin.icon name="external-link" class="h-4 w-4" />
                        </x-slot>
                    </x-admin.form.input>
                </div>
            </div>
        </x-admin.card>

        <x-admin.card title="Case study" text="The main article on the project page." icon="file-text">
            <x-admin.form.textarea
                name="content"
                label="Case study"
                required
                rows="14"
                editor
                :value="$project->content ?? ''"
                hint="Shown as the main article on the project page. Use Heading 2 for section titles, e.g. The Challenge, Our Approach, The Outcome."
                placeholder="Tell the story: the challenge, what you built and the outcome. Use Heading 2 for each section."
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
                        :value="isset($project) ? $project->status->value : \App\Enums\CommonStatusEnum::ACTIVE->value"
                    />

                    <x-admin.form.input
                        type="number"
                        name="sort_order"
                        label="Display order"
                        min="0"
                        :value="$project->sort_order ?? ''"
                        placeholder="Auto"
                        hint="Lower numbers show first."
                    >
                        <x-slot:leftIcon>
                            <x-admin.icon name="hash" class="h-4 w-4" />
                        </x-slot>
                    </x-admin.form.input>

                    <div>
                        <x-admin.form.label for="is_featured">Home page</x-admin.form.label>
                        <div
                            class="flex min-h-9.5 items-center rounded-lg border border-slate-200 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-800"
                        >
                            <x-admin.form.toggle
                                name="is_featured"
                                size="sm"
                                label="Feature on the home page"
                                :checked="$project->is_featured ?? false"
                            />
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Service" text="The service this work belongs to." icon="briefcase">
                <x-admin.form.select
                    name="service_id"
                    label="Service"
                    :options="['' => 'No service'] + collect($services)->all()"
                    :value="$project->service_id ?? ''"
                />
            </x-admin.card>

            <x-admin.card title="Project image" icon="image">
                <x-admin.image-upload
                    name="featured_image"
                    preset="project"
                    label="Project image"
                    :current="isset($project) && $project->featured_image ? asset('storage/' . $project->featured_image) : null"
                />
            </x-admin.card>
        </x-slot>
    </x-admin.form-grid>
</div>

@push('scripts')
    <script defer>
        function projectForm(initial) {
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
