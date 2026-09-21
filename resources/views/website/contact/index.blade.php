@php
    use App\Helpers\Settings;

    $appName = Settings::appName();
    $emails = array_values(array_filter(Settings::emails()));
    $phones = array_values(array_filter(Settings::phones()));
    $addresses = array_values(array_filter(Settings::addresses(), fn ($a) => filled($a['text'] ?? null)));
    $socialLinks = Settings::socialLinks();
    // Rebuilt from its src so only a Google Maps iframe can ever be output.
    $map = Settings::mapEmbed(collect($addresses)->first(fn ($a) => filled($a['map_iframe'] ?? null))['map_iframe'] ?? null);

    $label = 'font-label-sm text-label-sm text-on-surface-variant mb-1.5 block tracking-wider uppercase';
    $input = 'font-body-md text-body-md text-on-surface placeholder:text-outline focus:border-primary-container focus:ring-primary-container w-full border bg-white px-4 py-3 transition-colors focus:ring-1 focus:outline-none';
    $inputState = fn ($field) => $errors->has($field) ? 'border-error' : 'border-[#E1E5EA]';
    // Previous input as text only: a tampered submit (name[]=x) must not break the form.
    $old = fn ($field) => is_string($value = old($field)) ? $value : '';
    $sectionTitle = 'font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl';

    // Quick-contact cards under the hero, built from Settings (only filled channels are shown).
    $channels = array_values(
        array_filter([
            $emails ? ['label' => 'Email us', 'items' => array_map(fn ($e) => ['text' => $e, 'url' => 'mailto:' . $e], $emails), 'note' => 'Replies within 1 business day'] : null,
            $phones ? ['label' => 'Call us', 'items' => array_map(fn ($p) => ['text' => $p, 'url' => 'tel:' . preg_replace('/[^0-9+]/', '', $p)], $phones), 'note' => 'Mon–Fri, business hours'] : null,
            ...array_map(fn ($a) => ['label' => $a['label'] ?? null ?: 'Visit us', 'items' => [['text' => $a['text'], 'url' => null]], 'note' => null], $addresses),
        ]),
    );

    $steps = [
        ['title' => 'We reply within 1 business day', 'text' => 'A senior engineer, not a sales rep, reads every message and replies personally.'],
        ['title' => '30-minute discovery call', 'text' => 'We talk through goals, constraints and timelines. An NDA is available before we start.'],
        ['title' => 'Proposal & plan', 'text' => 'You receive a scoped plan with milestones, team shape and a transparent estimate.'],
    ];
@endphp

<x-website
    :title="'Contact Us: Start Your Project | ' . $appName"
    description="Tell us about your project. Our engineering team replies within one business day."
    :image="asset('images/website/contact/workspace.jpg')"
>
    <div class="bg-surface text-on-surface flex w-full flex-col">
        {{-- Hero (same rhythm as the home and about heroes) --}}
        <section class="w-full border-b border-[#E1E5EA] pt-8 pb-10 md:pt-12 md:pb-14 lg:pt-16 lg:pb-20">
            <div class="site-container">
                <div class="gap-section grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr] xl:gap-12">
                    <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                        <span class="font-label-sm text-label-sm text-primary mb-4 font-semibold tracking-widest uppercase">
                            Contact {{ $appName }}
                        </span>

                        <h1
                            class="sm:mb-space-lg font-display mb-5 text-[38px] leading-[1.05] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[60px] lg:text-[56px] xl:text-[68px] 2xl:text-[74px]"
                        >
                            Let's build something reliable together.
                        </h1>

                        <p class="font-body-lg text-body-lg text-secondary lg:mb-space-xl mb-7 max-w-xl">
                            Tell us what you're building. Whether it's a new product, a rescue mission or a scaling challenge, we'll help you find the
                            right way forward.
                        </p>

                        <div class="sm:gap-space-md flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                            <a
                                href="#contact-form"
                                class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors sm:w-auto"
                            >
                                Send a Message ↓
                            </a>
                            <a
                                href="{{ $emails ? 'mailto:' . $emails[0] : route('services') }}"
                                class="px-space-xl font-label-md text-label-md inline-flex w-full items-center justify-center border border-[#E1E5EA] bg-white py-4 tracking-wider text-[#0A0A0A] uppercase transition-all hover:border-[#0A0A0A] sm:w-auto"
                            >
                                {{ $emails ? 'Email Us →' : 'Explore Services →' }}
                            </a>
                        </div>
                    </div>

                    <div class="relative aspect-16/10 w-full overflow-hidden rounded-lg lg:aspect-auto lg:min-h-full">
                        <img
                            src="{{ asset('images/website/contact/workspace.webp') }}"
                            width="1600"
                            height="1000"
                            alt="Engineering team collaborating around a shared desk"
                            class="absolute inset-0 h-full w-full object-cover"
                            fetchpriority="high"
                        />
                    </div>
                </div>

                @if ($channels)
                    <div
                        @class([
                            'mt-10 grid grid-cols-1 gap-px overflow-hidden rounded-lg border border-[#E1E5EA] bg-[#E1E5EA] md:mt-14 lg:mt-16',
                            'sm:grid-cols-2' => count($channels) >= 2,
                            'lg:grid-cols-3' => count($channels) >= 3,
                        ])
                    >
                        @foreach ($channels as $channel)
                            <div class="flex min-w-0 flex-col gap-1 bg-white p-4 lg:p-5">
                                <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">{{ $channel['label'] }}</span>
                                @foreach ($channel['items'] as $item)
                                    @if ($item['url'])
                                        <a
                                            href="{{ $item['url'] }}"
                                            class="hover:text-primary-container text-lg font-semibold wrap-break-word text-[#0A0A0A] transition-colors md:text-xl"
                                        >
                                            {{ $item['text'] }}
                                        </a>
                                    @else
                                        <p class="font-body-md text-body-md whitespace-pre-line text-[#0A0A0A]">{{ $item['text'] }}</p>
                                    @endif
                                @endforeach

                                @if ($channel['note'])
                                    <span class="font-body-sm text-body-sm text-secondary">{{ $channel['note'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- Form + what happens next --}}
        <section class="section-y w-full border-b border-[#E1E5EA]">
            <div class="site-container">
                <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
                    <h2 class="{{ $sectionTitle }}">Tell us about your project.</h2>
                    <p class="font-body-md text-body-md text-secondary max-w-2xl">
                        A few details help us bring the right people to the first conversation.
                    </p>
                </div>

                <div class="gap-section grid grid-cols-1 items-start lg:grid-cols-12">
                    <div id="contact-form" class="scroll-mt-24 lg:col-span-7 xl:col-span-8">
                        <div class="rounded-lg border border-[#E1E5EA] bg-white p-4 shadow-sm sm:p-5 lg:p-6">
                            <div class="mb-5 flex items-center justify-between gap-3 border-b border-[#E1E5EA] pb-4">
                                <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Project details</h3>
                                <span class="font-label-sm text-label-sm text-outline shrink-0 tracking-wider uppercase">* Required</span>
                            </div>

                            @if (session('contact_success'))
                                <div class="mb-5 flex items-start gap-3 border border-[#10B981]/30 bg-[#10B981]/10 p-4 text-[#047857]" role="status">
                                    <span class="font-semibold" aria-hidden="true">✓</span>
                                    <p class="font-body-md text-body-md">{{ session('contact_success') }}</p>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div
                                    class="border-error/30 bg-error-container/40 font-body-sm text-body-sm text-on-error-container mb-5 border p-4"
                                    role="alert"
                                >
                                    Please check the highlighted fields and try again.
                                </div>
                            @endif

                            <form
                                method="POST"
                                action="{{ route('contact.store') }}"
                                x-data="{ submitting: false }"
                                x-on:submit="submitting = true"
                                class="flex flex-col gap-4"
                                novalidate
                            >
                                @csrf

                                <div class="hidden" aria-hidden="true">
                                    <label for="website">Website</label>
                                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" />
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="{{ $label }}">Full Name *</label>
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            value="{{ $old('name') }}"
                                            required
                                            autocomplete="name"
                                            class="{{ $input }} {{ $inputState('name') }}"
                                        />
                                        @error('name')
                                            <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="{{ $label }}">Work Email *</label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            value="{{ $old('email') }}"
                                            required
                                            autocomplete="email"
                                            class="{{ $input }} {{ $inputState('email') }}"
                                        />
                                        @error('email')
                                            <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="company" class="{{ $label }}">Company</label>
                                        <input
                                            id="company"
                                            name="company"
                                            type="text"
                                            value="{{ $old('company') }}"
                                            autocomplete="organization"
                                            class="{{ $input }} {{ $inputState('company') }}"
                                        />
                                        @error('company')
                                            <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="{{ $label }}">Phone</label>
                                        <input
                                            id="phone"
                                            name="phone"
                                            type="tel"
                                            value="{{ $old('phone') }}"
                                            autocomplete="tel"
                                            class="{{ $input }} {{ $inputState('phone') }}"
                                        />
                                        @error('phone')
                                            <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="service" class="{{ $label }}">What do you need?</label>
                                        <select id="service" name="service" class="{{ $input }} {{ $inputState('service') }} cursor-pointer">
                                            <option value="">Select a service</option>
                                            @foreach ($services as $value => $text)
                                                <option value="{{ $value }}" @selected(old('service', request()->query('service')) === $value)>
                                                    {{ $text }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service')
                                            <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="message" class="{{ $label }}">Project Details *</label>
                                    <textarea
                                        id="message"
                                        name="message"
                                        rows="6"
                                        required
                                        placeholder="What are you building, what's the timeline, and what does success look like?"
                                        class="{{ $input }} {{ $inputState('message') }} resize-y"
                                    >
{{ $old('message') }}</textarea
                                    >
                                    @error('message')
                                        <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-1 flex flex-col gap-4 border-t border-[#E1E5EA] pt-5 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="font-body-sm text-body-sm text-secondary max-w-sm">
                                        We only use your details to respond to this enquiry. No mailing lists, no spam.
                                    </p>
                                    <button
                                        type="submit"
                                        x-bind:disabled="submitting"
                                        class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex w-full shrink-0 items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors disabled:cursor-wait disabled:opacity-70 sm:w-auto"
                                    >
                                        <span x-text="submitting ? 'Sending…' : 'Send Message →'">Send Message →</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <aside class="gap-section flex flex-col lg:sticky lg:top-24 lg:col-span-5 xl:col-span-4">
                        <div class="rounded-lg bg-[#0A0A0A] p-4 text-white md:p-5">
                            <div class="mb-4 flex items-center gap-2">
                                <span class="bg-primary-container h-2 w-2 rounded-full"></span>
                                <span class="font-label-sm text-label-sm tracking-widest text-[#A0A0A0] uppercase">What happens next</span>
                            </div>
                            <ol class="flex flex-col gap-4">
                                @foreach ($steps as $step)
                                    <li class="flex gap-4">
                                        <span class="text-label-md font-mono font-semibold text-[#4D8BFF]">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <div>
                                            <p class="font-headline-sm text-[16px] font-semibold text-white">{{ $step['title'] }}</p>
                                            <p class="font-body-sm text-body-sm mt-1 text-[#A0A0A0]">{{ $step['text'] }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        @if ($socialLinks)
                            <div class="rounded-lg border border-[#E1E5EA] bg-white p-4 shadow-sm">
                                <span class="font-label-sm text-label-sm tracking-widest text-[#8E91A0] uppercase">Follow us</span>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($socialLinks as $social)
                                        <a
                                            href="{{ $social['url'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="font-label-sm text-label-sm text-on-surface hover:border-primary-container hover:text-primary-container border border-[#E1E5EA] px-3 py-1.5 tracking-wider uppercase transition-colors"
                                        >
                                            {{ $social['platform'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </aside>
                </div>

                @if ($map)
                    <div
                        class="bg-surface-container-low mt-8 h-70 overflow-hidden rounded-lg border border-[#E1E5EA] md:mt-12 md:h-90 lg:mt-16 lg:h-105 [&_iframe]:h-full [&_iframe]:w-full [&_iframe]:border-0"
                    >
                        {!! $map !!}
                    </div>
                @endif
            </div>
        </section>

        <x-website.faq title="Questions before we talk?" text="If you don't see your question here, just ask it in the form." class="bg-white" />
    </div>
</x-website>
