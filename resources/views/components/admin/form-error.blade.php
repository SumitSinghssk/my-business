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
                <x-icons.exclamation-circle class="h-3.5 w-3.5" />
                <span>{{ $message }}</span>
            </div>
        @endforeach
    </div>
@endif
