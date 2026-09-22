{{--
    Image upload with a fixed-ratio cropper. Size/ratio come from config/images.php.
    
    <x-admin.image-upload name="featured_image" preset="blog" :current="$url" label="Featured image" />
    
    Posts: {name} (file), {name}_crop[x|y|width|height] (crop box), {removeName} (1 when removed).
--}}

@props([
    'name',
    'preset',
    'current' => null,
    'label' => null,
    'removeName' => 'remove_image',
    'help' => null,
])

@php
    $spec = \App\Support\ImagePreset::get($preset);
    $config = [
        'current' => $current,
        'width' => $spec->width,
        'height' => $spec->height,
        'minWidth' => $spec->minWidth(),
        'minHeight' => $spec->minHeight(),
    ];
@endphp

<div x-data="imageUpload(@js($config))" {{ $attributes->class('space-y-2') }}>
    <div class="flex flex-wrap items-end justify-between gap-2">
        <x-admin.form.label class="mb-0!">{{ $label ?? $spec->label }}</x-admin.form.label>
        <span
            class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2 py-0.5 text-[11px] font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300"
        >
            <x-admin.icon name="image" class="h-3 w-3" />
            Required: {{ $spec->hint() }}
        </span>
    </div>

    <p class="text-xs text-slate-500 dark:text-slate-400">
        {{ $help ?? 'You will crop the image to this exact shape; it is then resized to ' . $spec->width . ' × ' . $spec->height . ' px automatically.' }}
        Minimum {{ $spec->minWidth() }} × {{ $spec->minHeight() }} px · JPG, PNG, WebP or GIF · max 5 MB.
    </p>

    <input
        type="file"
        name="{{ $name }}"
        accept="image/jpeg,image/png,image/webp,image/gif"
        class="hidden"
        x-ref="input"
        x-on:change="onFileChange"
    />
    <input type="hidden" name="{{ $removeName }}" :value="removed ? 1 : 0" />
    <template x-for="key in ['x', 'y', 'width', 'height']" :key="key">
        <input type="hidden" :name="`{{ $name }}_crop[${key}]`" :value="crop[key]" />
    </template>

    {{-- Preview frame: same aspect ratio as the website --}}
    <div class="max-w-xl">
        <button
            type="button"
            x-on:click="pick()"
            class="{{ $spec->aspect }} group relative block w-full cursor-pointer overflow-hidden rounded-xl border border-dashed border-slate-300 bg-slate-50/60 transition-colors hover:border-blue-400 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-blue-500/50"
            :class="preview && 'border-solid border-slate-200 dark:border-slate-700'"
            aria-label="Choose {{ strtolower($spec->label) }}"
        >
            <template x-if="preview">
                <img :src="preview" alt="" x-on:error="$el.style.visibility = 'hidden'" class="absolute inset-0 h-full w-full object-cover" />
            </template>

            <template x-if="!preview">
                <span class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 p-4 text-center text-slate-400">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-xs dark:border-slate-700 dark:bg-slate-800"
                    >
                        <x-admin.icon name="upload-cloud" class="h-5 w-5" />
                    </span>
                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">Click to upload</span>
                    <span class="text-[11px]">{{ $spec->ratio }} · {{ $spec->width }} × {{ $spec->height }} px</span>
                </span>
            </template>

            <template x-if="preview">
                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition-opacity group-hover:opacity-100">
                    <span class="rounded-lg bg-white/90 px-3 py-1.5 text-xs font-semibold text-slate-800">Change image</span>
                </span>
            </template>
        </button>
    </div>

    <div class="flex flex-wrap items-center gap-3" x-show="preview" x-cloak>
        <button
            type="button"
            x-show="hasFile"
            x-on:click="recrop()"
            class="inline-flex cursor-pointer items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400"
        >
            <x-admin.icon name="pencil" class="h-3.5 w-3.5" />
            Adjust crop
        </button>
        <button
            type="button"
            x-on:click="remove()"
            class="inline-flex cursor-pointer items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-red-600"
        >
            <x-admin.icon name="trash" class="h-3.5 w-3.5" />
            Remove image
        </button>
    </div>

    <p
        x-show="warning"
        x-cloak
        x-text="warning"
        class="rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700 dark:bg-amber-500/10 dark:text-amber-300"
    ></p>
    <p
        x-show="error"
        x-cloak
        x-text="error"
        class="rounded-lg bg-red-50 px-3 py-2 text-xs text-red-600 dark:bg-red-500/10 dark:text-red-300"
    ></p>
    <x-admin.form.error :for="$name" />

    {{-- Crop dialog --}}
    <div
        x-show="cropping"
        x-cloak
        x-transition.opacity
        x-on:keydown.escape.window="cropping && cancelCrop()"
        class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 p-3 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-label="Crop image"
    >
        <div class="flex max-h-full w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Crop {{ strtolower($spec->label) }}</h3>
                    <p class="text-xs text-slate-500">
                        Drag to position, scroll or pinch to zoom. Saved as {{ $spec->width }} × {{ $spec->height }} px ({{ $spec->ratio }}).
                    </p>
                </div>
                <button
                    type="button"
                    x-on:click="cancelCrop()"
                    class="rounded-md p-1 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800"
                    aria-label="Cancel"
                >
                    <x-admin.icon name="x" class="h-4 w-4" />
                </button>
            </div>

            <div class="min-h-0 flex-1 bg-slate-100 dark:bg-slate-950">
                <div class="h-[60vh] max-h-[520px] w-full">
                    <img x-ref="cropImage" alt="" class="block max-w-full" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-100 px-4 py-3 dark:border-slate-800">
                <x-admin.button type="button" variant="secondary" size="sm" x-on:click="cancelCrop()">Cancel</x-admin.button>
                <x-admin.button type="button" size="sm" x-on:click="applyCrop()">Apply crop</x-admin.button>
            </div>
        </div>
    </div>
</div>
