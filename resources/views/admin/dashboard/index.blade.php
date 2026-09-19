@php
    $user = auth('web')->user();

    $tones = [
        'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300',
        'violet' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300',
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300',
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
    ];

    $enquiryStatus = [
        'new' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300',
        'seen' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
        'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        'closed' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
    ];

    // Clean y-axis ticks: round the max up to a "nice" step.
    if ($chart) {
        $step = max(1, (int) ceil($chart['max'] / 4));
        $niceStep = collect([1, 2, 5, 10, 20, 25, 50, 100, 250, 500, 1000])->first(fn ($s) => $s >= $step) ?? $step;
        $yMax = $niceStep * (int) ceil($chart['max'] / $niceStep);
        $ticks = range($yMax, 0, $niceStep);
    }
@endphp

<x-admin :breadcrumb="[]">
    <div class="space-y-4">
        {{-- Greeting + quick actions --}}
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $greeting }}, {{ strtok($user->name, ' ') }} 👋</h1>
                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                    {{ now()->format('l, F j') }} · Here's what's happening with your website.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @can('admin.blogs.create')
                    <a
                        href="{{ route('admin.blogs.create') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm shadow-blue-600/25 transition hover:bg-blue-700"
                    >
                        <x-icons.add class="h-3.5 w-3.5" />
                        New post
                    </a>
                @endcan

                @can('admin.pages.create')
                    <a
                        href="{{ route('admin.pages.create') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                    >
                        <x-icons.pages class="h-3.5 w-3.5" />
                        New page
                    </a>
                @endcan
            </div>
        </div>

        {{-- Stat tiles --}}
        @if (count($stats))
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <a
                        href="{{ $stat['url'] }}"
                        class="group rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-500/30"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <span class="{{ $tones[$stat['tone']] }} flex h-9 w-9 items-center justify-center rounded-lg">
                                <x-dynamic-component :component="'icons.' . $stat['icon']" class="h-4.5 w-4.5" />
                            </span>
                            @if (! is_null($stat['delta']))
                                <span
                                    class="{{ $stat['delta'] >= 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-300' }} rounded-full px-1.5 py-0.5 text-[11px] font-semibold"
                                >
                                    {{ $stat['delta'] >= 0 ? '▲' : '▼' }} {{ abs($stat['delta']) }}%
                                </span>
                            @endif
                        </div>
                        <p class="mt-3 text-2xl font-bold text-slate-900 tabular-nums dark:text-white">{{ number_format($stat['value']) }}</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-300">{{ $stat['label'] }}</p>
                        <p class="mt-0.5 text-[11px] text-slate-400">{{ $stat['hint'] }}</p>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            {{-- Enquiries chart --}}
            @if ($chart)
                <div
                    class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs sm:p-5 xl:col-span-2 dark:border-slate-800 dark:bg-slate-900"
                >
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Enquiries · last 14 days</h2>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                @if ($chart['busiest'])
                                    Busiest day: {{ $chart['busiest']['label'] }} ({{ $chart['busiest']['count'] }})
                                @else
                                    New contact form messages will appear here.
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-slate-900 tabular-nums dark:text-white">{{ number_format($chart['total']) }}</p>

                            @if (! is_null($chart['delta']))
                                <p
                                    class="{{ $chart['delta'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} text-[11px] font-semibold"
                                >
                                    {{ $chart['delta'] >= 0 ? '▲' : '▼' }} {{ abs($chart['delta']) }}% vs previous 14 days
                                </p>
                            @else
                                <p class="text-[11px] text-slate-400">total enquiries</p>
                            @endif
                        </div>
                    </div>

                    <div x-data="{ hover: null }" class="relative mt-4 flex h-52 gap-2">
                        {{-- Y axis --}}
                        <div
                            class="flex w-6 shrink-0 flex-col justify-between pb-5 text-right text-[10px] text-slate-400 tabular-nums"
                            aria-hidden="true"
                        >
                            @foreach ($ticks as $tick)
                                <span class="-translate-y-1/2 leading-none">{{ $tick }}</span>
                            @endforeach
                        </div>

                        <div class="relative flex-1">
                            {{-- Gridlines --}}
                            <div class="pointer-events-none absolute inset-x-0 top-0 bottom-5 flex flex-col justify-between" aria-hidden="true">
                                @foreach ($ticks as $tick)
                                    <div class="h-px bg-slate-100 dark:bg-slate-800"></div>
                                @endforeach
                            </div>

                            {{-- Bars --}}
                            <div class="absolute inset-0 flex items-stretch gap-0.5" aria-hidden="true">
                                @foreach ($chart['days'] as $i => $day)
                                    <div
                                        class="flex flex-1 cursor-default flex-col items-center"
                                        x-on:mouseenter="hover = {{ $i }}"
                                        x-on:mouseleave="hover = null"
                                    >
                                        <div
                                            class="relative flex w-full flex-1 items-end justify-center rounded-md transition-colors"
                                            :class="hover === {{ $i }} && 'bg-slate-50 dark:bg-slate-800/50'"
                                        >
                                            <div
                                                class="chart-bar w-full max-w-6 rounded-t-[4px] transition-opacity"
                                                :class="hover !== null && hover !== {{ $i }} && 'opacity-40'"
                                                style="height: {{ $day['count'] ? max(2, ($day['count'] / $yMax) * 100) : 0 }}%"
                                            ></div>

                                            <div
                                                x-show="hover === {{ $i }}"
                                                x-cloak
                                                class="pointer-events-none absolute bottom-full z-10 mb-1.5 rounded-lg bg-slate-900 px-2 py-1 text-center text-[11px] whitespace-nowrap text-white shadow-lg dark:bg-slate-700"
                                            >
                                                <span class="block text-slate-300">{{ $day['weekday'] }}, {{ $day['label'] }}</span>
                                                <span class="font-semibold">{{ $day['count'] }} {{ Str::plural('enquiry', $day['count']) }}</span>
                                            </div>
                                        </div>
                                        <span
                                            class="{{ $loop->last ? 'font-semibold text-slate-700 dark:text-slate-200' : 'text-slate-400' }} mt-1 h-4 text-[10px] tabular-nums"
                                        >
                                            {{ $loop->first || $loop->last || $i % 3 === 0 ? $day['short'] : '' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Accessible data table --}}
                    <table class="sr-only">
                        <caption>Enquiries per day, last 14 days</caption>
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Enquiries</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chart['days'] as $day)
                                <tr>
                                    <td>{{ $day['label'] }}</td>
                                    <td>{{ $day['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Content health --}}
            @if (count($health))
                <div
                    class="{{ $chart ? '' : 'xl:col-span-3' }} rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs sm:p-5 dark:border-slate-800 dark:bg-slate-900"
                >
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Content health</h2>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Small fixes that help SEO and sharing.</p>

                    <ul class="mt-3 space-y-1.5">
                        @foreach ($health as $item)
                            @php
                                $ok = $item['count'] === 0;
                                $neutral = $item['neutral'] ?? false;
                            @endphp

                            <li>
                                <a
                                    href="{{ $item['url'] }}"
                                    class="flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 transition hover:bg-slate-50 dark:hover:bg-slate-800/60"
                                >
                                    <span class="flex min-w-0 items-center gap-2 text-xs text-slate-600 dark:text-slate-300">
                                        <span
                                            class="{{ $ok ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10' : ($neutral ? 'bg-slate-100 text-slate-500 dark:bg-slate-800' : 'bg-amber-50 text-amber-600 dark:bg-amber-500/10') }} flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold"
                                            aria-hidden="true"
                                        >
                                            {{ $ok ? '✓' : ($neutral ? '•' : '!') }}
                                        </span>
                                        <span class="truncate">{{ $item['label'] }}</span>
                                    </span>
                                    <span class="text-xs font-semibold text-slate-900 tabular-nums dark:text-white">
                                        @if ($item['boolean'] ?? false)
                                            {{ $ok ? 'Yes' : 'No' }}
                                        @else
                                            {{ $item['count'] }}
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            {{-- Recent enquiries --}}
            @can('admin.enquiries.view')
                <div class="rounded-xl border border-slate-200/80 bg-white shadow-xs xl:col-span-2 dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Recent enquiries</h2>
                        <a
                            href="{{ route('admin.enquiries.index') }}"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400"
                        >
                            View all →
                        </a>
                    </div>
                    @forelse ($recentEnquiries as $enquiry)
                        @php
                            $name = $enquiry->data['name'] ?? 'Unknown';
                            $initials = collect(preg_split('/\s+/', trim($name)))
                                ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp

                        <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-2.5 last:border-0 dark:border-slate-800">
                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-blue-100 to-violet-100 text-[11px] font-bold text-blue-700 dark:from-blue-500/20 dark:to-violet-500/20 dark:text-blue-200"
                            >
                                {{ $initials }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="flex items-center gap-1.5 truncate text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $name }}
                                    @if ($enquiry->is_unseen)
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500" title="Unread"></span>
                                    @endif
                                </p>
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                    {{ $enquiry->data['email'] ?? '' }}
                                    @if (! empty($enquiry->data['service']))
                                        · {{ $enquiry->data['service'] }}
                                    @endif
                                </p>
                            </div>
                            <span
                                class="{{ $enquiryStatus[$enquiry->status] ?? $enquiryStatus['closed'] }} hidden rounded-full px-2 py-0.5 text-[11px] font-semibold capitalize sm:inline"
                            >
                                {{ $enquiry->status }}
                            </span>
                            <span class="w-16 shrink-0 text-right text-[11px] text-slate-400">
                                {{ $enquiry->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>
                    @empty
                        <div class="px-4 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">No enquiries yet</p>
                            <p class="mt-0.5 text-xs text-slate-500">Messages from the contact form will show up here.</p>
                        </div>
                    @endforelse
                </div>
            @endcan

            {{-- Recent activity --}}
            @can('admin.activity-logs.view')
                <div class="rounded-xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Recent activity</h2>
                        <a
                            href="{{ route('admin.activity-logs.index') }}"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400"
                        >
                            View all →
                        </a>
                    </div>
                    <ol class="px-4 py-2">
                        @forelse ($activity as $log)
                            <li class="relative flex gap-3 pb-3 last:pb-1">
                                @unless ($loop->last)
                                    <span class="absolute top-6 bottom-0 left-[11px] w-px bg-slate-100 dark:bg-slate-800" aria-hidden="true"></span>
                                @endunless

                                <img
                                    src="{{ $log->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=System' }}"
                                    alt=""
                                    class="relative mt-0.5 h-6 w-6 shrink-0 rounded-full object-cover ring-2 ring-white dark:ring-slate-900"
                                />
                                <div class="min-w-0 pt-0.5">
                                    <p class="text-xs text-slate-700 dark:text-slate-300">
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $log->user?->name ?? 'System' }}</span>
                                        {{ Str::limit($log->description ?: trim(str_replace('_', ' ', $log->action) . ' ' . $log->model_name), 70) }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-xs text-slate-500">No activity recorded yet.</li>
                        @endforelse
                    </ol>
                </div>
            @endcan
        </div>

        {{-- Latest posts --}}
        @can('admin.blogs.view')
            <div class="rounded-xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Latest posts</h2>
                    <a href="{{ route('admin.blogs.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        Manage posts →
                    </a>
                </div>
                <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-5 dark:divide-slate-800">
                    @forelse ($recentPosts as $post)
                        @php
                            $isLive = $post->status === \App\Enums\CommonStatusEnum::ACTIVE && (! $post->published_at || $post->published_at->lte(now()));
                            $isScheduled = $post->status === \App\Enums\CommonStatusEnum::ACTIVE && $post->published_at?->isFuture();
                        @endphp

                        <a
                            href="{{ route('admin.blogs.edit', $post) }}"
                            class="group flex gap-3 p-3 transition hover:bg-slate-50 lg:flex-col dark:hover:bg-slate-800/50"
                        >
                            <div class="h-14 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100 lg:h-24 lg:w-full dark:bg-slate-800">
                                @if ($post->featured_image_url)
                                    <img
                                        src="{{ $post->featured_image_url }}"
                                        alt=""
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-slate-300 dark:text-slate-600">
                                        <x-icons.gallery class="h-5 w-5" />
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p
                                    class="line-clamp-2 text-xs font-semibold text-slate-900 group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300"
                                >
                                    {{ $post->title }}
                                </p>
                                <p class="mt-1 flex items-center gap-1.5 text-[11px] text-slate-400">
                                    <span
                                        class="{{ $isLive ? 'bg-emerald-500' : ($isScheduled ? 'bg-amber-500' : 'bg-slate-300') }} h-1.5 w-1.5 rounded-full"
                                    ></span>
                                    {{ $isLive ? 'Live' : ($isScheduled ? 'Scheduled' : 'Draft') }} ·
                                    {{ $post->updated_at->diffForHumans(null, true) }} ago
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full px-4 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">No posts yet</p>
                            @can('admin.blogs.create')
                                <a href="{{ route('admin.blogs.create') }}" class="mt-2 inline-block text-xs font-semibold text-blue-600">
                                    Write your first post →
                                </a>
                            @endcan
                        </div>
                    @endforelse
                </div>
            </div>
        @endcan
    </div>
</x-admin>
