{{-- Dark call-to-action shared by the blog pages. Optional: $ctaTitle, $ctaText --}}
@php
    $email = \App\Helpers\Settings::emails()[0] ?? null;
@endphp

<section id="cta" class="bg-surface-container-low py-space-2xl w-full border-t border-[#E1E5EA]">
    <div class="site-container">
        <div class="p-space-xl md:p-space-2xl relative overflow-hidden rounded-lg border border-[#2A2A2A] bg-[#0A0A0A] text-white">
            <div
                class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#1C1C1C_1px,transparent_1px),linear-gradient(to_bottom,#1C1C1C_1px,transparent_1px)] bg-size-[4rem_4rem] opacity-40"
            ></div>

            <div class="gap-gutter relative z-10 grid grid-cols-1 items-center lg:grid-cols-12">
                <div class="space-y-space-md lg:col-span-7">
                    <div class="gap-space-sm flex items-center">
                        <span class="bg-primary-container h-2 w-2"></span>
                        <span class="font-label-sm text-label-sm tracking-widest text-[#A0A0A0] uppercase">
                            {{ \App\Helpers\Settings::appName() }} Advisory
                        </span>
                    </div>
                    <h2
                        class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg leading-tight font-semibold tracking-tight text-white"
                    >
                        {{ $ctaTitle ?? 'Discuss your architecture with our engineering team.' }}
                    </h2>
                    <p class="font-body-md text-body-md max-w-lg text-[#A0A0A0]">
                        {{ $ctaText ?? 'We help ambitious teams audit systems, migrate high-scale platforms, and build software that holds up under peak traffic.' }}
                    </p>
                </div>

                <div class="gap-space-sm flex flex-col lg:col-span-5 lg:items-end">
                    <a
                        href="{{ route('contact') }}"
                        class="bg-primary-container px-space-xl font-label-md text-label-md hover:bg-primary inline-flex w-full items-center justify-center py-4 tracking-wider text-white uppercase transition-colors lg:w-auto"
                    >
                        Start a Conversation →
                    </a>
                    @if ($email)
                        <a
                            href="mailto:{{ $email }}"
                            class="font-label-sm text-label-sm text-center tracking-wider text-[#A0A0A0] uppercase transition-colors hover:text-white lg:text-right"
                        >
                            {{ $email }}
                        </a>
                    @else
                        <span class="font-label-sm text-label-sm text-outline text-center tracking-wider uppercase lg:text-right">
                            30-min technical discovery call
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
