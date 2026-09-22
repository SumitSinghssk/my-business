{{-- About: the principles behind every decision. Expects $values. --}}
{{-- Values --}}
<section class="section-y border-line w-full border-b bg-white">
    <div class="site-container">
        <x-website.section-heading
            title="The principles behind every decision."
            text="These aren't posters on a wall. They shape how we scope work, review code, and talk to the people we build for."
        />

        <div class="gap-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($values as $value)
                <div
                    class="group hover:border-primary-container border-line flex h-full flex-col rounded-lg border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                >
                    <span class="text-label-md text-primary mb-3 font-mono font-semibold">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <h3 class="font-headline-sm text-headline-sm group-hover:text-primary-container text-ink mb-2 font-semibold transition-colors">
                        {{ $value['title'] }}
                    </h3>
                    <p class="font-body-md text-body-md text-secondary">{{ $value['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
