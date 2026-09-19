<?php

namespace App\Http\Controllers;

use App\Models\Page;

/**
 * Public rendering of CMS pages managed in Admin → Pages (privacy policy,
 * terms, cookie policy, or any other page an admin creates).
 */
class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::published()
            ->with('seo')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('website.pages.show', [
            'page' => $page,
            'content' => $page->contentWithToc(),
        ]);
    }
}
