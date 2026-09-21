@php
    $canDelete = auth()
        ->user()
        ->can("admin.enquiries.delete");
    $canView = auth()
        ->user()
        ->can("admin.enquiries.view");

    $activeFilters = collect(request()->only(["status", "source", "seen", "date_from", "date_to", "search"]))
        ->filter()
        ->count();
@endphp

<x-admin :breadcrumb="[['label' => 'Enquiries', 'url' => route('admin.enquiries.index')]]">
    <x-admin.card title="Enquiries" text="All incoming enquiries from various sources">
        @include("admin.enquiries.partials.filters")

        <x-admin.table :headers="['Contact', 'Source', 'Status', 'Received', '']" :data="$enquiries" emptyMessage="No enquiries found.">
            @foreach ($enquiries as $enquiry)
                @php
                    $data = $enquiry->data ?? [];
                    $name = $data["name"] ?? null;
                    $email = $data["email"] ?? null;
                    $phone = $data["phone"] ?? null;

                    $primary = $name ?: ($email ?: ($phone ?: "Anonymous"));
                    $secondary = $name ? $email : ($email ? $phone : null);

                    $color = $enquiry->statusColor;
                @endphp

                <tr
                    class="{{ $enquiry->is_unseen ? "bg-slate-50/40 dark:bg-slate-800/20" : "" }} transition-colors hover:bg-slate-50/80 dark:hover:bg-slate-800/40"
                >
                    {{-- Contact --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-2 w-2 shrink-0" title="{{ $enquiry->is_unseen ? "Unseen" : "Seen" }}">
                                @if ($enquiry->is_unseen)
                                    <span
                                        class="h-2 w-2 rounded-full bg-slate-800 ring-2 ring-slate-200 dark:bg-slate-200 dark:ring-slate-700"
                                    ></span>
                                @endif
                            </span>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $primary }}</p>
                                @if ($secondary)
                                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $secondary }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Source --}}
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-sm bg-slate-100 px-2 py-1 text-xs font-semibold tracking-wide text-slate-600 uppercase dark:bg-slate-700 dark:text-slate-300"
                        >
                            <x-icons.url class="h-3 w-3 shrink-0" />
                            {{ $enquiry->source }}
                        </span>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <span
                            @class([
                                "inline-flex items-center rounded-sm px-2 py-0.5 text-xs font-semibold",
                                "bg-slate-900 text-white dark:bg-white dark:text-slate-900" =>
                                    $color === "blue",
                                "bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300" =>
                                    $color === "green",
                                "bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300" =>
                                    $color === "yellow",
                                "bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300" =>
                                    $color === "slate",
                            ])
                        >
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </td>

                    {{-- Received --}}
                    <td class="px-6 py-4">
                        <time
                            class="text-xs whitespace-nowrap text-slate-500 dark:text-slate-400"
                            datetime="{{ $enquiry->created_at->toIso8601String() }}"
                            title="{{ $enquiry->created_at->format("d M Y, H:i") }}"
                        >
                            {{ $enquiry->created_at->diffForHumans() }}
                        </time>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            @if ($canView)
                                <x-admin.tooltip text="View details">
                                    <button
                                        type="button"
                                        x-on:click="
                                            $dispatch('open-modal', 'enquiry-{{ $enquiry->id }}')
                                            @if ($enquiry->is_unseen)
                                                axios.patch('{{ route("admin.enquiries.seen", $enquiry) }}').catch(()
                                                =>
                                                {})
                                            @endif
                                        "
                                        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-md border border-slate-200 bg-white text-slate-600 shadow-xs transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 active:scale-90 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white"
                                    >
                                        <x-icons.visibility class="h-4.5 w-4.5" />
                                    </button>
                                </x-admin.tooltip>
                            @endif

                            @if ($canDelete)
                                <x-admin.delete-button size="sm" :route="route('admin.enquiries.destroy', $enquiry)" :id="$enquiry->id" />
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>

    {{-- Detail modals (rendered outside the table so the markup stays valid) --}}
    @foreach ($enquiries as $enquiry)
        @include("admin.enquiries.partials.details-modal", ["enquiry" => $enquiry])
    @endforeach
</x-admin>
