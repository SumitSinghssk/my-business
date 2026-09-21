@php
    $technologies = [
        ['name' => 'React', 'logo' => 'react.svg'],
        ['name' => 'Next.js', 'logo' => 'nextjs.svg'],
        ['name' => 'TypeScript', 'logo' => 'typescript.svg'],
        ['name' => 'Laravel', 'logo' => 'laravel.svg'],
        ['name' => 'Node.js', 'logo' => 'nodejs.svg'],
        ['name' => 'Flutter', 'logo' => 'flutter.svg'],
        ['name' => 'React Native', 'logo' => 'react.svg'],
        ['name' => 'AWS Cloud', 'logo' => 'aws.svg'],
        ['name' => 'Docker', 'logo' => 'docker.svg'],
        ['name' => 'PostgreSQL', 'logo' => 'postgresql.svg'],
        ['name' => 'Tailwind CSS', 'logo' => 'tailwindcss.svg'],
    ];
@endphp

<section class="py-space-md w-full overflow-hidden border-b border-[#E1E5EA] bg-[#F7F8FA]">
    <div class="site-container">
        <div class="gap-space-md md:gap-space-xl flex flex-col md:flex-row md:items-center">
            <div class="font-label-sm text-label-sm text-outline shrink-0 tracking-widest uppercase">Built with modern technologies —</div>

            <div
                class="group relative min-w-0 flex-1 overflow-hidden mask-[linear-gradient(to_right,transparent,black_6%,black_94%,transparent)]"
                role="region"
                aria-label="Technologies we use"
            >
                <div class="animate-marquee flex w-max group-hover:[animation-play-state:paused] motion-reduce:animate-none">
                    @foreach ([false, true] as $isDuplicate)
                        <ul class="gap-space-2xl pr-space-2xl flex shrink-0 items-center" @if ($isDuplicate) aria-hidden="true" @endif>
                            @foreach ($technologies as $technology)
                                <li class="gap-space-sm flex shrink-0 items-center">
                                    <img
                                        src="{{ asset('images/website/technologies/' . $technology['logo']) }}"
                                        alt=""
                                        width="28"
                                        height="28"
                                        class="h-7 w-7 object-contain"
                                        loading="lazy"
                                    />
                                    <span class="font-label-md text-label-md font-medium tracking-wide whitespace-nowrap text-[#434655]">
                                        {{ $technology['name'] }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
