<?php

namespace Database\Seeders;

use App\Helpers\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SettingSeeder::class,
            AdminUserSeeder::class,
            SeoSeeder::class,
            PageSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            BlogSeeder::class,
        ]);

        // WithoutModelEvents skips the model hooks that normally clear these caches.
        Cache::forget('seo_all');
        Settings::flush();
    }
}
