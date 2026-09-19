@php
    $canUpdate = auth()
        ->user()
        ->can("admin.settings.scripts.update");
@endphp

<form
    @if ($canUpdate)
        action="{{ route("admin.settings.scripts.update") }}"
        method="POST"
    @endif
    x-data="{ submitting: false }"
    x-on:submit="submitting = true"
    class="space-y-12"
>
    @csrf
    @method("POST")

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Header Scripts</h3>
            <p class="text-xs text-slate-500">
                Scripts injected inside the
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;head&gt;</code>
                tag.
            </p>
        </div>

        <div>
            <x-admin.form-label for="header_scripts" label="Header Scripts" />
            <textarea
                name="header_scripts"
                id="header_scripts"
                rows="8"
                @disabled(! $canUpdate)
                placeholder="<!-- Paste your <script> or meta tags here -->"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-900 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none disabled:cursor-not-allowed disabled:opacity-70 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
{{ old("header_scripts", $settings["header_scripts"] ?? "") }}</textarea
            >
            <x-admin.form-error for="header_scripts" />
            <p class="mt-1.5 text-xs text-slate-400">Example: Google Tag Manager, Facebook Pixel initialization scripts.</p>
        </div>
    </section>

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Footer Scripts</h3>
            <p class="text-xs text-slate-500">
                Scripts injected just before the closing
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;/body&gt;</code>
                tag.
            </p>
        </div>

        <div>
            <x-admin.form-label for="footer_scripts" label="Footer Scripts" />
            <textarea
                name="footer_scripts"
                id="footer_scripts"
                rows="8"
                @disabled(! $canUpdate)
                placeholder="<!-- Paste your deferred <script> tags here -->"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-900 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none disabled:cursor-not-allowed disabled:opacity-70 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
{{ old("footer_scripts", $settings["footer_scripts"] ?? "") }}</textarea
            >
            <x-admin.form-error for="footer_scripts" />
            <p class="mt-1.5 text-xs text-slate-400">Example: Chat widgets, lazy-load libraries.</p>
        </div>
    </section>

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Header CSS</h3>
            <p class="text-xs text-slate-500">
                Custom CSS injected inside the
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;head&gt;</code>
                tag.
            </p>
        </div>

        <div>
            <x-admin.form-label for="header_css" label="Header CSS" />
            <textarea
                name="header_css"
                id="header_css"
                rows="8"
                @disabled(! $canUpdate)
                placeholder="/* Your custom CSS here */"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-900 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none disabled:cursor-not-allowed disabled:opacity-70 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
{{ old("header_css", $settings["header_css"] ?? "") }}</textarea
            >
            <x-admin.form-error for="header_css" />
        </div>
    </section>

    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Footer CSS</h3>
            <p class="text-xs text-slate-500">
                Additional CSS injected just before
                <code class="rounded bg-slate-100 px-1 dark:bg-slate-800">&lt;/body&gt;</code>
                .
            </p>
        </div>

        <div>
            <x-admin.form-label for="footer_css" label="Footer CSS" />
            <textarea
                name="footer_css"
                id="footer_css"
                rows="8"
                @disabled(! $canUpdate)
                placeholder="/* Your override CSS here */"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-900 transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none disabled:cursor-not-allowed disabled:opacity-70 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
{{ old("footer_css", $settings["footer_css"] ?? "") }}</textarea
            >
            <x-admin.form-error for="footer_css" />
        </div>
    </section>

    @if ($canUpdate)
        <div
            class="sticky bottom-0 z-10 -mx-4 mt-8 flex items-center justify-between border-t border-slate-100 bg-white/80 p-4 backdrop-blur-md sm:mx-0 dark:border-slate-800 dark:bg-slate-900/80"
        >
            <div class="flex w-full items-center justify-end gap-4">
                <x-admin.button>
                    <span x-text="submitting ? 'Saving…' : 'Save Scripts'">Save Scripts</span>
                </x-admin.button>
            </div>
        </div>
    @endif
</form>
