<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'profile.view',
            'profile.update',
            'profile.update-password',

            'admin.seo.view',
            'admin.seo.create',
            'admin.seo.edit',
            'admin.seo.delete',

            'admin.settings.view',
            'admin.settings.basic-details.view',
            'admin.settings.basic-details.update',
            'admin.settings.scripts.view',
            'admin.settings.scripts.update',
            'admin.settings.clear-cache',
            'admin.settings.download-db',
            'admin.settings.sitemap.view',
            'admin.settings.sitemap.update',
            'admin.settings.robots.view',
            'admin.settings.robots.update',

            'admin.activity-logs.view',
            'admin.activity-logs.clear',

            'admin.roles.view',
            'admin.roles.create',
            'admin.roles.update',
            'admin.roles.delete',

            'admin.permissions.view',
            'admin.permissions.create',
            'admin.permissions.delete',

            'admin.users.view',
            'admin.users.create',
            'admin.users.edit',
            'admin.users.delete',
            'admin.users.toogle-status',

            'admin.notifications.view',
            'admin.notifications.mark-all-as-read',

            'admin.blog-categories.view',
            'admin.blog-categories.create',
            'admin.blog-categories.edit',
            'admin.blog-categories.delete',
            'admin.blog-categories.toogle-status',

            'admin.blogs.view',
            'admin.blogs.create',
            'admin.blogs.edit',
            'admin.blogs.delete',
            'admin.blogs.toogle-status',

            'admin.pages.view',
            'admin.pages.create',
            'admin.pages.edit',
            'admin.pages.delete',
            'admin.pages.toogle-status',

            'admin.services.view',
            'admin.services.create',
            'admin.services.edit',
            'admin.services.delete',
            'admin.services.toogle-status',

            'admin.projects.view',
            'admin.projects.create',
            'admin.projects.edit',
            'admin.projects.delete',
            'admin.projects.toogle-status',

            'admin.log-settings.view',
            'admin.log-settings.delete',

            'admin.enquiries.view',
            'admin.enquiries.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        Role::firstOrCreate(['name' => 'super admin'])->syncPermissions(Permission::all());

        // Default permissions are only applied when a role is first created, so
        // re-running the seeder never undoes changes made in Roles & Permissions.
        $defaults = [
            'admin' => ['dashboard.view', 'profile.view', 'profile.update', 'profile.update-password'],
            'developer' => ['dashboard.view', 'profile.view'],
            'sales' => ['dashboard.view'],
        ];

        foreach ($defaults as $name => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $name]);

            if ($role->wasRecentlyCreated) {
                $role->syncPermissions($rolePermissions);
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
