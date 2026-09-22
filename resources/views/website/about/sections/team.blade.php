{{-- About: the people leading the work. Expects $team. --}}
{{-- Team --}}
<section class="section-y border-line w-full border-b">
    <div class="site-container">
        <x-website.section-heading
            title="The people leading the work."
            text="Senior specialists who stay close to every engagement, from the first workshop to long after launch."
        />

        <div class="gap-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($team as $person)
                @php
                    $initials = collect(preg_split('/\s+/', trim($person['name'])))
                        ->filter()
                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                        ->take(2)
                        ->implode('');
                @endphp

                <div class="border-line flex h-full flex-col rounded-lg border bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span
                            class="{{ $loop->even ? 'bg-primary-container' : 'bg-ink' }} font-headline-sm flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-base font-semibold text-white"
                            aria-hidden="true"
                        >
                            {{ $initials }}
                        </span>
                        <div class="min-w-0">
                            <h3 class="text-ink text-[17px] leading-snug font-semibold">{{ $person['name'] }}</h3>
                            <p class="font-label-sm text-primary text-[11px] tracking-wider uppercase">{{ $person['role'] }}</p>
                        </div>
                    </div>
                    <p class="font-body-sm text-body-sm text-secondary border-line mt-3 border-t pt-3">{{ $person['bio'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
