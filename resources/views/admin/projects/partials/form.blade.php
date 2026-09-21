@php
    use App\Enums\CommonStatusEnum;

    $status = old('status', isset($project) ? $project->status->value : CommonStatusEnum::ACTIVE->value);
    // Lists may come back from a failed submit as arrays (after normalising) or as the raw text.
    $tags = old('tags', $project->tags ?? []);
    $tags = is_array($tags) ? implode(', ', $tags) : (string) $tags;
    $results = old('results', $project->results ?? []);
    $results = is_array($results)
        ? collect($results)
            ->map(fn ($r) => trim(($r['value'] ?? '') . ' | ' . ($r['label'] ?? ''), ' |'))
            ->implode("\n")
        : (string) $results;
    $serviceId = (string) old('service_id', $project->service_id ?? '');
@endphp

<div
    x-data="projectForm({
                title: @js(old('title', $project->title ?? '')),
                slug: @js(old('slug', $project->slug ?? '')),
            })"
    class="space-y-6"
>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <x-admin.form-label for="title" label="Project Title" required />
            <x-admin.form-input
                type="text"
                name="title"
                id="title"
                x-model="title"
                :value="old('title', $project->title ?? '')"
                placeholder="e.g. Orion Systems"
                :error="$errors->first('title')"
            >
                <x-slot:leftIcon>
                    <x-icons.gallery class="h-5 w-5" />
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
                :value="old('slug', $project->slug ?? '')"
                placeholder="e.g. orion-systems"
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
                    {{ url('/work') }}/
                    <span x-text="slug || 'your-slug'"></span>
                </span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <x-admin.form-label for="client" label="Client" />
            <x-admin.form-input
                type="text"
                name="client"
                id="client"
                :value="old('client', $project->client ?? '')"
                placeholder="e.g. Orion Systems Inc."
                :error="$errors->first('client')"
            />
            <x-admin.form-error for="client" />
        </div>
        <div>
            <x-admin.form-label for="industry" label="Industry" />
            <x-admin.form-input
                type="text"
                name="industry"
                id="industry"
                :value="old('industry', $project->industry ?? '')"
                placeholder="e.g. Enterprise SaaS"
                :error="$errors->first('industry')"
            />
            <x-admin.form-error for="industry" />
        </div>
        <div>
            <x-admin.form-label for="year" label="Year" />
            <x-admin.form-input
                type="text"
                name="year"
                id="year"
                inputmode="numeric"
                maxlength="4"
                :value="old('year', $project->year ?? '')"
                placeholder="e.g. 2026"
                :error="$errors->first('year')"
            />
            <x-admin.form-error for="year" />
        </div>
        <div>
            <x-admin.form-label for="service_id" label="Service" />
            <x-admin.form-select name="service_id" id="service_id">
                <option value="">No service</option>
                @foreach ($services as $id => $title)
                    <option value="{{ $id }}" @selected($serviceId === (string) $id)>{{ $title }}</option>
                @endforeach
            </x-admin.form-select>
            <x-admin.form-error for="service_id" />
        </div>
    </div>

    <div>
        <x-admin.form-label for="excerpt" label="Short Summary" />
        <x-admin.form-textarea name="excerpt" id="excerpt" rows="2" placeholder="One or two sentences shown on project cards and under the title.">
            {{ old('excerpt', $project->excerpt ?? '') }}
        </x-admin.form-textarea>
        <x-admin.form-error for="excerpt" />
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <x-admin.form-label for="results" label="Key Results" />
            <x-admin.form-textarea
                name="results"
                id="results"
                rows="4"
                placeholder="One per line as value | label, e.g.&#10;64% | lower cloud spend&#10;24,000 | nodes monitored"
            >
                {{ $results }}
            </x-admin.form-textarea>
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                Up to 4 lines, written as
                <span class="font-mono">value | label</span>
                . Shown as big numbers on the project page.
            </p>
            <x-admin.form-error for="results" />
            <x-admin.form-error for="results.*.value" />
            <x-admin.form-error for="results.*.label" />
        </div>

        <div class="space-y-5">
            <div>
                <x-admin.form-label for="tags" label="Technologies" />
                <x-admin.form-input
                    type="text"
                    name="tags"
                    id="tags"
                    :value="$tags"
                    placeholder="e.g. Next.js, Go, PostgreSQL"
                    :error="$errors->first('tags')"
                />
                <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Separate with commas.</p>
                <x-admin.form-error for="tags" />
                <x-admin.form-error for="tags.*" />
            </div>

            <div>
                <x-admin.form-label for="project_url" label="Live Project URL" />
                <x-admin.form-input
                    type="url"
                    name="project_url"
                    id="project_url"
                    :value="old('project_url', $project->project_url ?? '')"
                    placeholder="https://"
                    :error="$errors->first('project_url')"
                />
                <x-admin.form-error for="project_url" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div>
            <x-admin.form-label for="status" label="Status" required />
            <x-admin.form-select name="status" id="status">
                @foreach (CommonStatusEnum::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}" @selected($status === $statusOption->value)>{{ $statusOption->label() }}</option>
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
                :value="old('sort_order', $project->sort_order ?? '')"
                placeholder="Auto"
                :error="$errors->first('sort_order')"
            />
            <x-admin.form-error for="sort_order" />
        </div>

        <div>
            <x-admin.form-label for="is_featured" label="Home Page" />
            <label
                for="is_featured"
                class="flex h-[42px] cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
            >
                <input type="hidden" name="is_featured" value="0" />
                <input
                    type="checkbox"
                    id="is_featured"
                    name="is_featured"
                    value="1"
                    @checked(old('is_featured', $project->is_featured ?? false))
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                />
                Feature on the home page
            </label>
            <x-admin.form-error for="is_featured" />
        </div>
    </div>

    <x-admin.image-upload
        name="featured_image"
        preset="project"
        label="Project image"
        :current="isset($project) && $project->featured_image ? asset('storage/' . $project->featured_image) : null"
    />

    <div>
        <x-admin.form-label for="content" label="Case Study" required />
        <x-admin.form-textarea
            name="content"
            id="content"
            rows="14"
            editor
            placeholder="Tell the story: the challenge, what you built and the outcome. Use Heading 2 for each section."
        >
            {{ old('content', $project->content ?? '') }}
        </x-admin.form-textarea>
        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
            Shown as the main article on the project page. Use
            <strong>Heading 2</strong>
            for section titles, e.g. The Challenge, Our Approach, The Outcome.
        </p>
        <x-admin.form-error for="content" />
    </div>
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
