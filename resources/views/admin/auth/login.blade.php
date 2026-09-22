@php
    $appName = \App\Helpers\Settings::appName();
    $logo = \App\Helpers\Settings::logoLight();
@endphp

<x-admin>
    <div class="grid min-h-screen lg:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)]">
        {{-- Sign-in form --}}
        <div class="flex flex-col px-5 py-8 sm:px-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 self-start">
                @if ($logo)
                    <img src="{{ $logo }}" alt="" class="h-9 w-9 rounded-lg object-contain" />
                @else
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-linear-to-br from-blue-500 to-blue-700 text-base font-bold text-white shadow-sm ring-1 shadow-blue-600/30 ring-white/20 ring-inset"
                    >
                        {{ strtoupper(mb_substr($appName, 0, 1)) }}
                    </span>
                @endif
                <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $appName }}</span>
            </a>

            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-sm">
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300"
                    >
                        <x-admin.icon name="lock" class="h-5 w-5" />
                    </span>
                    <h1 class="mt-5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Sign in to your workspace</h1>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                        Welcome back. Enter your details to open the {{ $appName }} admin.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('admin.login.store') }}"
                        class="mt-8 space-y-5"
                        x-data="{ submitting: false }"
                        x-on:submit="submitting = true"
                    >
                        @csrf

                        <x-admin.form.input
                            type="email"
                            name="email"
                            label="Email address"
                            placeholder="name@company.com"
                            autocomplete="email"
                            required
                            autofocus
                            class="h-10"
                        >
                            <x-slot:leftIcon>
                                <x-admin.icon name="mail" class="h-4 w-4" />
                            </x-slot>
                        </x-admin.form.input>

                        <x-admin.form.input
                            type="password"
                            name="password"
                            label="Password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            class="h-10"
                        >
                            <x-slot:leftIcon>
                                <x-admin.icon name="key" class="h-4 w-4" />
                            </x-slot>
                        </x-admin.form.input>

                        <x-admin.button full size="lg">
                            <span x-text="submitting ? 'Signing in…' : 'Sign in'">Sign in</span>
                            <x-slot:rightIcon>
                                <x-admin.icon name="arrow-right" class="h-4 w-4" x-show="! submitting" />
                            </x-slot>
                        </x-admin.button>
                    </form>

                    <p class="mt-6 flex items-center gap-2 text-xs text-slate-400">
                        <x-admin.icon name="shield-check" class="h-4 w-4 text-emerald-500" />
                        Secured sign-in. Repeated failed attempts are temporarily blocked.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>© {{ date('Y') }} {{ $appName }}</span>
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 font-medium transition hover:text-blue-600 dark:hover:text-blue-300"
                >
                    <x-admin.icon name="arrow-left" class="h-3.5 w-3.5" />
                    Back to website
                </a>
            </div>
        </div>

        {{-- Brand panel (desktop) --}}
        <div class="relative hidden overflow-hidden bg-white p-3 lg:block dark:bg-slate-950">
            <div
                class="relative flex h-full flex-col justify-between overflow-hidden rounded-2xl bg-linear-to-br from-blue-600 via-blue-700 to-slate-900 p-12"
            >
                {{-- Grid pattern + glow --}}
                <div
                    class="pointer-events-none absolute inset-0 opacity-[0.15]"
                    style="
                        background-image:
                            linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px);
                        background-size: 44px 44px;
                        mask-image: radial-gradient(ellipse at top right, black 20%, transparent 70%);
                    "
                ></div>
                <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-blue-400/30 blur-3xl"></div>

                <div class="relative">
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-blue-50 ring-1 ring-white/15"
                    >
                        <x-admin.icon name="sparkles" class="h-3.5 w-3.5" />
                        {{ $appName }} admin
                    </span>
                    <h2 class="mt-6 max-w-md text-3xl leading-tight font-semibold tracking-tight text-white">
                        Everything your website needs, in one calm workspace.
                    </h2>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-blue-100/80">
                        Reply to enquiries, publish posts and pages, tune SEO and manage your team, all from one place.
                    </p>
                </div>

                {{-- What the workspace covers --}}
                <ul class="relative mt-10 grid max-w-lg grid-cols-2 gap-3">
                    @foreach ([['inbox', 'Enquiries', 'Every contact form message, tracked'], ['newspaper', 'Blog & pages', 'Write, schedule and publish'], ['globe', 'SEO', 'Titles, schema, sitemap, robots'], ['shield-check', 'Team access', 'Roles, permissions and activity log']] as [$icon, $title, $text])
                        <li class="rounded-xl bg-white/[0.07] p-4 ring-1 ring-white/10 backdrop-blur-sm">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white">
                                <x-admin.icon :name="$icon" class="h-4 w-4" />
                            </span>
                            <p class="mt-3 text-sm font-semibold text-white">{{ $title }}</p>
                            <p class="mt-0.5 text-xs text-blue-100/70">{{ $text }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-admin>
