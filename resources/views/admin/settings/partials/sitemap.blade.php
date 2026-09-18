@php
    $canUpdate = auth()
        ->user()
        ->can('admin.settings.sitemap.update');
    $canView = auth()
        ->user()
        ->can('admin.settings.sitemap.view');
@endphp

<div class="space-y-12">
    <section class="space-y-6">
        <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Current Sitemap Status</h3>
            <p class="text-xs text-slate-500">Overview of your active sitemap file.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Status</p>
                <div class="mt-2 flex items-center gap-2">
                    @if ($sitemapExists ?? false)
                        <span class="inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">Active</span>
                    @else
                        <span class="inline-flex h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span class="text-sm font-medium text-slate-500">Not Generated</span>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Last Generated</p>
                <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ $sitemapInfo['lastGenerated'] ?? 'Never' }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">File Size</p>
                <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ $sitemapInfo['fileSize'] ?? 'N/A' }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total URLs</p>
                <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ $sitemapInfo['totalUrls'] ?? 'N/A' }}
                </p>
            </div>

            @if ($sitemapExists ?? false)
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2 dark:border-slate-700 dark:bg-slate-800/50">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Public URL</p>
                    <a
                        href="{{ url('sitemap.xml') }}"
                        target="_blank"
                        class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
                    >
                        <x-icons.link class="h-4 w-4 shrink-0" />
                        {{ url('sitemap.xml') }}
                    </a>
                </div>
            @endif
        </div>
    </section>

    @if ($canUpdate)
        <section class="space-y-6" x-data="{ submitting: false }">
            <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
                <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Generate New Sitemap</h3>
                <p class="text-xs text-slate-500">Auto-build a sitemap from your site's pages and posts.</p>
            </div>

            <form action="{{ route('admin.settings.sitemap.generate') }}" method="POST" x-on:submit="submitting = true">
                @csrf
                <x-admin.button type="submit" x-bind:disabled="submitting">
                    <span x-show="!submitting" class="flex items-center gap-2">
                        <x-icons.generate class="h-4 w-4" />
                        Generate Sitemap
                    </span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <x-icons.loading class="h-4 w-4 animate-spin" />
                        Generating…
                    </span>
                </x-admin.button>
            </form>
        </section>
    @endif

    @if ($sitemapExists ?? false)
        <section
            class="space-y-6"
            x-data="{
                submitting: false,
                download() {
                    this.submitting = true
                    fetch('{{ route('admin.settings.sitemap.download') }}')
                        .then((res) => {
                            if (! res.ok) throw new Error('Server error')
                            this.submitting = false
                            return res
                                .blob()
                                .then((blob) => ({ blob, filename: 'sitemap.xml' }))
                        })
                        .then(({ blob, filename }) => {
                            const a = document.createElement('a')
                            a.href = URL.createObjectURL(blob)
                            a.download = filename
                            document.body.appendChild(a)
                            a.click()
                            a.remove()
                            URL.revokeObjectURL(a.href)
                        })
                        .catch((err) => {
                            this.submitting = false
                            alert('Download failed: ' + err.message)
                        })
                },
            }"
        >
            <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
                <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Download Sitemap</h3>
                <p class="text-xs text-slate-500">Save a local copy of your current sitemap.xml file.</p>
            </div>

            <button
                type="button"
                x-on:click="download()"
                x-bind:disabled="submitting"
                class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
            >
                <span x-show="!submitting" class="flex items-center gap-2">
                    <x-icons.download class="h-4 w-4" />
                    Download sitemap.xml
                </span>
                <span x-show="submitting" class="flex items-center gap-2">
                    <x-icons.loading class="h-4 w-4 animate-spin" />
                    Downloading…
                </span>
            </button>
        </section>
    @endif

    @if ($canUpdate)
        <section
            class="space-y-6"
            x-data="{
                file: null,
                uploading: false,
                dragover: false,
                setFile(f) {
                    if (f && f.name.endsWith('.xml')) {
                        this.file = f
                    } else if (f) {
                        alert('Only .xml files are accepted.')
                    }
                },
            }"
        >
            <div class="border-b border-slate-100 pb-2 dark:border-slate-800">
                <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Upload Existing Sitemap</h3>
                <p class="text-xs text-slate-500">Replace the current sitemap with your own XML file (max 10 MB).</p>
            </div>

            <form
                action="{{ route('admin.settings.sitemap.upload') }}"
                method="POST"
                enctype="multipart/form-data"
                x-on:submit="uploading = true"
                class="space-y-4"
            >
                @csrf

                <label
                    class="relative flex h-36 w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed transition"
                    :class="dragover ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 bg-slate-50 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:bg-slate-800'"
                    x-on:dragover.prevent="dragover = true"
                    x-on:dragleave.prevent="dragover = false"
                    x-on:drop.prevent="
                        dragover = false
                        setFile($event.dataTransfer.files[0])
                    "
                >
                    <div class="relative z-10 flex w-full flex-col items-center justify-center">
                        <template x-if="!file">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <x-icons.upload class="h-8 w-8 text-slate-400" />
                                <div>
                                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">
                                        <span class="text-blue-600 dark:text-blue-400">Click to upload</span>
                                        or drag & drop
                                    </p>
                                    <p class="text-xs text-slate-400">XML files only · max 10 MB</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="file">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-8 w-8 text-blue-500">
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 4a2 2 0 0 1 2-2h4.586A2 2 0 0 1 12 2.586L15.414 6A2 2 0 0 1 16 7.414V16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4Zm2 6a.75.75 0 0 1 .75-.75h6.5a.75.75 0 0 1 0 1.5h-6.5A.75.75 0 0 1 6 10Zm.75 2.75a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z"
                                    />
                                </svg>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300" x-text="file.name"></p>
                                <button
                                    type="button"
                                    x-on:click.stop.prevent="file = null"
                                    class="cursor-pointer text-xs text-red-500 hover:underline"
                                >
                                    Remove
                                </button>
                            </div>
                        </template>
                    </div>

                    <input
                        type="file"
                        name="sitemap"
                        accept=".xml"
                        class="absolute inset-0 z-0 cursor-pointer opacity-0"
                        x-on:change="setFile($event.target.files[0])"
                    />
                </label>

                @error('sitemap')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror

                <div x-show="file">
                    <x-admin.button type="submit" x-bind:disabled="uploading">
                        <span x-show="!uploading" class="flex items-center gap-2">
                            <x-icons.upload class="h-4 w-4 text-white" />
                            Upload Sitemap
                        </span>
                        <span x-show="uploading" class="flex items-center gap-2">
                            <x-icons.loading class="h-4 w-4 animate-spin" />
                            Uploading…
                        </span>
                    </x-admin.button>
                </div>
            </form>
        </section>
    @endif
</div>
