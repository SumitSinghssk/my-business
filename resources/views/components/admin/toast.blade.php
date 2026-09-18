<div
    x-data="flashMessages()"
    x-init="init()"
    class="admin-theme pointer-events-none fixed top-5 right-5 z-100 flex w-full max-w-100 flex-col gap-3"
    aria-live="polite"
    aria-label="Notifications"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="translate-x-12 scale-95 opacity-0"
            x-transition:enter-end="translate-x-0 scale-100 opacity-100"
            x-transition:leave="transition duration-200 ease-in"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-12 opacity-0"
            class="group pointer-events-auto relative flex items-start gap-4 overflow-hidden rounded-2xl border border-gray-200/50 bg-white/90 p-4 shadow-[0_8px_30px_rgb(0,0,0,0.12)] backdrop-blur-md dark:border-gray-700/50 dark:bg-gray-900/90"
        >
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md shadow-sm"
                :class="{
                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400': toast.type === 'success',
                    'bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400':       toast.type === 'error',
                    'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400':   toast.type === 'warning',
                    'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400':       toast.type === 'info',
                }"
            >
                <template x-if="toast.type === 'success'">
                    <x-icons.check class="h-6 w-6" />
                </template>
                <template x-if="toast.type === 'error'">
                    <x-icons.close class="h-6 w-6" />
                </template>
                <template x-if="toast.type === 'warning'">
                    <x-icons.warning class="h-6 w-6" />
                </template>
                <template x-if="toast.type === 'info'">
                    <x-icons.info class="h-6 w-6" />
                </template>
            </div>

            <div class="flex-1 pt-0.5">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white" x-text="toast.title"></h3>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-gray-400" x-text="toast.message"></p>

                <template x-if="toast.errors && toast.errors.length">
                    <ul class="mt-2 space-y-1 border-t border-gray-100 pt-2 dark:border-gray-800">
                        <template x-for="err in toast.errors" :key="err">
                            <li class="flex items-center gap-2 text-xs font-medium text-rose-600 dark:text-rose-400">
                                <span class="h-1 w-1 rounded-full bg-rose-500"></span>
                                <span x-text="err"></span>
                            </li>
                        </template>
                    </ul>
                </template>
            </div>

            <button
                x-on:click="remove(toast.id)"
                class="shrink-0 cursor-pointer rounded-lg p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-200"
            >
                <x-icons.close class="h-4 w-4" />
            </button>

            <div class="absolute right-0 bottom-0 left-0 h-0.75 bg-gray-100 dark:bg-gray-800">
                <div
                    class="h-full transition-all ease-linear"
                    :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-rose-500':    toast.type === 'error',
                        'bg-amber-500':   toast.type === 'warning',
                        'bg-blue-500':    toast.type === 'info',
                    }"
                    :style="{ width: toast.progress + '%', transitionDuration: '100ms' }"
                ></div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
    <script defer>
        function flashMessages() {
            return {
                toasts: [],
                duration: 5000,

                init() {
                    if (window.__toastInitialized) return;
                    window.__toastInitialized = true;

                    window.toast = (type, message, title = null, errors = []) => {
                        this.add(type, title ?? this.titleFor(type), message, errors);
                    };

                    @if(session('success')) this.add('success', 'Success', @json(session('success'))); @endif
                    @if(session('error'))   this.add('error', 'Error', @json(session('error'))); @endif
                    @if(session('warning')) this.add('warning', 'Warning', @json(session('warning'))); @endif
                    @if(session('info'))    this.add('info', 'Information', @json(session('info'))); @endif
                    @if($errors->any())     this.add('error', 'Validation Error', 'Please check the form for errors.', @json($errors->all())); @endif
                    window.addEventListener('flash', (e) => {
                        this.add(e.detail.type, e.detail.title ?? this.titleFor(e.detail.type), e.detail.message, e.detail.errors ?? []);
                    });
                },

                titleFor(type) {
                    return { success: 'Success', error: 'System Error', warning: 'Attention', info: 'Information' }[type] ?? 'Notice';
                },

                add(type, title, message, errors = []) {
                    const id = Date.now() + Math.random();
                    this.toasts.push({ id, type, title, message, errors, visible: true, progress: 100 });

                    this.$nextTick(() => {
                        const start = Date.now();
                        const interval = setInterval(() => {
                            const elapsed = Date.now() - start;
                            const toast = this.toasts.find(t => t.id === id);

                            if (!toast) {
                                clearInterval(interval);
                                return;
                            }

                            toast.progress = Math.max(0, 100 - (elapsed / this.duration) * 100);

                            if (elapsed >= this.duration) {
                                clearInterval(interval);
                                this.remove(id);
                            }
                        }, 50);
                    });
                },

                remove(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) {
                        toast.visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                }
            }
        }
    </script>
@endpush
