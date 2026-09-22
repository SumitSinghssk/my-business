<?php

namespace App\View\Composers;

use App\Helpers\Settings;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Data for the website footer (layouts/partials/website/footer): link columns, contact details and legal pages.
 */
class WebsiteFooterComposer
{
    public function compose(View $view): void
    {
        $columns = [
            'Company' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Services', 'url' => route('services')],
                ['label' => 'Work', 'url' => route('work.index')],
                ['label' => 'About', 'url' => route('about')],
                ['label' => 'Insights', 'url' => route('blog.index')],
                ['label' => 'Contact', 'url' => route('contact')],
            ],
        ];

        $services = Service::active()->ordered()->take(6)->get(['title', 'slug']);
        if ($services->isNotEmpty()) {
            $columns['Services'] = $services
                ->map(fn ($service) => ['label' => $service->title, 'url' => route('services.show', $service->slug)])
                ->all();
        }

        $posts = Blog::published()->latestPublished()->take(4)->get(['title', 'slug']);
        if ($posts->isNotEmpty()) {
            $columns['Latest Insights'] = $posts
                ->map(fn ($post) => ['label' => Str::limit($post->title, 42), 'url' => route('blog.show', $post->slug)])
                ->all();
        }

        $view->with([
            'appName' => Settings::appName(),
            'columns' => $columns,
            'email' => Settings::emails()[0] ?? null,
            'phone' => Settings::phones()[0] ?? null,
            'address' => Settings::addresses()[0]['text'] ?? null,
            'socialLinks' => Settings::socialLinks(),
            'legalPages' => Page::published()->orderBy('title')->get(['title', 'slug']),
        ]);
    }
}
