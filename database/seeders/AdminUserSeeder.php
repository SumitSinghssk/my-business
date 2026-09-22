<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'superadmin@gmail.com',
                'name' => 'Super Admin',
                'role' => 'super admin',
            ],
            [
                'email' => 'admin@gmail.com',
                'name' => 'Admin User',
                'role' => 'admin',
            ],
            [
                'email' => 'developer@gmail.com',
                'name' => 'Developer User',
                'role' => 'developer',
            ],
        ];

        foreach ($users as $userData) {
            // Only create accounts that do not exist yet: re-running must never reset a
            // real password or roles, nor re-create an account an admin deleted.
            if (User::withTrashed()->where('email', $userData['email'])->exists()) {
                continue;
            }

            $password = $this->password();

            $user = User::create([
                'email' => $userData['email'],
                'name' => $userData['name'],
                'password' => Hash::make($password),
                'status' => CommonStatusEnum::ACTIVE->value,
            ]);

            $user->assignRole($userData['role']);

            if (! app()->environment('local', 'testing')) {
                $this->command?->warn("Created {$userData['email']} with password: {$password}  (change it after logging in)");
            }
        }
    }

    /**
     * "password" only on local and test machines. Anywhere else a known password would be a
     * ready-made way in, so use ADMIN_SEED_PASSWORD from .env or a random one (printed above).
     */
    private function password(): string
    {
        if (app()->environment('local', 'testing')) {
            return 'password';
        }

        return env('ADMIN_SEED_PASSWORD') ?: Str::password(20);
    }
}
