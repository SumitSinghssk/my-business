<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Seeds demo blog categories and posts for the public Insights pages.
 *
 * Safe to re-run: every record is matched on its slug, so running it again
 * updates the seeded rows instead of duplicating them. Article bodies live in
 * database/seeders/content/blogs/{slug}.html and featured images in
 * database/seeders/assets/blogs/{slug}.jpg (copied to the public disk).
 *
 *   php artisan db:seed --class=BlogSeeder
 */
class BlogSeeder extends Seeder
{
    private const CONTENT_PATH = 'seeders/content/blogs';

    private const IMAGE_PATH = 'seeders/assets/blogs';

    public function run(): void
    {
        $author = User::where('email', 'superadmin@gmail.com')->first() ?? User::query()->first();

        if (! $author) {
            $this->command?->warn('BlogSeeder skipped: no users found. Run AdminUserSeeder first.');

            return;
        }

        $categories = collect($this->categories())->mapWithKeys(function (array $category) {
            $model = BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'status' => CommonStatusEnum::ACTIVE->value,
                ]
            );

            return [$category['slug'] => $model->id];
        });

        foreach ($this->posts() as $post) {
            $blog = Blog::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'user_id' => $author->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => File::get(database_path(self::CONTENT_PATH."/{$post['slug']}.html")),
                    'featured_image' => $this->storeImage($post['slug']),
                    'status' => CommonStatusEnum::ACTIVE->value,
                    'published_at' => Carbon::parse($post['published_at']),
                ]
            );

            $blog->categories()->sync($categories->only($post['categories'])->values());
        }

        $this->command?->info('BlogSeeder: '.count($this->categories()).' categories and '.count($this->posts()).' posts seeded.');
    }

    /**
     * Copy the bundled image onto the public disk (same place admin uploads go)
     * and return its stored path.
     */
    private function storeImage(string $slug): ?string
    {
        $source = database_path(self::IMAGE_PATH."/{$slug}.jpg");

        if (! File::exists($source)) {
            return null;
        }

        $path = "blogs/{$slug}.jpg";
        Storage::disk('public')->put($path, File::get($source));

        return $path;
    }

    private function categories(): array
    {
        return [
            ['slug' => 'distributed-architecture', 'name' => 'Distributed Architecture', 'description' => 'Caching, replication, event streaming and the systems that hold global products together.'],
            ['slug' => 'design-systems', 'name' => 'Design Systems', 'description' => 'Tokens, component libraries and the workflow between design and engineering.'],
            ['slug' => 'ai-infrastructure', 'name' => 'AI & Infrastructure', 'description' => 'Model serving, GPUs, observability and the platforms underneath modern products.'],
            ['slug' => 'web-engineering', 'name' => 'Web Engineering', 'description' => 'Frontend architecture, APIs and the craft of building for the web.'],
            ['slug' => 'engineering-management', 'name' => 'Engineering Management', 'description' => 'Team structure, delivery and how engineering organisations scale.'],
            ['slug' => 'case-post-mortems', 'name' => 'Case Post-Mortems', 'description' => 'Honest write-ups of migrations, incidents and what we learned from them.'],
        ];
    }

    private function posts(): array
    {
        return [
            [
                'slug' => 'rethinking-distributed-caching',
                'title' => 'Rethinking Distributed Caching: Why Redis Alone is No Longer Enough for Sub-Millisecond Global APIs',
                'excerpt' => 'A dissection of multi-tier edge caching, cache stampede mitigation and autonomous invalidation across globally distributed regions.',
                'categories' => ['distributed-architecture'],
                'published_at' => '2026-09-10 09:00:00',
            ],
            [
                'slug' => 'zero-downtime-database-migrations',
                'title' => 'Zero-Downtime Database Migrations at 4.8M Operations per Second',
                'excerpt' => 'How we migrated 42TB of transactional PostgreSQL without dropping a single active customer session or declining a transaction.',
                'categories' => ['case-post-mortems', 'distributed-architecture'],
                'published_at' => '2026-08-27 09:00:00',
            ],
            [
                'slug' => 'design-tokens-at-scale',
                'title' => 'Design Tokens at Scale: Unifying Figma and Production TypeScript Schemas',
                'excerpt' => 'Eliminating handoff drift by compiling design tokens into CSS custom properties, typed schemas and visual regression pipelines.',
                'categories' => ['design-systems'],
                'published_at' => '2026-08-13 09:00:00',
            ],
            [
                'slug' => 'pragmatic-micro-frontends',
                'title' => 'Pragmatic Micro-Frontends: When Independent Deployability Outweighs Complexity',
                'excerpt' => 'Module Federation versus isolated sub-apps, and the organisational signals that tell you whether you need either.',
                'categories' => ['web-engineering'],
                'published_at' => '2026-07-30 09:00:00',
            ],
            [
                'slug' => 'offline-first-logistics-state',
                'title' => 'Deterministic State & Offline-First Protocol in High-Frequency Logistics',
                'excerpt' => 'Using CRDTs and event-sourced local ledgers to keep warehouse and fleet operations running through network outages.',
                'categories' => ['distributed-architecture'],
                'published_at' => '2026-07-16 09:00:00',
            ],
            [
                'slug' => 'llm-inference-latency-edge-kubernetes',
                'title' => 'Evaluating LLM Inference Latency on Edge Kubernetes Clusters',
                'excerpt' => 'Profiling vLLM, TensorRT-LLM and TGI under real multi-tenant load, and what quantisation really costs.',
                'categories' => ['ai-infrastructure'],
                'published_at' => '2026-07-02 09:00:00',
            ],
            [
                'slug' => 'rust-enterprise-api-gateway',
                'title' => 'Rust in the Enterprise API Gateway: Lessons from 10 Billion Monthly Invocations',
                'excerpt' => 'Rewriting our ingestion reverse proxy in Tokio and Hyper cut gateway cloud spend by 64% and pinned tail latency.',
                'categories' => ['distributed-architecture', 'web-engineering'],
                'published_at' => '2026-06-18 09:00:00',
            ],
            [
                'slug' => 'type-safe-api-contracts',
                'title' => 'Type-Safe API Contracts with OpenAPI 3.1, gRPC, and Zod',
                'excerpt' => 'Removing mismatches at service boundaries with contract-first design, generated clients and consumer-driven tests.',
                'categories' => ['web-engineering'],
                'published_at' => '2026-06-04 09:00:00',
            ],
            [
                'slug' => 'event-driven-kafka-debezium',
                'title' => 'Building Resilient Event-Driven Architectures with Kafka and Debezium',
                'excerpt' => 'Solving the dual-write problem with the transactional outbox pattern and change data capture.',
                'categories' => ['distributed-architecture'],
                'published_at' => '2026-05-21 09:00:00',
            ],
            [
                'slug' => 'observability-engineers-actually-use',
                'title' => 'Observability That Engineers Actually Use',
                'excerpt' => 'SLO burn-rate alerts, distributed tracing and cost controls that cut on-call pages from 41 a week to 6.',
                'categories' => ['ai-infrastructure'],
                'published_at' => '2026-05-07 09:00:00',
            ],
            [
                'slug' => 'scaling-engineering-pods',
                'title' => 'Scaling Engineering Pods Without Losing Velocity',
                'excerpt' => 'How small, autonomous, cross-functional pods restored delivery speed while the organisation kept growing.',
                'categories' => ['engineering-management'],
                'published_at' => '2026-04-23 09:00:00',
            ],
            [
                'slug' => 'post-mortem-checkout-outage',
                'title' => 'Post-Mortem: The 47-Minute Checkout Outage',
                'excerpt' => 'A blameless look at how a routine deploy exhausted a shared connection pool during peak sale traffic.',
                'categories' => ['case-post-mortems', 'engineering-management'],
                'published_at' => '2026-04-09 09:00:00',
            ],
        ];
    }
}
