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
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort') === 'oldest' ? 'oldest' : 'latest';

        $categories = BlogCategory::active()
            ->whereHas('blogs', fn (Builder $q) => $q->published())
            ->withCount(['blogs' => fn (Builder $q) => $q->published()])
            ->orderBy('name')
            ->get();

        $activeCategory = $categories->firstWhere('slug', $request->query('category'));

        $blogs = Blog::published()
            ->with(['author', 'categories'])
            ->when($activeCategory, fn (Builder $q) => $q->whereHas('categories', fn (Builder $c) => $c->whereKey($activeCategory->id)))
            ->when($search !== '', fn (Builder $q) => $q->where(fn (Builder $s) => $s
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->when(
                $sort === 'oldest',
                fn (Builder $q) => $q->orderByRaw('COALESCE(published_at, created_at) ASC')->orderBy('id'),
                fn (Builder $q) => $q->latestPublished()
            )
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // The editorial layout (featured + spotlight) only applies to the
        // unfiltered first page; filtered results and later pages use the grid.
        $isEditorial = $blogs->onFirstPage() && ! $activeCategory && $search === '' && $sort === 'latest';
        $items = $blogs->getCollection();

        return view('website.blog.index', [
            'blogs' => $blogs,
            'featured' => $isEditorial ? $items->first() : null,
            'spotlight' => $isEditorial ? $items->slice(1, 2) : collect(),
            'archive' => $isEditorial ? $items->slice(3) : $items,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'totalPublished' => Blog::published()->count(),
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    public function show(string $slug)
    {
        $blog = Blog::published()
            ->with(['author', 'categories', 'seo'])
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = $blog->categories->pluck('id');

        $related = Blog::published()
            ->with('categories')
            ->whereKeyNot($blog->id)
            ->whereHas('categories', fn (Builder $q) => $q->whereIn('blog_categories.id', $categoryIds))
            ->latestPublished()
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->concat(
                Blog::published()
                    ->with('categories')
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
        ]);
    }
}
