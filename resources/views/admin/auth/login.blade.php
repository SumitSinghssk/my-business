@php
    $appName = \App\Helpers\Settings::appName();
    $logo = \App\Helpers\Settings::logoLight();
@endphp

<x-admin>
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-50 px-4 py-12 dark:bg-slate-950">
        {{-- Soft background glow --}}
        <div
            class="pointer-events-none absolute -top-32 left-1/2 h-80 w-[36rem] -translate-x-1/2 rounded-full bg-linear-to-r from-blue-200/50 via-violet-200/40 to-blue-100/40 blur-3xl dark:from-blue-500/10 dark:via-violet-500/10 dark:to-transparent"
        ></div>

        <div class="relative w-full max-w-sm">
            {{-- Brand --}}
            <div class="mb-6 text-center">
                @if ($logo)
                    <img src="{{ $logo }}" alt="{{ $appName }}" class="mx-auto h-11 w-11 rounded-xl object-contain" />
                @else
                    <span
                        class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-linear-to-br from-blue-500 to-violet-500 text-lg font-extrabold text-white shadow-lg shadow-blue-500/30"
                    >
                        {{ strtoupper(mb_substr($appName, 0, 1)) }}
                    </span>
                @endif
                <h1 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">Welcome back 👋</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Sign in to the {{ $appName }} admin panel.</p>
            </div>

            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-lg shadow-slate-200/50 sm:p-6 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none"
            >
                <form
                    method="POST"
                    action="{{ route('admin.login.store') }}"
                    class="space-y-4"
                    x-data="{ submitting: false }"
                    x-on:submit="submitting = true"
                >
                    @csrf

                    <div>
                        <x-admin.form-label for="email" label="Email address" />
                        <x-admin.form-input
                            type="email"
                            name="email"
                            id="email"
                            :value="old('email')"
                            placeholder="name@company.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                            <x-slot:leftIcon>
                                <x-icons.mail class="h-4 w-4" />
                            </x-slot>
                        </x-admin.form-input>
                    </div>

                    <div>
                        <x-admin.form-label for="password" label="Password" />
                        <x-admin.form-input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            toggle
                        >
                            <x-slot:leftIcon>
                                <x-icons.lock class="h-4 w-4" />
                            </x-slot>
                        </x-admin.form-input>
                    </div>

                    <x-admin.form-error for="email" />

                    <x-admin.button full>
                        <span x-text="submitting ? 'Signing in…' : 'Sign in'">Sign in</span>
                    </x-admin.button>
                </form>
            </div>

            <div class="mt-5 text-center">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300"
                >
                    <x-icons.arrow-left class="h-3.5 w-3.5" />
                    Back to website
                </a>
            </div>
        </div>
    </div>
</x-admin>
