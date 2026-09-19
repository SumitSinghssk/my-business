<?php

namespace App\Http\Controllers;

use App\Services\SitemapBuilder;

class SitemapController extends Controller
{
    public function __invoke(SitemapBuilder $sitemap)
    {
        return response($sitemap->toXml(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
