<?php

namespace Database\Seeders;

use App\Helpers\Settings;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Placeholder contact details for Admin → Settings → Basic (emails, phones, office + map, social links),
 * so the contact page, footer and Organization schema have something to show.
 *
 * Only empty fields are filled: anything already saved in the admin panel is kept.
 * Replace these values with your real details in Admin → Settings.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $address = "100 Pine Street, Suite 1200\nSan Francisco, CA 94111\nUnited States";

        $defaults = [
            'app_name' => config('app.name'),
            'emails' => ['hello@example.com', 'projects@example.com'],
            'phones' => ['+1 (555) 010-2040', '+1 (555) 010-2041'],
            'addresses' => [
                [
                    'label' => 'Head office',
                    'text' => $address,
                    // Rebuilt by mapEmbed() exactly as the admin form stores it.
                    'map_iframe' => Settings::mapEmbed('https://www.google.com/maps?q='.urlencode('100 Pine Street, San Francisco, CA 94111').'&output=embed'),
                ],
            ],
            'social_links' => [
                ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/example'],
                ['platform' => 'GitHub', 'url' => 'https://github.com/example'],
                ['platform' => 'X (Twitter)', 'url' => 'https://x.com/example'],
                ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/example'],
            ],
        ];

        $setting = Setting::firstOrCreate(['key' => 'basic_settings'], ['value' => []]);
        $value = is_array($setting->value) ? $setting->value : [];

        foreach ($defaults as $key => $default) {
            if (empty($value[$key])) {
                $value[$key] = $default;
            }
        }

        $value['logo'] ??= ['light' => null, 'dark' => null];

        $setting->value = $value;
        $setting->save();

        Settings::flush();
    }
}
