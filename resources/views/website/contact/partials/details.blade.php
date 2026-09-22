<aside class="flex flex-col gap-4 lg:sticky lg:top-24 lg:col-span-5 xl:col-span-4" aria-label="Contact information">
    @if ($emails || $phones || $socialLinks)
        <div class="border-line rounded-lg border bg-white p-4 shadow-sm sm:p-5">
            <h3 class="card-title border-line mb-4 border-b pb-4">Get in touch</h3>

            <ul class="flex flex-col gap-4">
                @if ($emails)
                    <li class="flex items-start gap-3">
                        <span class="icon-tile" aria-hidden="true"><x-icons.mail class="h-5 w-5" /></span>
                        <div class="min-w-0">
                            <span class="meta-label">Email</span>
                            @foreach ($emails as $email)
                                <a
                                    href="mailto:{{ $email }}"
                                    class="font-body-md text-body-md hover:text-primary-container text-ink block font-medium break-all transition-colors"
                                >
                                    {{ $email }}
                                </a>
                            @endforeach

                            <span class="font-body-sm text-body-sm text-secondary">Replies within 1 business day</span>
                        </div>
                    </li>
                @endif

                @if ($phones)
                    <li class="flex items-start gap-3">
                        <span class="icon-tile" aria-hidden="true"><x-icons.phone class="h-5 w-5" /></span>
                        <div class="min-w-0">
                            <span class="meta-label">Phone</span>
                            @foreach ($phones as $phone)
                                <a
                                    href="{{ \App\Helpers\Settings::telHref($phone) }}"
                                    class="font-body-md text-body-md hover:text-primary-container text-ink block font-medium transition-colors"
                                >
                                    {{ $phone }}
                                </a>
                            @endforeach

                            <span class="font-body-sm text-body-sm text-secondary">Mon–Fri, business hours</span>
                        </div>
                    </li>
                @endif
            </ul>

            @if ($socialLinks)
                <div @class(['flex items-center justify-between gap-3', 'border-line mt-5 border-t pt-4' => $emails || $phones])>
                    <ul class="flex flex-wrap items-center gap-2">
                        @foreach ($socialLinks as $social)
                            <li>
                                <a
                                    href="{{ $social['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="{{ $appName }} on {{ $social['platform'] ?: 'social media' }}"
                                    title="{{ $social['platform'] }}"
                                    class="text-on-surface-variant hover:border-primary-container hover:text-primary-container border-line flex h-10 w-10 items-center justify-center rounded-md border transition-colors"
                                >
                                    <x-icons.social :platform="$social['platform'] ?? ''" class="h-4.5 w-4.5" aria-hidden="true" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif
</aside>
