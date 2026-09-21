<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Project;
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
 * Seeds the case studies shown on the public Work pages.
 *
 * Safe to re-run: only projects that never existed are created. Existing ones
 * (possibly edited in the admin panel) and ones deleted there are left
 * untouched. Case study content lives
 * in database/seeders/content/projects/{slug}.html and images are taken from
 * public/images/website (cropped to the `project` preset). Run ServiceSeeder
 * first so projects can be linked to their service.
 *
 *   php artisan db:seed --class=ProjectSeeder
 */
class ProjectSeeder extends Seeder
{
    use SeedsMissingRecords;

    private const CONTENT_PATH = 'seeders/content/projects';

    private const PERMISSIONS = [
        'admin.projects.view',
        'admin.projects.create',
        'admin.projects.edit',
        'admin.projects.delete',
        'admin.projects.toogle-status',
    ];

    public function run(): void
    {
        $this->grantPermissions();

        $author = User::where('email', 'superadmin@gmail.com')->first() ?? User::query()->first();
        $services = Service::pluck('id', 'slug');

        $created = 0;
        foreach ($this->projects() as $order => $item) {
            if ($this->alreadySeeded(Project::class, $item['slug'])) {
                continue;
            }

            Project::create(
                [
                    'slug' => $item['slug'],
                    'user_id' => $author?->id,
                    'service_id' => $services[$item['service']] ?? null,
                    'title' => $item['title'],
                    'client' => $item['client'],
                    'industry' => $item['industry'],
                    'year' => $item['year'],
                    'excerpt' => $item['excerpt'],
                    'results' => $item['results'],
                    'tags' => $item['tags'],
                    'content' => File::get(database_path(self::CONTENT_PATH."/{$item['slug']}.html")),
                    'featured_image' => $this->storeImage($item['image']),
                    'is_featured' => $item['featured'],
                    'sort_order' => $order + 1,
                    'status' => CommonStatusEnum::ACTIVE->value,
                ]
            );
            $created++;
        }

        $this->command?->info("ProjectSeeder: {$created} projects created (existing ones left untouched).");
    }

    /**
     * Create the project permissions and give them to super admins only, without
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

        return File::exists($source) ? app(ImageProcessor::class)->store($source, 'project') : null;
    }

    private function projects(): array
    {
        return [
            [
                'slug' => 'orion-systems',
                'title' => 'Orion Systems',
                'client' => 'Orion Systems',
                'industry' => 'Enterprise SaaS',
                'year' => '2025',
                'service' => 'web-applications',
                'excerpt' => 'A multi-cloud network console streaming live telemetry from 24,000 cluster nodes, with resource reallocation from the same screen.',
                'results' => [
                    ['value' => '24,000+', 'label' => 'Nodes monitored live'],
                    ['value' => '3', 'label' => 'Clouds in one console'],
                    ['value' => '1', 'label' => 'Shared incident view'],
                ],
                'tags' => ['Next.js', 'TypeScript', 'Go', 'TimescaleDB'],
                'image' => 'work/orion-systems.jpg',
                'featured' => true,
            ],
            [
                'slug' => 'apex-pay-logistics',
                'title' => 'Apex Pay & Logistics',
                'client' => 'Apex Logistics',
                'industry' => 'Fintech & Logistics',
                'year' => '2025',
                'service' => 'mobile-app-development',
                'excerpt' => 'An offline-first driver app for 6,000+ vehicles with live routing, proof of delivery and same-day driver settlement.',
                'results' => [
                    ['value' => '6,000+', 'label' => 'Vehicles on the platform'],
                    ['value' => 'Same day', 'label' => 'Driver settlement'],
                    ['value' => '2', 'label' => 'Platforms, one codebase'],
                ],
                'tags' => ['Flutter', 'AWS IoT', 'PostgreSQL'],
                'image' => 'work/apex-pay-logistics.jpg',
                'featured' => true,
            ],
            [
                'slug' => 'genosync-diagnostics',
                'title' => 'GenoSync Diagnostics',
                'client' => 'GenoSync',
                'industry' => 'Healthtech & AI',
                'year' => '2024',
                'service' => 'custom-software-development',
                'excerpt' => 'A secure genomic analysis pipeline and clinician portal that turns sequencing results into reviewed reports in hours.',
                'results' => [
                    ['value' => 'Hours', 'label' => 'Analysis turnaround, not days'],
                    ['value' => '100%', 'label' => 'Steps audit-logged'],
                    ['value' => 'Auto', 'label' => 'Scales with sample volume'],
                ],
                'tags' => ['Python', 'PyTorch', 'FastAPI', 'React', 'Kubernetes'],
                'image' => 'work/genosync-diagnostics.jpg',
                'featured' => true,
            ],
            [
                'slug' => 'harborview-estates',
                'title' => 'Harborview Estates',
                'client' => 'Harborview Estates',
                'industry' => 'Real Estate',
                'year' => '2025',
                'service' => 'website-development',
                'excerpt' => 'A fast property website synced with the agency CRM, with map search, viewing bookings and listing alerts.',
                'results' => [
                    ['value' => '<2s', 'label' => 'Mobile load time'],
                    ['value' => '0', 'label' => 'Listings entered twice'],
                    ['value' => '4', 'label' => 'Office sites in one CMS'],
                ],
                'tags' => ['Laravel', 'Tailwind CSS', 'Mapbox'],
                'image' => 'industries/real-estate.jpg',
                'featured' => false,
            ],
            [
                'slug' => 'summit-stays',
                'title' => 'Summit Stays',
                'client' => 'Summit Stays',
                'industry' => 'Hospitality & Travel',
                'year' => '2024',
                'service' => 'mobile-app-development',
                'excerpt' => 'A guest app for boutique hotels with direct booking, mobile check-in, digital keys and in-stay requests.',
                'results' => [
                    ['value' => 'Direct', 'label' => 'Bookings without commission'],
                    ['value' => 'Mobile', 'label' => 'Check-in and room keys'],
                    ['value' => 'Live', 'label' => 'Guest requests to staff'],
                ],
                'tags' => ['React Native', 'Node.js', 'Stripe'],
                'image' => 'industries/hospitality-travel.jpg',
                'featured' => false,
            ],
            [
                'slug' => 'brightpath-learning',
                'title' => 'Brightpath Learning',
                'client' => 'Brightpath Learning',
                'industry' => 'Education',
                'year' => '2024',
                'service' => 'web-applications',
                'excerpt' => 'A custom learning platform with a course builder, automatic certificates and live progress dashboards for corporate clients.',
                'results' => [
                    ['value' => 'Instant', 'label' => 'Client progress reports'],
                    ['value' => 'Auto', 'label' => 'Certificates on completion'],
                    ['value' => 'Any device', 'label' => 'Resume where you left off'],
                ],
                'tags' => ['Laravel', 'Vue.js', 'MySQL'],
                'image' => 'industries/education.jpg',
                'featured' => false,
            ],
        ];
    }
}
