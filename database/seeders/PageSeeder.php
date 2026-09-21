<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Helpers\Settings;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\Concerns\SeedsMissingRecords;
use Illuminate\Database\Seeder;

/**
 * Seeds the legal pages every public website needs (privacy, terms, cookies).
 *
 * Only creates pages that never existed, so it never overwrites a page an admin
 * has edited and never brings back one an admin deleted. The text is a sensible starting template: have it reviewed for
 * your jurisdiction before relying on it.
 *
 *   php artisan db:seed --class=PageSeeder
 */
class PageSeeder extends Seeder
{
    use SeedsMissingRecords;

    public function run(): void
    {
        $author = User::where('email', 'superadmin@gmail.com')->first() ?? User::query()->first();
        $company = Settings::appName();
        $email = Settings::emails()[0] ?? 'hello@example.com';
        $contactUrl = route('contact');

        $pages = [
            'privacy-policy' => ['Privacy Policy', $this->privacy($company, $email, $contactUrl)],
            'terms-of-service' => ['Terms of Service', $this->terms($company, $email, $contactUrl)],
            'cookie-policy' => ['Cookie Policy', $this->cookies($company, $email)],
        ];

        foreach ($pages as $slug => [$title, $content]) {
            if ($this->alreadySeeded(Page::class, $slug)) {
                continue;
            }

            Page::create(
                [
                    'slug' => $slug,
                    'user_id' => $author?->id,
                    'title' => $title,
                    'content' => $content,
                    'status' => CommonStatusEnum::ACTIVE->value,
                    'published_at' => now(),
                ]
            );
        }

        $this->command?->info('PageSeeder: legal pages ensured (existing pages were left untouched).');
    }

    private function privacy(string $company, string $email, string $contactUrl): string
    {
        return <<<HTML
<p>This Privacy Policy explains how {$company} ("we", "us") collects, uses and protects personal information when you visit our website or contact us about a project.</p>

<h2>Information We Collect</h2>
<p>We only collect information you choose to give us, and basic technical data needed to run the website:</p>
<ul>
<li><strong>Contact details</strong> such as your name, email address, phone number and company when you submit our <a href="{$contactUrl}">contact form</a>.</li>
<li><strong>Project information</strong> you share with us, such as your requirements, budget range and timelines.</li>
<li><strong>Technical data</strong> such as IP address, browser type and pages visited, collected through server logs and analytics.</li>
</ul>

<h2>How We Use Your Information</h2>
<ul>
<li>To respond to your enquiry and discuss your project.</li>
<li>To provide, operate and improve our services and website.</li>
<li>To meet legal, accounting and security obligations.</li>
</ul>
<p>We do not sell your personal information, and we never add you to a marketing list without your consent.</p>

<h2>Legal Basis for Processing</h2>
<p>Where data protection laws such as the GDPR apply, we process personal data on the basis of your consent, to take steps at your request before entering into a contract, to comply with legal obligations, or for our legitimate interests in running and improving our business.</p>

<h2>Sharing Your Information</h2>
<p>We share information only with trusted service providers who help us operate our business, such as hosting, email and analytics providers, and only as needed for those services. We may also disclose information if required by law.</p>

<h2>Data Retention</h2>
<p>We keep enquiry information for as long as needed to respond to you and manage any resulting business relationship, and then delete or anonymise it unless we must keep it for legal reasons.</p>

<h2>Your Rights</h2>
<p>Depending on where you live, you may have the right to access, correct, delete or export your personal data, and to object to or restrict certain processing. To make a request, email <a href="mailto:{$email}">{$email}</a>.</p>

<h2>Security</h2>
<p>We use appropriate technical and organisational measures to protect your information, including encrypted connections and restricted access. No method of transmission over the internet is completely secure, but we work hard to protect your data.</p>

<h2>Changes to This Policy</h2>
<p>We may update this policy from time to time. The "Last updated" date at the top of this page shows when it was last revised.</p>

<h2>Contact Us</h2>
<p>If you have questions about this policy or how we handle your data, contact us at <a href="mailto:{$email}">{$email}</a>.</p>
HTML;
    }

    private function terms(string $company, string $email, string $contactUrl): string
    {
        return <<<HTML
<p>These Terms of Service govern your use of the {$company} website. By using this website you agree to these terms. If you do not agree, please do not use the website.</p>

<h2>Use of the Website</h2>
<p>You may browse and use this website for lawful purposes only. You must not attempt to disrupt the website, gain unauthorised access to our systems, or use the website to send spam or harmful content.</p>

<h2>Intellectual Property</h2>
<p>All content on this website, including text, graphics, logos and code samples, is owned by {$company} or its licensors unless stated otherwise. You may share links to our content, but you may not copy or republish it for commercial purposes without our written permission.</p>

<h2>Project Engagements</h2>
<p>Information on this website is for general guidance only and does not form an offer or contract. Any project work is governed by a separate written agreement between you and {$company}, which takes precedence over these terms.</p>

<h2>Enquiries</h2>
<p>When you contact us through the <a href="{$contactUrl}">contact form</a>, you confirm that the information you provide is accurate and that you are authorised to share it. How we handle that information is described in our Privacy Policy.</p>

<h2>Third-Party Links</h2>
<p>This website may link to third-party websites. We are not responsible for their content or practices, and a link does not imply endorsement.</p>

<h2>Disclaimer</h2>
<p>This website is provided "as is". While we work to keep it accurate and available, we make no guarantees that it will be error-free or uninterrupted.</p>

<h2>Limitation of Liability</h2>
<p>To the fullest extent permitted by law, {$company} is not liable for any indirect or consequential loss arising from your use of this website.</p>

<h2>Changes to These Terms</h2>
<p>We may update these terms from time to time. Continued use of the website after changes are published means you accept the updated terms.</p>

<h2>Contact</h2>
<p>Questions about these terms? Email us at <a href="mailto:{$email}">{$email}</a>.</p>
HTML;
    }

    private function cookies(string $company, string $email): string
    {
        return <<<HTML
<p>This Cookie Policy explains how {$company} uses cookies and similar technologies on this website.</p>

<h2>What Are Cookies?</h2>
<p>Cookies are small text files stored on your device when you visit a website. They help the site work properly, remember your preferences and understand how the site is used.</p>

<h2>Cookies We Use</h2>
<table>
<thead>
<tr><th>Type</th><th>Purpose</th><th>Duration</th></tr>
</thead>
<tbody>
<tr><td>Strictly necessary</td><td>Keep the website secure and working, for example session and form security (CSRF) cookies.</td><td>Session</td></tr>
<tr><td>Analytics</td><td>Help us understand which pages are visited so we can improve the website. Only used if analytics is enabled.</td><td>Up to 2 years</td></tr>
</tbody>
</table>

<h2>Managing Cookies</h2>
<p>You can block or delete cookies through your browser settings. Blocking strictly necessary cookies may stop parts of the website, such as the contact form, from working correctly.</p>

<h2>Changes to This Policy</h2>
<p>We may update this Cookie Policy when we change the cookies we use. The "Last updated" date shows the latest revision.</p>

<h2>Contact</h2>
<p>Questions about cookies? Email us at <a href="mailto:{$email}">{$email}</a>.</p>
HTML;
    }
}
