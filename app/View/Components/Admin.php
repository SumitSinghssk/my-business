<?php

namespace App\View\Components;

use App\Models\Enquiry;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Admin extends Component
{
    public array $links;

    public function __construct()
    {
        $unreadEnquiries = auth()->user()?->can('admin.enquiries.view')
            ? Enquiry::whereNull('seen_at')->count()
            : 0;

        $this->links = [
            [
                'section' => 'Overview',
                'items' => [
                    [
                        'title' => 'Dashboard',
                        'route' => route('admin.dashboard'),
                        'icon' => 'dashboard',
                        'active' => 'admin.dashboard',
                        'permission' => 'dashboard.view',
                    ],
                    [
                        'title' => 'Enquiries',
                        'route' => route('admin.enquiries.index'),
                        'icon' => 'inbox',
                        'active' => 'admin.enquiries.*',
                        'permission' => 'admin.enquiries.view',
                        'badge' => $unreadEnquiries ?: null,
                    ],
                ],
            ],
            [
                'section' => 'Content',
                'items' => [
                    [
                        'title' => 'Blogs',
                        'route' => route('admin.blogs.index'),
                        'icon' => 'newspaper',
                        'active' => 'admin.blogs.*',
                        'permission' => 'admin.blogs.view',
                    ],
                    [
                        'title' => 'Categories',
                        'route' => route('admin.blog-categories.index'),
                        'icon' => 'tag',
                        'active' => 'admin.blog-categories.*',
                        'permission' => 'admin.blog-categories.view',
                    ],
                    [
                        'title' => 'Services',
                        'route' => route('admin.services.index'),
                        'icon' => 'layers',
                        'active' => 'admin.services.*',
                        'permission' => 'admin.services.view',
                    ],
                    [
                        'title' => 'Work',
                        'route' => route('admin.projects.index'),
                        'icon' => 'briefcase',
                        'active' => 'admin.projects.*',
                        'permission' => 'admin.projects.view',
                    ],
                    [
                        'title' => 'Pages',
                        'route' => route('admin.pages.index'),
                        'icon' => 'file-text',
                        'active' => 'admin.pages.*',
                        'permission' => 'admin.pages.view',
                    ],
                    [
                        'title' => 'SEO',
                        'route' => route('admin.seo.index'),
                        'icon' => 'globe',
                        'active' => 'admin.seo.*',
                        'permission' => 'admin.seo.view',
                    ],
                ],
            ],
            [
                'section' => 'System',
                'items' => [
                    [
                        'title' => 'Users',
                        'route' => route('admin.users.index'),
                        'icon' => 'users',
                        'active' => 'admin.users.*',
                        'permission' => 'admin.users.view',
                    ],
                    [
                        'title' => 'Roles & Permissions',
                        'route' => route('admin.roles.index'),
                        'icon' => 'shield-check',
                        'active' => 'admin.roles.*',
                        'permission' => 'admin.roles.view',
                    ],
                    [
                        'title' => 'Activity Logs',
                        'route' => route('admin.activity-logs.index'),
                        'icon' => 'activity',
                        'active' => 'admin.activity-logs.*',
                        'permission' => 'admin.activity-logs.view',
                    ],
                    [
                        'title' => 'Settings',
                        'route' => route('admin.settings.index'),
                        'icon' => 'settings',
                        'active' => 'admin.settings.*',
                        'permission' => 'admin.settings.view',
                    ],
                ],
            ],
        ];
    }

    /** "New …" shortcuts for the top bar's Create menu and the command palette (permission-filtered). */
    protected function quickCreate(): array
    {
        $user = auth()->user();

        return collect([
            ['title' => 'Blog post', 'icon' => 'newspaper', 'route' => 'admin.blogs.create', 'permission' => 'admin.blogs.create'],
            ['title' => 'Page', 'icon' => 'file-text', 'route' => 'admin.pages.create', 'permission' => 'admin.pages.create'],
            ['title' => 'Service', 'icon' => 'layers', 'route' => 'admin.services.create', 'permission' => 'admin.services.create'],
            ['title' => 'Work project', 'icon' => 'briefcase', 'route' => 'admin.projects.create', 'permission' => 'admin.projects.create'],
            ['title' => 'Blog category', 'icon' => 'tag', 'route' => 'admin.blog-categories.create', 'permission' => 'admin.blog-categories.create'],
            ['title' => 'SEO record', 'icon' => 'globe', 'route' => 'admin.seo.create', 'permission' => 'admin.seo.create'],
            ['title' => 'User', 'icon' => 'users', 'route' => 'admin.users.create', 'permission' => 'admin.users.create'],
        ])
            ->filter(fn ($item) => $user?->can($item['permission']))
            ->map(fn ($item) => [...$item, 'url' => route($item['route'])])
            ->values()
            ->all();
    }

    public function render(): View|Closure|string
    {
        return view('layouts.admin', [
            'links' => $this->links,
            'quickCreate' => $this->quickCreate(),
        ]);
    }
}
