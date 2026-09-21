<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;

/**
 * SEO records for the main website pages, editable in Admin → SEO.
 * The slug is the URL path ("/" for the home page).
 *
 * The FAQs shown by <x-website.faq> on each page also live here (FAQs tab).
 *
 * Uses firstOrCreate so re-running never overwrites what an admin has edited;
 * FAQs are only filled in on records that have none yet.
 */
class SeoSeeder extends Seeder
{
    public function run(): void
    {
        // {site_name} is replaced with the current site name when the page renders.
        $app = Seo::SITE_NAME;

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
                'faqs' => [
                    ['question' => 'What does a typical project cost?', 'answer' => 'Most websites start from around $10k and most custom web or mobile products range from $25k to $150k, depending on scope. After a short discovery call we provide a detailed, fixed-scope estimate.'],
                    ['question' => 'How long does it take to build a product?', 'answer' => 'Marketing websites usually take 4 to 8 weeks. Web and mobile applications typically take 8 to 16 weeks for a first production release, delivered in two-week sprints with working software at every step.'],
                    ['question' => 'Which technologies do you use?', 'answer' => 'We choose proven technology that fits your team and goals, most often Laravel, React, Next.js, Flutter, Node.js, PostgreSQL and AWS. We avoid experimental stacks that would be hard for you to maintain.'],
                    ['question' => 'Will I own the source code?', 'answer' => 'Yes. You own 100% of the code, designs and documentation, and everything lives in your own repositories and cloud accounts from day one.'],
                ],
            ],
            'about' => [
                'page' => 'About',
                'meta_title' => "About Us: Our Studio, Team & Approach | {$app}",
                'meta_description' => 'Meet the studio: strategists, designers and engineers building reliable digital products for ambitious businesses.',
            ],
            'contact' => [
                'page' => 'Contact',
                'meta_title' => "Contact Us: Start Your Project | {$app}",
                'meta_description' => 'Tell us about your project. Our engineering team replies within one business day.',
                'faqs' => [
                    ['question' => 'How soon can you start?', 'answer' => 'Most engagements within two to three weeks of signing. For urgent work we can often begin a discovery phase sooner.'],
                    ['question' => 'Do you work with early-stage startups?', 'answer' => 'Yes. We work with funded startups and established enterprises alike. For MVPs we focus on the smallest product that proves your idea, built on foundations that can scale.'],
                    ['question' => 'Who owns the code and intellectual property?', 'answer' => 'You do. All source code, designs and documentation are transferred to you in full, with repositories in your own accounts from day one.'],
                    ['question' => 'Can you take over an existing codebase?', 'answer' => 'Absolutely. We start with a technical audit covering architecture, security, performance and test coverage, then agree a plan to stabilise and improve it.'],
                    ['question' => 'Do you offer support after launch?', 'answer' => 'Yes. We offer ongoing engineering retainers covering monitoring, maintenance, security updates and continued feature development.'],
                ],
            ],
            'insights' => [
                'page' => 'Insights',
                'meta_title' => "Insights & Essays | {$app}",
                'meta_description' => 'Perspectives on engineering, systems design, and product craftsmanship from our engineering and design teams.',
            ],
        ];

        foreach ($records as $slug => $data) {
            $seo = Seo::firstOrCreate(['slug' => $slug], $data + ['index' => true]);

            if (! empty($data['faqs']) && empty($seo->faqs)) {
                $seo->update(['faqs' => $data['faqs']]);
            }
        }
    }
}
