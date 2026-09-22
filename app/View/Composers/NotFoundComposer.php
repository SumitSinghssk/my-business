<?php

namespace App\View\Composers;

use App\Models\Blog;
use Illuminate\View\View;

/**
 * Data for the 404 page: a few recent articles to point visitors somewhere useful.
 */
class NotFoundComposer
{
    public function compose(View $view): void
    {
        try {
            $latestPosts = Blog::published()->latestPublished()->take(3)->get(['title', 'slug']);
        } catch (\Throwable) {
            // The 404 page must still render when the database is unavailable.
            $latestPosts = collect();
        }

        $view->with('latestPosts', $latestPosts);
    }
}
