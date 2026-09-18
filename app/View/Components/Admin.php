<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Admin extends Component
{
    public array $links;

    public function __construct()
    {
        $this->links = [
            [
                'section' => 'Main',
                'items' => [
                    [
                        'title' => 'Dashboard',
                        'route' => route('admin.dashboard'),
                        'icon' => 'dashboard',
                        'active' => 'admin.dashboard',
                        'permission' => 'dashboard.view',
                    ],
                ],
            ],
            [
                'section' => 'CRM',
                'items' => [
                    [
                        'title' => 'Enquiries',
                        'route' => route('admin.enquiries.index'),
                        'icon' => 'mail',
                        'active' => 'admin.enquiries.*',
                        'permission' => 'admin.enquiries.view',
                    ],
                ],
            ],
            [
                'section' => 'Content',
                'items' => [
                    [
                        'title' => 'SEO',
                        'route' => route('admin.seo.index'),
                        'icon' => 'seo',
                        'active' => 'admin.seo.*',
                        'permission' => 'admin.seo.view',
                    ],
                    [
                        'title' => 'Blog Categories',
                        'route' => route('admin.blog-categories.index'),
                        'icon' => 'category',
                        'active' => 'admin.blog-categories.*',
                        'permission' => 'admin.blog-categories.view',
                    ],
                    [
                        'title' => 'Blogs',
                        'route' => route('admin.blogs.index'),
                        'icon' => 'blog',
                        'active' => 'admin.blogs.*',
                        'permission' => 'admin.blogs.view',
                    ],
                    [
                        'title' => 'Pages',
                        'route' => route('admin.pages.index'),
                        'icon' => 'pages',
                        'active' => 'admin.pages.*',
                        'permission' => 'admin.pages.view',
                    ],
                ],
            ],
            [
                'section' => 'System',
                'items' => [
                    [
                        'title' => 'Settings',
                        'route' => route('admin.settings.index'),
                        'icon' => 'setting',
                        'active' => 'admin.settings.*',
                        'permission' => 'admin.settings.view',
                    ],
                    [
                        'title' => 'Activity Logs',
                        'route' => route('admin.activity-logs.index'),
                        'icon' => 'activity',
                        'active' => 'admin.activity-logs.*',
                        'permission' => 'admin.activity-logs.view',
                    ],
                    [
                        'title' => 'Roles & Permissions',
                        'route' => route('admin.roles.index'),
                        'icon' => 'verified-user',
                        'active' => 'admin.roles.*',
                        'permission' => 'admin.roles.view',
                    ],
                    [
                        'title' => 'Users',
                        'route' => route('admin.users.index'),
                        'icon' => 'users',
                        'active' => 'admin.users.*',
                        'permission' => 'admin.users.view',
                    ],
                ],
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('layouts.admin', [
            'links' => $this->links,
        ]);
    }
}
