@props(['breadcrumb' => []])

<x-app>
    @push('heads')
        <meta name="robots" content="noindex, nofollow" />
        <script>
            (() => {
                const theme = localStorage.getItem('admin-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = theme ? theme === 'dark' : prefersDark;

                document.documentElement.classList.toggle('dark', isDark);
                document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
            })();
        </script>
    @endpush

    @push('head-scripts')
        @vite(['resources/css/app.css', 'resources/js/admin.js'])
    @endpush

    @if (request()->is('admin/login'))
        <div class="admin-theme min-h-screen bg-slate-50 dark:bg-slate-950">
            {{ $slot }}
        </div>
    @else
        <div x-data="{ sidebarOpen: false }" class="admin-theme relative min-h-screen bg-slate-50 dark:bg-slate-950">
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-on:click="sidebarOpen = false"
                class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
            ></div>

            @include('layouts.partials.admin.sidebar')

            <div class="flex flex-col transition-all duration-300 lg:pl-64">
                <div class="sticky top-0 z-30">
                    @include('layouts.partials.admin.header')
                    @include('layouts.partials.admin.breadcrumb', ['breadcrumb' => $breadcrumb ?? []])
                </div>

                <main class="min-h-[calc(100vh-120px)] sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @push('scripts')
            <script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
            <style>
                .tox-promotion,
                .tox-statusbar__branding {
                    display: none;
                }
            </style>
            <script defer>
                function initTinyMCE(isDark) {
                    let content = '';

                    if (tinymce.get()) {
                        const editor = tinymce.activeEditor;
                        if (editor) {
                            content = editor.getContent();
                        }
                        tinymce.remove();
                    }

                    tinymce.init({
                        license_key: 'gpl',
                        selector: 'textarea.tinymce',
                        height: 550,
                        convert_urls: false,

                        skin: isDark ? 'oxide-dark' : 'oxide',
                        content_css: isDark ? 'dark' : '{{ Vite::asset('resources/css/app.css') }}',

                        plugins: ['lists', 'code', 'image', 'media', 'link', 'table', 'accordion', 'charmap', 'emoticons', 'advlist'],

                        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist',

                        body_class: 'modern-content p-4',

                        setup: function (editor) {
                            editor.on('init', function () {
                                if (content) {
                                    editor.setContent(content);
                                }
                            });
                        },

                        images_upload_handler: function (blobInfo, progress) {
                            return new Promise((resolve, reject) => {
                                let xhr = new XMLHttpRequest();
                                xhr.open('POST', '{{ route('admin.images.store') }}');
                                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                                xhr.upload.onprogress = function (e) {
                                    progress((e.loaded / e.total) * 100);
                                };

                                xhr.onload = function () {
                                    if (xhr.status === 403) {
                                        reject('CSRF token mismatch.');
                                        return;
                                    }

                                    if (xhr.status < 200 || xhr.status >= 300) {
                                        reject('HTTP Error: ' + xhr.status);
                                        return;
                                    }

                                    let json;
                                    try {
                                        json = JSON.parse(xhr.responseText);
                                    } catch (e) {
                                        reject('Invalid JSON: ' + xhr.responseText);
                                        return;
                                    }

                                    if (!json || typeof json.location !== 'string') {
                                        reject('Invalid response format');
                                        return;
                                    }

                                    resolve(json.location);
                                };

                                xhr.onerror = function () {
                                    reject('Image upload failed.');
                                };

                                let formData = new FormData();
                                formData.append('file', blobInfo.blob(), blobInfo.filename());

                                xhr.send(formData);
                            });
                        },
                    });
                }

                document.addEventListener('DOMContentLoaded', function () {
                    const isDark = document.documentElement.classList.contains('dark');

                    initTinyMCE(isDark);

                    window.addEventListener('admin-theme-changed', function (e) {
                        initTinyMCE(e.detail.isDark);
                    });
                });
            </script>
        @endpush

        <x-admin.toast />
    @endif
</x-app>
