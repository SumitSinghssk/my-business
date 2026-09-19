@php
    $sectors = [
        [
            'number' => '01',
            'title' => 'Healthcare & Life Sciences',
            'text' => 'HIPAA & FDA Title 21 compliant architecture, real-time telemetry streaming, genomic diagnostic workflows, and patient informatics platforms.',
            'image' => asset('images/website/industries/healthcare.jpg'),
            'alt' => 'Modern digital healthcare and biomedical informatics',
            'tags' => ['HIPAA Compliant', 'HL7 / FHIR', 'Zero-Trust Telemetry'],
        ],
        [
            'number' => '02',
            'title' => 'Financial Services & Trading',
            'text' => 'PCI-DSS Level 1 infrastructure, algorithmic clearing, sub-millisecond settlement engines, and real-time fraud telemetry.',
            'image' => asset('images/website/industries/financial-services.jpg'),
            'alt' => 'Modern global fintech and high-frequency trading infrastructure',
            'tags' => ['PCI-DSS Level 1', 'Sub-ms Settlement', 'ISO 27001'],
        ],
        [
            'number' => '03',
            'title' => 'Logistics & Headless Commerce',
            'text' => 'Autonomous fulfillment orchestration, distributed inventory synchronization, edge routing, and high-concurrency cart microservices.',
            'image' => asset('images/website/industries/logistics-commerce.jpg'),
            'alt' => 'Headless e-commerce and autonomous supply chain logistics',
            'tags' => ['High Concurrency', 'Edge Routing', 'Multi-Tenant ERP'],
        ],
        [
            'number' => '04',
            'title' => 'Real Estate & PropTech',
            'text' => 'MLS data synchronization, smart asset tokenization, automated leasing workflows, and property management portals.',
            'image' => asset('images/website/industries/real-estate.jpg'),
            'alt' => 'Modern real estate technology and smart property management',
            'tags' => ['MLS / RESO Sync', 'Asset Tokenization', 'Automated Leasing'],
        ],
        [
            'number' => '05',
            'title' => 'Hospitality & Travel',
            'text' => 'Unified booking engines, distributed PMS API integrations, real-time guest profile sync, and channel manager connectivity.',
            'image' => asset('images/website/industries/hospitality-travel.jpg'),
            'alt' => 'Digital hospitality and travel booking platforms',
            'tags' => ['Unified Booking', 'PMS APIs', 'Guest Sync'],
        ],
        [
            'number' => '06',
            'title' => 'Education & EdTech',
            'text' => 'LMS integrations, student analytics dashboards, admissions and fee workflows, and secure learner portals.',
            'image' => asset('images/website/industries/education.jpg'),
            'alt' => 'Modern education technology and student learning platforms',
            'tags' => ['LMS Integration', 'Student Analytics', 'Role-Based Portals'],
        ],
        [
            'number' => '07',
            'title' => 'Professional & Legal',
            'text' => 'Encrypted client vaults, automated compliance tracking, matter management, and time-based billing systems.',
            'image' => asset('images/website/industries/professional-legal.jpg'),
            'alt' => 'Secure legal and professional services software',
            'tags' => ['Client Vault', 'Compliance Automation', 'Audit Trails'],
        ],
        [
            'number' => '08',
            'title' => 'Venture Startups',
            'text' => 'Rapid MVP sprints, product-market telemetry, scalable architecture foundations, and investor-ready technical roadmaps.',
            'image' => asset('images/website/industries/venture-startups.jpg'),
            'alt' => 'Startup product development and rapid MVP engineering',
            'tags' => ['MVP Sprints', 'Product Telemetry', 'Scale-Ready'],
        ],
    ];
@endphp

<section class="py-space-lg md:py-space-xl w-full border-b border-[#E1E5EA] bg-white">
    <div class="site-container px-4">
        <div class="mb-6 flex flex-col items-start gap-3 text-left lg:mx-auto lg:mb-8 lg:max-w-4xl lg:items-center lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-[#0A0A0A] md:text-3xl lg:text-4xl">
                Digital solutions across industries.
            </h2>
            <p class="font-body-md text-body-md text-secondary max-w-2xl">
                Tailored sector logic addressing compliance, regulatory security, and customer expectations.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:gap-6 xl:grid-cols-4">
            @foreach ($sectors as $sector)
                <div
                    class="hover:border-primary-container flex flex-col overflow-hidden rounded-lg border border-[#E1E5EA] bg-white shadow-sm transition duration-200"
                >
                    <img
                        src="{{ $sector['image'] }}"
                        width="1376"
                        height="768"
                        alt="{{ $sector['alt'] }}"
                        class="h-48 w-full rounded-t-lg border-b border-[#E1E5EA] object-cover"
                        loading="lazy"
                    />
                    <div class="flex flex-1 flex-col justify-between gap-4 rounded-b-lg bg-white p-4">
                        <div class="space-y-space-xs">
                            <div class="mb-1 flex items-center gap-1.5">
                                <span class="bg-primary-container h-1.5 w-1.5 rounded-full"></span>
                                <span class="text-label-sm text-outline font-mono font-medium tracking-wider uppercase">
                                    Sector / {{ $sector['number'] }}
                                </span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm mb-2 font-semibold text-[#0A0A0A]">{{ $sector['title'] }}</h3>
                            <p class="font-body-sm text-body-sm text-secondary leading-relaxed">{{ $sector['text'] }}</p>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($sector['tags'] as $tag)
                                <span
                                    class="bg-surface-container-low font-label-sm border border-[#E1E5EA] px-2 py-0.5 text-[9px] font-semibold tracking-wider text-[#434655] uppercase"
                                >
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
