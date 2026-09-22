{{-- Contact: one card per office with its address and map. Expects $offices. --}}
@foreach ($offices as $office)
    <div class="border-line mt-8 overflow-hidden rounded-lg border bg-white shadow-sm md:mt-12 lg:mt-16">
        <div @class(['grid grid-cols-1', 'lg:grid-cols-12' => $office['map']])>
            <div @class(['flex flex-col justify-center gap-5 p-4 sm:p-6 lg:p-8', 'lg:col-span-5 xl:col-span-4' => $office['map']])>
                <div class="flex items-start gap-3">
                    <span class="icon-tile" aria-hidden="true"><x-icons.location class="h-5 w-5" /></span>
                    <div class="min-w-0">
                        <span class="meta-label">Visit us</span>
                        <h3 class="card-title">{{ $office['label'] }}</h3>
                    </div>
                </div>

                <address class="font-body-lg text-body-lg text-ink whitespace-pre-line not-italic">{{ $office['text'] }}</address>

                <a
                    href="{{ $office['directions'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-space-lg font-label-md text-label-md border-line text-ink hover:border-ink inline-flex w-full items-center justify-center border bg-white py-3.5 tracking-wider uppercase transition-all sm:w-auto sm:self-start"
                >
                    Get Directions →
                </a>
            </div>

            @if ($office['map'])
                <div
                    class="bg-surface-container-low border-line h-72 border-t sm:h-80 lg:col-span-7 lg:h-auto lg:min-h-96 lg:border-t-0 lg:border-l xl:col-span-8 [&_iframe]:h-full [&_iframe]:w-full [&_iframe]:border-0"
                >
                    {!! $office['map'] !!}
                </div>
            @endif
        </div>
    </div>
@endforeach
