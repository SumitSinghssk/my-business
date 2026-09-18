@php
    $message = $enquiry->data["message"] ?? null;
    $color = $enquiry->statusColor;
    $fields = collect($enquiry->data ?? [])->except("message");
@endphp

<x-modal name="enquiry-{{ $enquiry->id }}" maxWidth="2xl">
    <div class="dark:bg-slate-900">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
            <div class="min-w-0">
                <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Enquiry Details</h2>

                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-sm bg-slate-100 px-2 py-1 text-xs font-semibold tracking-wide text-slate-600 uppercase dark:bg-slate-700 dark:text-slate-300"
                    >
                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                            />
                        </svg>
                        {{ $enquiry->source }}
                    </span>

                    <span
                        @class([
                            'inline-flex items-center rounded-sm px-2 py-0.5 text-xs font-semibold',
                            'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $color === 'blue',
                            'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300' => $color === 'green',
                            'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' => $color === 'yellow',
                            'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' => $color === 'slate',
                        ])
                    >
                        {{ ucfirst($enquiry->status) }}
                    </span>
                </div>
            </div>

            <button
                type="button"
                x-on:click="$dispatch('close-modal', 'enquiry-{{ $enquiry->id }}')"
                class="-mr-1 flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-md text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
            >
                <x-icons.close class="h-5 w-5" />
            </button>
        </div>

        {{-- Body --}}
        <div class="space-y-6 px-6 py-6">
            @if ($fields->isNotEmpty())
                <dl class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    @foreach ($fields as $key => $value)
                        <div class="min-w-0">
                            <dt class="text-xs font-semibold tracking-wide text-slate-400 uppercase dark:text-slate-500">
                                {{ str_replace(["_", "-"], " ", $key) }}
                            </dt>

                            <dd class="mt-1 text-sm wrap-break-word text-slate-700 dark:text-slate-300">
                                @if (is_array($value))
                                    <span class="font-mono text-xs text-slate-500">{{ json_encode($value) }}</span>
                                @elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_URL))
                                    <a
                                        href="{{ $value }}"
                                        target="_blank"
                                        class="text-slate-900 underline underline-offset-2 hover:text-slate-600 dark:text-slate-200"
                                    >
                                        {{ $value }}
                                    </a>
                                @elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL))
                                    <a
                                        href="mailto:{{ $value }}"
                                        class="text-slate-900 underline underline-offset-2 hover:text-slate-600 dark:text-slate-200"
                                    >
                                        {{ $value }}
                                    </a>
                                @else
                                    {{ is_scalar($value) && $value !== "" ? $value : "—" }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            @endif

            @if ($message)
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                    <p class="mb-1.5 text-xs font-semibold tracking-wide text-slate-400 uppercase dark:text-slate-500">Message</p>
                    <p class="text-sm wrap-break-word whitespace-pre-line text-slate-700 dark:text-slate-300">{{ $message }}</p>
                </div>
            @endif

            @if ($fields->isEmpty() && ! $message)
                <p class="text-sm text-slate-400 italic dark:text-slate-500">No data attached to this enquiry.</p>
            @endif

            @if ($enquiry->source_url)
                <div class="min-w-0">
                    <p class="mb-1 text-xs font-semibold tracking-wide text-slate-400 uppercase dark:text-slate-500">Source URL</p>
                    <a
                        href="{{ $enquiry->source_url }}"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 text-sm wrap-break-word text-slate-700 underline-offset-2 hover:text-slate-900 hover:underline dark:text-slate-300 dark:hover:text-white"
                    >
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                            />
                        </svg>
                        {{ $enquiry->source_url }}
                    </a>
                </div>
            @endif
        </div>

        {{-- Footer meta --}}
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4 text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-800/40 dark:text-slate-400"
        >
            <span>Received {{ $enquiry->created_at->format("d M Y, H:i") }}</span>

            <span>
                @if ($enquiry->seen_at)Seen {{ $enquiry->seen_at->diffForHumans() }}@if ($enquiry->seenBy)by
                    <span class="font-medium text-slate-600 dark:text-slate-300">
                        {{ $enquiry->seenBy->name }}
                    </span>
                @endif
                @else
                    <span class="font-medium text-slate-600 dark:text-slate-300">Not seen yet</span>
                @endif
            </span>
        </div>
    </div>
</x-modal>
