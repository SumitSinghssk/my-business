<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class WebsiteController extends Controller
{
    public function index()
    {
        $latestPosts = Blog::published()
            ->with('categories')
            ->latestPublished()
            ->take(3)
            ->get();

        return view('website.home.index', compact('latestPosts'));
    }

    public function services()
    {
        return view('website.services.index');
    }

    public function about()
    {
        return view('website.about.index');
    }
}
