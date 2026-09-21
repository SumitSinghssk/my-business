<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Service;
use App\Models\User;
use App\Services\ImageProcessor;
use Database\Seeders\Concerns\SeedsMissingRecords;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeds the services shown on the public Services page.
 *
 * Safe to re-run: only services that never existed are created. Existing ones
 * (possibly edited in the admin panel) and ones deleted there are left
 * untouched. Detail page content
 * lives in database/seeders/content/services/{slug}.html and images are taken
 * from public/images/website (cropped to the `service` preset).
 *
 *   php artisan db:seed --class=ServiceSeeder
 */
class ServiceSeeder extends Seeder
{
    use SeedsMissingRecords;

    private const CONTENT_PATH = 'seeders/content/services';

    private const PERMISSIONS = [
        'admin.services.view',
        'admin.services.create',
        'admin.services.edit',
        'admin.services.delete',
        'admin.services.toogle-status',
    ];

    public function run(): void
    {
        $this->grantPermissions();

        $author = User::where('email', 'superadmin@gmail.com')->first() ?? User::query()->first();

        $created = 0;
        foreach ($this->services() as $order => $item) {
            if ($this->alreadySeeded(Service::class, $item['slug'])) {
                continue;
            }

            Service::create(
                [
                    'slug' => $item['slug'],
                    'user_id' => $author?->id,
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'highlights' => $item['highlights'],
                    'tags' => $item['tags'],
                    'content' => File::get(database_path(self::CONTENT_PATH."/{$item['slug']}.html")),
                    'featured_image' => $this->storeImage($item['image']),
                    'sort_order' => $order + 1,
                    'status' => CommonStatusEnum::ACTIVE->value,
                ]
            );
            $created++;
        }

        $this->command?->info("ServiceSeeder: {$created} services created (existing ones left untouched).");
    }

    /**
     * Create the service permissions and give them to super admins only, without
     * re-syncing other roles (so permissions edited in the admin panel are kept).
     */
    private function grantPermissions(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::where('name', 'super admin')->first()?->givePermissionTo(self::PERMISSIONS);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function storeImage(string $image): ?string
    {
        $source = public_path("images/website/{$image}");

        return File::exists($source) ? app(ImageProcessor::class)->store($source, 'service') : null;
    }

    private function services(): array
    {
        return [
            [
                'slug' => 'website-development',
                'title' => 'Website Development',
                'excerpt' => 'Fast, accessible and search-friendly websites that turn visitors into customers, built on a CMS your team can actually use.',
                'highlights' => ['Marketing sites & landing pages', 'Headless and Laravel CMS builds', 'Core Web Vitals & technical SEO', 'Analytics and conversion tracking'],
                'tags' => ['Laravel', 'Next.js', 'Tailwind CSS'],
                'image' => 'process/agile-build.jpg',
            ],
            [
                'slug' => 'web-applications',
                'title' => 'Web Applications',
                'excerpt' => 'Secure, scalable web platforms, from customer portals and dashboards to full SaaS products with billing and multi-tenancy.',
                'highlights' => ['SaaS platforms & internal tools', 'Role-based access and audit trails', 'Third-party & payment integrations', 'Real-time dashboards and reporting'],
                'tags' => ['React', 'Laravel', 'PostgreSQL'],
                'image' => 'work/orion-systems.jpg',
            ],
            [
                'slug' => 'mobile-app-development',
                'title' => 'Mobile Apps',
                'excerpt' => 'Native-quality iOS and Android apps from a single codebase, designed for offline use, speed and app-store success.',
                'highlights' => ['iOS & Android from one codebase', 'Offline-first data sync', 'Push notifications & deep links', 'App Store and Play Store release'],
                'tags' => ['Flutter', 'React Native', 'Swift / Kotlin'],
                'image' => 'work/apex-pay-logistics.jpg',
            ],
            [
                'slug' => 'custom-software-development',
                'title' => 'Custom Software',
                'excerpt' => 'Bespoke systems that automate the way your business actually works, replacing spreadsheets and disconnected tools.',
                'highlights' => ['Workflow & process automation', 'ERP, CRM and legacy integrations', 'APIs and microservices', 'Data migration from legacy systems'],
                'tags' => ['Node.js', 'Go', 'REST & GraphQL'],
                'image' => 'process/architecture.jpg',
            ],
            [
                'slug' => 'ui-ux-design',
                'title' => 'UI/UX Design',
                'excerpt' => 'Research-led product design and design systems that make complex products feel simple and keep brand consistency at scale.',
                'highlights' => ['User research & journey mapping', 'Wireframes and clickable prototypes', 'Design systems & component libraries', 'Accessibility (WCAG 2.2) reviews'],
                'tags' => ['Figma', 'Design Tokens', 'Prototyping'],
                'image' => 'process/design-systems.jpg',
            ],
            [
                'slug' => 'cloud-devops',
                'title' => 'Cloud & DevOps',
                'excerpt' => 'Reliable infrastructure and automated delivery pipelines so you can ship often, scale on demand and sleep at night.',
                'highlights' => ['AWS architecture & cost optimisation', 'CI/CD pipelines and infrastructure as code', 'Containers & Kubernetes', 'Monitoring, alerting & incident response'],
                'tags' => ['AWS', 'Docker', 'Terraform'],
                'image' => 'process/launch.jpg',
            ],
            [
                'slug' => 'architecture-consulting',
                'title' => 'Architecture Consulting',
                'excerpt' => 'Independent technical audits and architecture guidance for teams facing scale, performance or reliability challenges.',
                'highlights' => ['Codebase & security audits', 'Performance and scalability reviews', 'Technology roadmap planning', 'Fractional CTO & team mentoring'],
                'tags' => ['System Design', 'Observability', 'SRE'],
                'image' => 'process/observability.jpg',
            ],
        ];
    }
}
