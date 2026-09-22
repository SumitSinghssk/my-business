<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Enums\CommonStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogStoreRequest;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.blogs.view');

        $query = Blog::with(['author', 'categories']);

        // Filter by Search (Title or Excerpt)
        if ($request->filled('search')) {
            $term = '%'.addcslashes($request->search, '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('excerpt', 'like', $term);
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Category (Many-to-Many relationship)
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('blog_categories.id', $request->category_id);
            });
        }

        $blogs = $query->latest()
            ->paginate(20)
            ->withQueryString();

        // Get categories for the filter dropdown
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        Gate::authorize('admin.blogs.create');

        $categories = BlogCategory::whereNull('parent_id')
            ->where('status', 'active')
            ->with(['children' => fn ($q) => $q->where('status', 'active')->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(BlogStoreRequest $request)
    {
        Gate::authorize('admin.blogs.create');

        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $data['slug'] = $data['slug'] ?? str()->slug($data['title']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageProcessor::class)->store($request->file('featured_image'), 'blog', $request->input('featured_image_crop'));
        }

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        $blog = Blog::create($data);
        $blog->categories()->sync($categoryIds);

        if (Gate::allows('admin.blogs.edit')) {
            return to_route('admin.blogs.edit', $blog)->with('success', 'Blog Post Created');
        }

        return to_route('admin.blogs.index')->with('success', 'Blog Post Created');
    }

    public function edit(Blog $blog)
    {
        Gate::authorize('admin.blogs.edit');

        $blog->load(['seo', 'categories']);

        // Categories the post already has stay pickable even if they were deactivated since.
        $selected = $blog->categories->pluck('id');

        $categories = BlogCategory::whereNull('parent_id')
            ->where(fn ($q) => $q->where('status', 'active')
                ->orWhereIn('id', $selected)
                ->orWhereHas('children', fn ($c) => $c->whereIn('id', $selected)))
            ->with(['children' => fn ($q) => $q->where(fn ($w) => $w->where('status', 'active')->orWhereIn('id', $selected))->orderBy('name')])
            ->orderBy('name')
            ->get();

        $selectedCategoryIds = $blog->categories->pluck('id')->toArray();

        return view('admin.blogs.edit', compact('blog', 'categories', 'selectedCategoryIds'));
    }

    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        Gate::authorize('admin.blogs.edit');

        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            // The old file is only removed once the new one has been saved.
            $data['featured_image'] = app(ImageProcessor::class)->store($request->file('featured_image'), 'blog', $request->input('featured_image_crop'), $blog->featured_image);
        } elseif ($request->boolean('remove_image')) {
            if ($blog->featured_image) {
                app(ImageProcessor::class)->delete($blog->featured_image);
            }
            $data['featured_image'] = null;
        }

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);
        unset($data['remove_image']);

        $blog->update($data);
        $blog->categories()->sync($categoryIds);

        return back()->with('success', 'Blog Post Updated');
    }

    public function destroy(Blog $blog)
    {
        Gate::authorize('admin.blogs.delete');

        $blog->delete();

        return to_route('admin.blogs.index')->with('success', 'Blog Post deleted');
    }

    public function toggleStatus(Blog $blog)
    {
        Gate::authorize('admin.blogs.toogle-status');

        $blog->status = $blog->status === CommonStatusEnum::ACTIVE ? CommonStatusEnum::INACTIVE : CommonStatusEnum::ACTIVE;
        $blog->save();

        return response()->json([
            'status' => $blog->status,
            'message' => 'Status updated successfully',
        ]);
    }
}
