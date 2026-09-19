@php
    use App\Helpers\Settings;

    $appName = Settings::appName();
    $emails = array_values(array_filter(Settings::emails()));
    $phones = array_values(array_filter(Settings::phones()));
    $addresses = array_values(array_filter(Settings::addresses(), fn ($a) => filled($a['text'] ?? null)));
    $socialLinks = Settings::socialLinks();
    $map = collect($addresses)->first(fn ($a) => filled($a['map_iframe'] ?? null))['map_iframe'] ?? null;

    $label = 'font-label-sm text-label-sm text-on-surface-variant mb-1.5 block tracking-wider uppercase';
    $input = 'font-body-md text-body-md text-on-surface placeholder:text-outline focus:border-primary-container focus:ring-primary-container w-full border bg-white px-4 py-3 transition-colors focus:ring-1 focus:outline-none';
    $inputState = fn ($field) => $errors->has($field) ? 'border-error' : 'border-[#E1E5EA]';

    $steps = [
        ['title' => 'We reply within 1 business day', 'text' => 'A senior engineer, not a sales rep, reads every message and replies personally.'],
        ['title' => '30-minute discovery call', 'text' => 'We talk through goals, constraints and timelines. An NDA is available before we start.'],
        ['title' => 'Proposal & plan', 'text' => 'You receive a scoped plan with milestones, team shape and a transparent estimate.'],
    ];

    $faqs = [
        ['q' => 'How soon can you start?', 'a' => 'Most engagements within two to three weeks of signing. For urgent work we can often begin a discovery phase sooner.'],
        ['q' => 'Do you work with early-stage startups?', 'a' => 'Yes. We work with funded startups and established enterprises alike. For MVPs we focus on the smallest product that proves your idea, built on foundations that can scale.'],
        ['q' => 'Who owns the code and intellectual property?', 'a' => 'You do. All source code, designs and documentation are transferred to you in full, with repositories in your own accounts from day one.'],
        ['q' => 'Can you take over an existing codebase?', 'a' => 'Absolutely. We start with a technical audit covering architecture, security, performance and test coverage, then agree a plan to stabilise and improve it.'],
        ['q' => 'Do you offer support after launch?', 'a' => 'Yes. We offer ongoing engineering retainers covering monitoring, maintenance, security updates and continued feature development.'],
    ];
@endphp

<x-website :title="'Contact Us | ' . $appName" description="Tell us about your project. Our engineering team replies within one business day.">
    @include('website.partials.faq-schema', ['faqs' => $faqs])

    <section class="pt-space-xl w-full border-b border-[#E1E5EA] pb-10">
        <div class="site-container px-4">
            <div class="gap-gutter grid grid-cols-1 items-stretch lg:grid-cols-[1.2fr_1fr]">
                <div class="lg:pr-space-lg xl:pr-space-xl flex flex-col justify-center pr-0">
                    <div class="mb-space-md">
                        <x-website.breadcrumbs :items="[['label' => 'Contact']]" />
                    </div>

                    <h1
                        class="mb-space-lg font-display text-[44px] leading-[1.03] font-semibold tracking-[-0.04em] text-[#0A0A0A] sm:text-[60px] lg:text-[56px] xl:text-[68px] 2xl:text-[74px]"
                    >
                        Let's build something reliable together.
                    </h1>

                    <p class="mb-space-xl font-body-lg text-body-lg text-secondary max-w-xl">
                        Tell us what you're building. Whether it's a new product, a rescue mission or a scaling challenge, we'll help you find the
                        right way forward.
                    </p>

                    <div class="gap-space-md flex flex-wrap items-center">
                        <a
                            href="#contact-form"
                            class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors"
                        >
                            Send a Message ↓
                        </a>
                    </div>
                </div>

                <div class="relative aspect-16/10 w-full overflow-hidden lg:aspect-auto lg:min-h-full">
                    <img
                        src="{{ asset('images/website/contact/workspace.jpg') }}"
                        width="1600"
                        height="1000"
                        alt="Engineering team collaborating around a shared desk"
                        class="absolute inset-0 h-full w-full object-cover object-top-left"
                        fetchpriority="high"
                    />
                </div>
            </div>
        </div>
    </section>

    <section class="py-space-lg md:py-space-xl w-full">
        <div class="site-container grid grid-cols-1 items-start gap-6 px-4 lg:grid-cols-12 lg:gap-8">
            <div id="contact-form" class="scroll-mt-24 lg:col-span-7">
                <div class="rounded-lg border border-[#E1E5EA] bg-white p-5 shadow-sm md:p-6 lg:p-8">
                    <div class="mb-5 flex items-center justify-between gap-3 border-b border-[#E1E5EA] pb-4">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Tell us about your project</h2>
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
                                    value="{{ old('name') }}"
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
                                    value="{{ old('email') }}"
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
                                    value="{{ old('company') }}"
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
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    class="{{ $input }} {{ $inputState('phone') }}"
                                />
                                @error('phone')
                                    <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
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
                            <div>
                                <label for="budget" class="{{ $label }}">Estimated Budget</label>
                                <select id="budget" name="budget" class="{{ $input }} {{ $inputState('budget') }} cursor-pointer">
                                    <option value="">Select a range</option>
                                    @foreach ($budgets as $value => $text)
                                        <option value="{{ $value }}" @selected(old('budget') === $value)>{{ $text }}</option>
                                    @endforeach
                                </select>
                                @error('budget')
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
{{ old('message') }}</textarea
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
                                class="px-space-xl font-label-md text-label-md hover:bg-primary-container inline-flex shrink-0 items-center justify-center bg-[#0A0A0A] py-4 tracking-wider text-white uppercase transition-colors disabled:cursor-wait disabled:opacity-70"
                            >
                                <span x-text="submitting ? 'Sending…' : 'Send Message →'">Send Message →</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="flex flex-col gap-6 lg:col-span-5 lg:gap-8">
                <div class="overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm">
                    <dl class="divide-y divide-[#E1E5EA]">
                        @if ($emails)
                            <div class="p-5">
                                <dt class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Email</dt>
                                @foreach ($emails as $email)
                                    <dd class="mt-1">
                                        <a
                                            href="mailto:{{ $email }}"
                                            class="font-headline-sm text-headline-sm text-on-surface hover:text-primary-container break-all transition-colors"
                                        >
                                            {{ $email }}
                                        </a>
                                    </dd>
                                @endforeach
                            </div>
                        @endif

                        @if ($phones)
                            <div class="p-5">
                                <dt class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Phone</dt>
                                @foreach ($phones as $phone)
                                    <dd class="mt-1">
                                        <a
                                            href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}"
                                            class="font-headline-sm text-headline-sm text-on-surface hover:text-primary-container transition-colors"
                                        >
                                            {{ $phone }}
                                        </a>
                                    </dd>
                                @endforeach
                            </div>
                        @endif

                        @foreach ($addresses as $address)
                            <div class="p-5">
                                <dt class="font-label-sm text-label-sm text-outline tracking-widest uppercase">
                                    {{ $address['label'] ?? null ?: 'Office' }}
                                </dt>
                                <dd class="font-body-md text-body-md text-on-surface mt-1 whitespace-pre-line">{{ $address['text'] }}</dd>
                            </div>
                        @endforeach

                        @if ($socialLinks)
                            <div class="p-5">
                                <dt class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Follow</dt>
                                <dd class="mt-3 flex flex-wrap gap-2">
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
                                </dd>
                            </div>
                        @endif

                        @unless ($emails || $phones || $addresses)
                            <div class="p-5">
                                <dt class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Get in touch</dt>
                                <dd class="font-body-md text-body-md text-secondary mt-1">
                                    Use the form and our team will get back to you within one business day.
                                </dd>
                            </div>
                        @endunless
                    </dl>
                </div>

                <div class="rounded-lg bg-[#0A0A0A] p-5 text-white md:p-6">
                    <div class="mb-4 flex items-center gap-2">
                        <span class="bg-primary-container h-2 w-2 rounded-full"></span>
                        <span class="font-label-sm text-label-sm tracking-widest text-[#A0A0A0] uppercase">What happens next</span>
                    </div>
                    <ol class="flex flex-col gap-4">
                        @foreach ($steps as $step)
                            <li class="flex gap-4">
                                <span class="text-label-md text-primary-container font-mono font-semibold">
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
            </aside>
        </div>
    </section>

    @if ($map)
        <section class="w-full pb-6 md:pb-8">
            <div class="site-container px-4">
                <div
                    class="bg-surface-container-low h-[300px] overflow-hidden rounded-lg border border-[#E1E5EA] md:h-[360px] [&_iframe]:h-full [&_iframe]:w-full [&_iframe]:border-0"
                >
                    {!! $map !!}
                </div>
            </div>
        </section>
    @endif

    <section id="faq" class="py-space-lg md:py-space-xl w-full scroll-mt-20 border-t border-[#E1E5EA] bg-white">
        <div class="site-container px-4">
            <div class="mb-6 flex flex-col items-start gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
                <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                    Questions before we talk?
                </h2>
                <p class="font-body-md text-body-md text-secondary max-w-2xl">If you don't see your question here, just ask it in the form.</p>
            </div>

            <div x-data="{ open: 0 }" class="mx-auto max-w-3xl divide-y divide-[#E1E5EA] overflow-hidden rounded-lg border border-[#E1E5EA]">
                @foreach ($faqs as $faq)
                    <div>
                        <button
                            type="button"
                            x-on:click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
                            :aria-expanded="(open === {{ $loop->index }}).toString()"
                            class="flex w-full items-center justify-between gap-4 p-5 text-left transition-colors hover:bg-[#F7F8FA]"
                        >
                            <span class="font-headline-sm text-on-surface text-base font-semibold sm:text-[17px]">{{ $faq['q'] }}</span>
                            <svg
                                class="text-primary-container h-5 w-5 shrink-0 transition-transform duration-200"
                                :class="open === {{ $loop->index }} && 'rotate-45'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                aria-hidden="true"
                            >
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                        </button>
                        <div x-show="open === {{ $loop->index }}" x-transition.opacity x-cloak class="px-5 pb-5">
                            <p class="font-body-md text-body-md text-secondary leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-website>
