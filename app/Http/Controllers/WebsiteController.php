<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Project;

class WebsiteController extends Controller
{
    public function index()
    {
        $latestPosts = Blog::published()
            ->with(['categories' => fn ($q) => $q->active()])
            ->latestPublished()
            ->take(3)
            ->get();

        // Projects ticked "Feature on the home page" first, topped up with the latest others.
        $featuredProjects = Project::active()
            ->orderByDesc('is_featured')
            ->ordered()
            ->take(3)
            ->get();

        return view('website.home.index', compact('latestPosts', 'featuredProjects'));
    }

    public function about()
    {
        return view('website.about.index');
    }
}
