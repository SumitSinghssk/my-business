@props(['messages' => $errors->get($attributes->get('for'))])

@if ($messages && count($messages) > 0)
    <div {{ $attributes->merge(['class' => 'mt-2.5 space-y-1']) }}>
        @foreach ($messages as $message)
            <div
                x-data="{ show: false }"
                x-init="setTimeout(() => (show = true), 50)"
                x-show="show"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="-translate-x-1 opacity-0"
                class="flex items-center gap-1.5 text-[13px] font-semibold text-red-600 dark:text-red-400"
            >
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                <span>{{ $message }}</span>
            </div>
        @endforeach
    </div>
@endif
