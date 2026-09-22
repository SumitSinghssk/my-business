<?php

namespace App\Support;

/**
 * Static marketing content of the website (texts, images and lists that are not managed in the admin panel).
 * Kept here so the Blade views only contain markup. Edit the copy in this file.
 */
class WebsiteContent
{
    /** Industries on the home page (image, title, text, capabilities). */
    public static function industries(): array
    {
        return [
            [
                'number' => '01',
                'title' => 'Healthcare & Life Sciences',
                'text' => 'HIPAA & FDA Title 21 compliant architecture, real-time telemetry streaming, genomic diagnostic workflows, and patient informatics platforms.',
                'image' => asset('images/website/industries/healthcare.webp'),
                'alt' => 'Modern digital healthcare and biomedical informatics',
                'tags' => ['HIPAA Compliant', 'HL7 / FHIR', 'Zero-Trust Telemetry'],
            ],
            [
                'number' => '02',
                'title' => 'Financial Services & Trading',
                'text' => 'PCI-DSS Level 1 infrastructure, algorithmic clearing, sub-millisecond settlement engines, and real-time fraud telemetry.',
                'image' => asset('images/website/industries/financial-services.webp'),
                'alt' => 'Modern global fintech and high-frequency trading infrastructure',
                'tags' => ['PCI-DSS Level 1', 'Sub-ms Settlement', 'ISO 27001'],
            ],
            [
                'number' => '03',
                'title' => 'Logistics & Headless Commerce',
                'text' => 'Autonomous fulfillment orchestration, distributed inventory synchronization, edge routing, and high-concurrency cart microservices.',
                'image' => asset('images/website/industries/logistics-commerce.webp'),
                'alt' => 'Headless e-commerce and autonomous supply chain logistics',
                'tags' => ['High Concurrency', 'Edge Routing', 'Multi-Tenant ERP'],
            ],
            [
                'number' => '04',
                'title' => 'Real Estate & PropTech',
                'text' => 'MLS data synchronization, smart asset tokenization, automated leasing workflows, and property management portals.',
                'image' => asset('images/website/industries/real-estate.webp'),
                'alt' => 'Modern real estate technology and smart property management',
                'tags' => ['MLS / RESO Sync', 'Asset Tokenization', 'Automated Leasing'],
            ],
            [
                'number' => '05',
                'title' => 'Hospitality & Travel',
                'text' => 'Unified booking engines, distributed PMS API integrations, real-time guest profile sync, and channel manager connectivity.',
                'image' => asset('images/website/industries/hospitality-travel.webp'),
                'alt' => 'Digital hospitality and travel booking platforms',
                'tags' => ['Unified Booking', 'PMS APIs', 'Guest Sync'],
            ],
            [
                'number' => '06',
                'title' => 'Education & EdTech',
                'text' => 'LMS integrations, student analytics dashboards, admissions and fee workflows, and secure learner portals.',
                'image' => asset('images/website/industries/education.webp'),
                'alt' => 'Modern education technology and student learning platforms',
                'tags' => ['LMS Integration', 'Student Analytics', 'Role-Based Portals'],
            ],
            [
                'number' => '07',
                'title' => 'Professional & Legal',
                'text' => 'Encrypted client vaults, automated compliance tracking, matter management, and time-based billing systems.',
                'image' => asset('images/website/industries/professional-legal.webp'),
                'alt' => 'Secure legal and professional services software',
                'tags' => ['Client Vault', 'Compliance Automation', 'Audit Trails'],
            ],
            [
                'number' => '08',
                'title' => 'Venture Startups',
                'text' => 'Rapid MVP sprints, product-market telemetry, scalable architecture foundations, and investor-ready technical roadmaps.',
                'image' => asset('images/website/industries/venture-startups.webp'),
                'alt' => 'Startup product development and rapid MVP engineering',
                'tags' => ['MVP Sprints', 'Product Telemetry', 'Scale-Ready'],
            ],
        ];
    }

    /** Delivery lifecycle phases on the home page. */
    public static function deliveryPhases(): array
    {
        return [
            [
                'meta' => 'Phase 1.0 • Days 1-10',
                'title' => 'Discover & Map',
                'text' => 'Deep immersion in commercial logic, constraint mapping, technical debt audits, and core user workflows.',
                'image' => 'discover.webp',
                'alt' => 'Team mapping requirements on a wall of notes during a discovery workshop sticky',
            ],
            [
                'meta' => 'Phase 2.0 • Days 11-20',
                'title' => 'Architecture & Specs',
                'text' => 'System topology, relational and non-relational data modeling, API contracts, and infrastructure blueprints.',
                'image' => 'architecture.webp',
                'alt' => 'Engineer connecting components on a system architecture flow diagram',
            ],
            [
                'meta' => 'Phase 3.0 • Sprints 1-2',
                'title' => 'Design Systems',
                'text' => 'High-density wireframing, component tokenization, accessible interaction patterns, and click-through prototypes.',
                'image' => 'design-systems.webp',
                'alt' => 'Designer sketching user interface wireframes on paper',
            ],
            [
                'meta' => 'Phase 4.0 • Sprints 3-6',
                'title' => 'Agile Build',
                'text' => 'Modular engine engineering, type-safe data pipes, continuous automated regression testing, and PR reviews.',
                'image' => 'agile-build.webp',
                'alt' => 'Source code open in an editor on a laptop',
            ],
            [
                'meta' => 'Phase 5.0 • Production',
                'title' => 'Hardened Launch',
                'text' => 'Third-party penetration testing, multi-region container orchestration, staging audits, and zero-downtime cutover.',
                'image' => 'launch.webp',
                'alt' => 'Networked server racks in a data centre',
            ],
            [
                'meta' => 'Phase 6.0 • Continuity',
                'title' => 'Scale & Observability',
                'text' => 'Real-time telemetry observability, P99 latency optimizations, weekly iterations, and enterprise SLA guarantee.',
                'image' => 'observability.webp',
                'alt' => 'Live performance analytics dashboard with latency and traffic charts',
            ],
        ];
    }

    /** Technology stack layers on the home page. */
    public static function technologyLayers(): array
    {
        return [
            [
                'layer' => 'Layer 01',
                'tag' => 'Client Side',
                'title' => 'Frontend Engineering',
                'items' => ['React • Next.js 15' => 'App Router', 'TypeScript' => 'Strict Mode', 'Tailwind CSS' => 'Tokens', 'WebGL • Three.js' => 'Visuals'],
            ],
            [
                'layer' => 'Layer 02',
                'tag' => 'Server Side',
                'title' => 'Backend & Runtimes',
                'items' => ['Node.js • Express' => 'Async I/O', 'Laravel • PHP 8.3' => 'Enterprise Core', 'REST APIs • GraphQL' => 'Type Safe', 'gRPC • Microservices' => 'Low Latency'],
            ],
            [
                'layer' => 'Layer 03',
                'tag' => 'Portable',
                'title' => 'Mobile Applications',
                'items' => ['Flutter • Dart' => 'Universal', 'React Native' => 'Expo EAS', 'Swift / Swift UI' => 'iOS Native', 'Kotlin Jetpack Compose' => 'Android'],
            ],
            [
                'layer' => 'Layer 04',
                'tag' => 'Storage',
                'title' => 'Databases & Caching',
                'items' => ['PostgreSQL' => 'ACID Strict', 'Redis Cluster' => 'Sub-ms Cache', 'MySQL Enterprise' => 'Relational', 'MongoDB / NoSQL' => 'Document Store'],
            ],
            [
                'layer' => 'Layer 05',
                'tag' => 'Systems',
                'title' => 'Cloud & DevOps',
                'items' => ['Amazon Web Services (AWS)' => 'Terraform', 'Docker Containers' => 'Immutable', 'Kubernetes' => 'Autoscaling', 'GitHub Actions CI/CD' => 'Continuous'],
            ],
            [
                'layer' => 'Layer 06',
                'tag' => 'Intelligence',
                'title' => 'AI & Automation',
                'items' => ['OpenAI • Anthropic APIs' => 'LLMs', 'Custom RAG Architectures' => 'Contextual', 'Pinecone • pgvector' => 'Embeddings', 'Background Worker Queues' => 'Event-Driven'],
            ],
        ];
    }

    /** Client testimonials on the home page. */
    public static function testimonials(): array
    {
        return [
            [
                'quote' => "Brought enterprise-level discipline to our product engineering. They didn't just write code; they transformed our product velocity, uptime, and systemic reliability.",
                'name' => 'Marcus Vance',
                'role' => 'Chief Technology Officer',
                'company' => 'Vectra Dynamics',
            ],
            [
                'quote' => 'From the first sprint, the team treated our platform like their own. Release cycles dropped from weeks to days, and our compliance audits stopped being a fire drill.',
                'name' => 'Elena Rossi',
                'role' => 'VP of Engineering',
                'company' => 'Northbridge Health',
            ],
            [
                'quote' => 'We came in with a rough idea and left with a production-ready product. Clear communication, honest timelines, and architecture that scales without rewrites.',
                'name' => 'Daniel Okafor',
                'role' => 'Founder & CEO',
                'company' => 'Stackline Logistics',
            ],
            [
                'quote' => 'Their engineers slotted into our workflow seamlessly. Uptime is up, incident load is down, and our own team finally has room to focus on roadmap work.',
                'name' => 'Priya Nair',
                'role' => 'Head of Product',
                'company' => 'Finlytic',
            ],
        ];
    }

    /** Technology logos in the scrolling strip. */
    public static function technologies(): array
    {
        return [
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
    }

    /** "Why us" principles on the home page. */
    public static function principles(): array
    {
        return [
            ['label' => '01 / Discipline', 'title' => 'Business First', 'text' => 'We align technical scope with unit economics, conversion efficiency, and operational ROI before writing a single line of code.', 'footer' => 'Measurable Outcomes'],
            ['label' => '02 / Unification', 'title' => 'Design & Engineering', 'text' => 'No wall between designers and engineers. Prototypes are tested against real API schemas early in the process to prevent costly rework.', 'footer' => 'Zero Hand-off Drift'],
            ['label' => '03 / Performance', 'title' => 'Built to Scale', 'text' => 'Architected from day one to handle traffic spikes, database sharding, and regional expansions without rewriting core infrastructure.', 'footer' => 'Enterprise Durability'],
            ['label' => '04 / Partnership', 'title' => 'Long-Term Thinking', 'text' => 'Complete documentation, 100% IP handover, automated regression test suites, and continuous post-launch engineering support.', 'footer' => 'Full Code Sovereignty'],
        ];
    }

    /** Key numbers in the home page about section. */
    public static function homeStats(): array
    {
        return [
            ['value' => '99.98%', 'label' => 'Historical SLA Production Uptime', 'highlight' => false],
            ['value' => '$420M+', 'label' => 'Processed Client Volume (2024)', 'highlight' => true],
            ['value' => '14', 'label' => 'Global Engineering & Design Awards', 'highlight' => false],
        ];
    }

    /** Process steps (about and services pages). */
    public static function processSteps(): array
    {
        return [
            ['image' => 'discover', 'title' => 'Discover', 'text' => 'Workshops, user research and a technical audit to agree what success looks like before anything is built.'],
            ['image' => 'architecture', 'title' => 'Design & Architect', 'text' => 'Product design, system architecture and a delivery plan your whole team can read and challenge.'],
            ['image' => 'agile-build', 'title' => 'Build', 'text' => 'Two-week sprints with live demos, code review on every change and automated tests from day one.'],
            ['image' => 'launch', 'title' => 'Launch & Grow', 'text' => 'Staged releases, monitoring and ongoing engineering support as your product and traffic grow.'],
        ];
    }

    /** Key numbers in the about page hero. */
    public static function aboutStats(): array
    {
        return [
            ['value' => '2024', 'label' => 'Founded', 'note' => 'Independent studio'],
            ['value' => '40+', 'label' => 'Products Shipped', 'note' => 'Web, mobile & platforms'],
            ['value' => '99.98%', 'label' => 'Production Uptime', 'note' => 'Across client systems'],
            ['value' => '14', 'label' => 'Industry Awards', 'note' => 'Engineering & design'],
        ];
    }

    /** Values on the about page. */
    public static function values(): array
    {
        return [
            ['title' => 'Clarity over cleverness', 'text' => 'We choose simple, well-understood solutions and write code the next engineer can read on their first day.'],
            ['title' => 'Own the outcome', 'text' => 'We measure success by what your product achieves in the real world, not by tickets closed or hours logged.'],
            ['title' => 'Craft in the details', 'text' => 'Accessibility, performance and edge cases are part of the work, not polish we add if there is time left.'],
            ['title' => 'Honest partnership', 'text' => 'We share bad news early, explain trade-offs plainly, and recommend less work when less is the right answer.'],
        ];
    }

    /** Engagement model steps on the about page. */
    public static function engagementModel(): array
    {
        return [
            ['title' => 'Dedicated pods', 'text' => 'A small, senior team of product, design and engineering works only on your product for the length of the engagement.'],
            ['title' => 'Transparent delivery', 'text' => 'Two-week sprints, live demos and a shared board. You always know what shipped, what is next and why.'],
            ['title' => 'Full ownership', 'text' => 'Code, designs and documentation live in your accounts from day one. No lock-in, no black boxes.'],
        ];
    }

    /** Team members on the about page. */
    public static function team(): array
    {
        return [
            ['name' => 'Elena Vance', 'role' => 'Principal Systems Architect', 'bio' => 'Designs fault-tolerant platforms and leads our core architecture practice.'],
            ['name' => 'Daniel Okafor', 'role' => 'Head of Engineering', 'bio' => 'Runs our engineering pods and keeps delivery predictable at scale.'],
            ['name' => 'Sofia Thorne', 'role' => 'Head of Product Design', 'bio' => 'Leads research, design systems and interaction design across products.'],
            ['name' => 'Priya Raman', 'role' => 'Director of Delivery', 'bio' => 'Partners with clients on scope, planning and long-term product roadmaps.'],
        ];
    }
}
