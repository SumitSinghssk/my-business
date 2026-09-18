<x-admin>
    <div class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-12 dark:bg-slate-950">
        <div class="w-full max-w-sm">
            <div class="border border-slate-200 bg-white p-6 shadow-xs sm:rounded-lg sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                <form
                    method="POST"
                    action="{{ route('admin.login.store') }}"
                    class="space-y-5"
                    x-data="{ submitting: false }"
                    x-on:submit="submitting = true"
                >
                    @csrf

                    <div class="space-y-2">
                        <x-admin.form-label for="email" label="Email" />
                        <x-admin.form-input type="email" name="email" id="email" :value="old('email')" placeholder="name@company.com" required>
                            <x-slot:leftIcon>
                                <x-icons.mail class="h-5 w-5" />
                            </x-slot>
                        </x-admin.form-input>
                    </div>

                    <div class="space-y-2">
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
                                <x-icons.lock class="h-5 w-5" />
                            </x-slot>
                        </x-admin.form-input>
                    </div>

                    <x-admin.form-error for="email" />

                    <x-admin.button full>
                        <span x-text="submitting ? 'Signing in...' : 'Sign In'"></span>
                    </x-admin.button>
                </form>
            </div>

            {{-- Back to home --}}
            <div class="mt-6 text-center">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
                >
                    <x-icons.arrow-left class="h-4 w-4" />
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</x-admin>
