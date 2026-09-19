<?php

namespace Database\Seeders;

use App\Helpers\Settings;
use App\Models\Seo;
use Illuminate\Database\Seeder;

/**
 * SEO records for the main website pages, editable in Admin → SEO.
 * The slug is the URL path ("/" for the home page).
 *
 * Uses firstOrCreate so re-running never overwrites what an admin has edited.
 */
class SeoSeeder extends Seeder
{
    public function run(): void
    {
        $app = Settings::appName();

        $records = [
            'default-seo' => [
                'page' => 'Default SEO',
                'meta_title' => "{$app} | Software & Digital Product Studio",
                'meta_description' => 'We design and engineer websites, web applications, mobile apps and custom software built around real business goals.',
            ],
            '/' => [
                'page' => 'Home',
                'meta_title' => "{$app} | Software & Digital Product Studio",
                'meta_description' => 'From websites and mobile apps to custom software, we design and engineer digital products built around real business goals.',
            ],
            'services' => [
                'page' => 'Services',
                'meta_title' => "Software Development Services | {$app}",
                'meta_description' => 'Website development, web applications, mobile apps, custom software, UI/UX design, cloud & DevOps and architecture consulting from one senior team.',
            ],
            'about' => [
                'page' => 'About',
                'meta_title' => "About Us | {$app}",
                'meta_description' => 'Meet the studio: strategists, designers and engineers building reliable digital products for ambitious businesses.',
            ],
            'contact' => [
                'page' => 'Contact',
                'meta_title' => "Contact Us | {$app}",
                'meta_description' => 'Tell us about your project. Our engineering team replies within one business day.',
            ],
            'insights' => [
                'page' => 'Insights',
                'meta_title' => "Insights & Essays | {$app}",
                'meta_description' => 'Perspectives on engineering, systems design, and product craftsmanship from our engineering and design teams.',
            ],
        ];

        foreach ($records as $slug => $data) {
            Seo::firstOrCreate(['slug' => $slug], $data + ['index' => true]);
        }
    }
}
