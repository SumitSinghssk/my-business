@php
    $layers = [
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
@endphp

<section class="section-y w-full border-b border-[#1C1C1C] bg-[#0A0A0A] text-white">
    <div class="site-container">
        <div class="section-head flex flex-col items-start gap-3 text-left lg:mx-auto lg:max-w-4xl lg:items-center lg:text-center">
            <h2 class="font-headline-lg text-2xl font-semibold tracking-[-0.035em] text-white md:text-3xl lg:text-4xl">
                Technology that works for your business.
            </h2>
            <p class="font-body-md text-body-md max-w-2xl text-[#A0A0A0]">
                We use proven modern technologies to create fast, secure, and scalable digital products. No fragile novelty stacks.
            </p>
        </div>

        <div class="grid grid-cols-1 border-t border-l border-[#1C1C1C] md:grid-cols-2 lg:grid-cols-3">
            @foreach ($layers as $layer)
                <div class="border-r border-b border-[#1C1C1C] p-4 transition-colors hover:bg-[#121212] lg:p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="font-label-sm text-label-sm tracking-widest text-[#8E91A0] uppercase">{{ $layer['layer'] }}</span>
                        <span class="font-label-sm text-label-sm text-[#4D8BFF] uppercase">{{ $layer['tag'] }}</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm mb-3 font-semibold text-white">{{ $layer['title'] }}</h3>
                    <ul class="space-y-space-xs font-body-md text-body-md text-[#A0A0A0]">
                        @foreach ($layer['items'] as $name => $note)
                            <li class="flex items-center justify-between gap-3">
                                <span>{{ $name }}</span>
                                <span class="shrink-0 text-xs text-[#8E91A0]">{{ $note }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
