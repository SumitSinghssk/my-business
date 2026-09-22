<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private const PER_PAGE = 9;

    public function index(Request $request)
    {
        $categories = BlogCategory::active()
            ->whereHas('blogs', fn (Builder $q) => $q->published())
            ->withCount(['blogs' => fn (Builder $q) => $q->published()])
            ->orderBy('name')
            ->get();

        $activeCategory = $categories->firstWhere('slug', $request->query('category'));

        $blogs = Blog::published()
            ->with(['categories' => fn ($q) => $q->active()])
            ->when($activeCategory, fn (Builder $q) => $q->whereHas('categories', fn (Builder $c) => $c->whereKey($activeCategory->id)))
            ->latestPublished()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // A page past the end is not an empty listing: return a real 404 (no "soft 404" for search engines).
        abort_if($blogs->currentPage() > max($blogs->lastPage(), 1), 404);

        $data = [
            'blogs' => $blogs,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'totalPublished' => $activeCategory ? Blog::published()->count() : $blogs->total(),
        ];

        // Category tabs fetch only the listing fragment; never cache it under the page URL.
        if ($request->header('X-Blog-Fragment')) {
            return response()
                ->view('website.blog.partials.listing', $data)
                ->header('Vary', 'X-Blog-Fragment')
                ->header('Cache-Control', 'no-store');
        }

        return view('website.blog.index', $data);
    }

    public function show(string $slug)
    {
        $blog = Blog::published()
            ->with(['author', 'categories' => fn ($q) => $q->active(), 'seo'])
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = $blog->categories->pluck('id');

        $related = Blog::published()
            ->with(['categories' => fn ($q) => $q->active()])
            ->whereKeyNot($blog->id)
            ->whereHas('categories', fn (Builder $q) => $q->whereIn('blog_categories.id', $categoryIds))
            ->latestPublished()
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->concat(
                Blog::published()
                    ->with(['categories' => fn ($q) => $q->active()])
                    ->whereKeyNot($blog->id)
                    ->whereKeyNot($related->pluck('id')->all())
                    ->latestPublished()
                    ->take(3 - $related->count())
                    ->get()
            );
        }

        return view('website.blog.show', [
            'blog' => $blog,
            'content' => $blog->contentWithToc(),
            'related' => $related,
            'authorPostCount' => $blog->user_id ? Blog::published()->where('user_id', $blog->user_id)->count() : 0,
        ]);
    }
}
