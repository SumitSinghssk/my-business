{{-- Contact: the enquiry form card. Expects $services. --}}
<div
    id="contact-form"
    x-data="contactForm({
                errors: @js((object) $errors->getMessages()),
                success: @js(session('contact_success', '')),
            })"
    class="border-line scroll-mt-24 rounded-lg border bg-white p-4 shadow-sm sm:p-5 lg:p-6"
>
    <div class="border-line mb-5 flex items-center justify-between gap-3 border-b pb-4">
        <h3 class="card-title">Project details</h3>
        <span class="font-label-sm text-label-sm text-outline shrink-0 tracking-wider uppercase">* Required</span>
    </div>

    {{-- Both messages are rendered by the server too, so they also work when the page reloads (JavaScript off). --}}
    <div
        x-ref="success"
        tabindex="-1"
        role="status"
        x-bind:hidden="! success"
        @unless (session('contact_success')) hidden @endunless
        class="border-success/30 bg-success/10 text-on-success mb-5 flex scroll-mt-24 items-start gap-3 border p-4 focus:outline-none"
    >
        <span class="font-semibold" aria-hidden="true">✓</span>
        <p class="font-body-md text-body-md" x-text="success">{{ session('contact_success') }}</p>
    </div>

    <div
        x-ref="failed"
        tabindex="-1"
        role="alert"
        x-bind:hidden="! failed"
        @unless ($errors->any()) hidden @endunless
        x-text="failed"
        class="border-error/30 bg-error-container/40 font-body-sm text-body-sm text-on-error-container mb-5 scroll-mt-24 border p-4 focus:outline-none"
    >
        Please check the highlighted fields and try again.
    </div>

    <form
        method="POST"
        action="{{ route('contact.store') }}"
        x-on:submit.prevent="submit($el)"
        @if ($errors->any()) x-init="
            window.addEventListener('load', () =>
                $el.querySelector('[aria-invalid=true]')?.focus(),
            )
        " @endif
        class="flex flex-col gap-4"
        novalidate
    >
        @csrf

        <div class="hidden" aria-hidden="true">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-website.form.input name="name" label="Full Name *" required autocomplete="name" />
            <x-website.form.input name="email" type="email" label="Work Email *" required autocomplete="email" />
            <x-website.form.input name="company" label="Company" autocomplete="organization" />
            <x-website.form.input name="phone" type="tel" label="Phone" autocomplete="tel" />
            <x-website.form.select
                name="service"
                label="What do you need?"
                placeholder="Select a service"
                :options="$services"
                :selected="request()->query('service')"
                class="sm:col-span-2"
            />
        </div>

        <x-website.form.textarea
            name="message"
            label="Project Details *"
            required
            placeholder="What are you building, what's the timeline, and what does success look like?"
        />

        <div class="border-line mt-1 flex flex-col gap-4 border-t pt-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="font-body-sm text-body-sm text-secondary max-w-sm">
                We only use your details to respond to this enquiry. No mailing lists, no spam.
            </p>
            <x-website.button type="submit" x-bind:disabled="submitting" class="shrink-0 disabled:cursor-wait disabled:opacity-70">
                <span x-text="submitting ? 'Sending…' : 'Send Message →'">Send Message →</span>
            </x-website.button>
        </div>
    </form>
</div>
