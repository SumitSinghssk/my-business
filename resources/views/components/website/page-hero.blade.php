{{--
    Page hero shared by home, about, contact, services and the service / project detail pages:
    text on the left (eyebrow or back link, h1, intro, buttons) and an image on the right.
    
    Props:  eyebrow    small label above the title
    back       ['url' => …, 'label' => …] back link instead of the eyebrow
    titleClass font sizes for the h1 (defaults to the large home/about size)
    Slots:  title (required), text, actions (buttons), media (right column),
    before-title (extra line under the eyebrow), default slot (content below the grid)
--}}

@props([
    'eyebrow' => null,
    'back' => null,
    'titleClass' => 'text-[38px] sm:text-[60px] lg:text-[56px] xl:text-[68px] 2xl:text-[74px]',
])

<section {{ $attributes->class('border-line w-full border-b pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20') }}>
    <div class="site-container">
        <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
            <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                @if ($back)
                    <a
                        href="{{ $back['url'] }}"
                        class="font-label-sm text-label-sm text-primary hover:text-primary-container mb-4 inline-flex items-center gap-1.5 self-start font-semibold tracking-widest uppercase transition-colors"
                    >
                        <span aria-hidden="true">←</span>
                        {{ $back['label'] }}
                    </a>
                @elseif ($eyebrow)
                    <span class="font-label-sm text-label-sm text-primary mb-4 font-semibold tracking-widest uppercase">{{ $eyebrow }}</span>
                @endif

                {{ $beforeTitle ?? '' }}

                <h1 class="sm:mb-space-lg font-display {{ $titleClass }} text-ink mb-5 leading-[1.05] font-semibold tracking-[-0.04em]">
                    {{ $title }}
                </h1>

                @if (isset($text) && $text->hasActualContent())
                    <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">{{ $text }}</p>
                @endif

                @if (isset($actions) && $actions->hasActualContent())
                    <div class="sm:gap-space-md flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        {{ $actions }}
                    </div>
                @endif
            </div>

            {{ $media ?? '' }}
        </div>

        {{ $slot }}
    </div>
</section>
