{{-- Blog post sidebar: author, table of contents, sharing and services. Pinned while the article scrolls. --}}
<aside class="lg:col-span-4">
    <div class="space-y-space-md lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:overflow-y-auto">
        <div class="bg-surface-container-lowest rounded-lg p-4 shadow-sm">
            <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Written by</span>
            <div class="mt-space-md flex items-center gap-3">
                <x-website.avatar :user="$blog->author" size="h-12 w-12" text="font-headline-sm text-headline-sm" class="rounded-full" />
                <div class="min-w-0">
                    <p class="font-headline-sm text-headline-sm text-on-surface truncate font-semibold">{{ $authorName }}</p>
                    @if ($authorPostCount)
                        <p class="font-label-sm text-label-sm text-secondary tracking-wider uppercase">
                            {{ $authorPostCount }} {{ Str::plural('Article', $authorPostCount) }}
                        </p>
                    @endif
                </div>
            </div>
            @if ($blog->author?->bio)
                <p class="mt-space-md font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $blog->author->bio }}</p>
            @endif
        </div>

        @if (count($toc))
            <div class="bg-surface-container-lowest hidden rounded-lg p-4 shadow-sm lg:block">
                <div class="mb-space-sm flex items-center justify-between">
                    <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Table of Contents</span>
                    <span class="font-label-sm text-label-sm text-primary">{{ count($toc) }} {{ Str::plural('Section', count($toc)) }}</span>
                </div>
                @include('website.blog.partials.toc')
            </div>
        @endif

        <div x-data="{ copied: false }" class="bg-surface-container-lowest rounded-lg p-4 shadow-sm">
            <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Share</span>
            <div class="mt-space-sm flex gap-2">
                <button
                    type="button"
                    x-on:click="
                        navigator.clipboard?.writeText(window.location.href)
                        copied = true
                        setTimeout(() => (copied = false), 2000)
                    "
                    class="{{ $shareButton }}"
                >
                    <span x-text="copied ? 'Copied ✓' : 'Copy Link'">Copy Link</span>
                </button>
                <a
                    href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="{{ $shareButton }}"
                    aria-label="Share on X"
                >
                    X
                </a>
                <a
                    href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="{{ $shareButton }}"
                    aria-label="Share on LinkedIn"
                >
                    LinkedIn
                </a>
            </div>
        </div>
    </div>
</aside>
