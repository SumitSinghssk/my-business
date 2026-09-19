<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Seo;
use Illuminate\Support\Carbon;

/**
 * Builds the XML sitemap from every public, indexable URL on the website.
 * Used by the live /sitemap.xml route and by Admin → Settings → Sitemap.
 */
class SitemapBuilder
{
    /**
     * @return array<int, array{loc: string, lastmod: string, changefreq: string, priority: string, images: array<int, array{loc: string, title: string}>}>
     */
    public function urls(): array
    {
        $latestPost = Blog::published()->latestPublished()->first();
        $siteUpdated = $latestPost?->updated_at ?? now();

        $urls = [
            $this->entry(route('home'), $siteUpdated, 'weekly', '1.0'),
            $this->entry(route('services'), $siteUpdated, 'monthly', '0.9'),
            $this->entry(route('blog.index'), $siteUpdated, 'daily', '0.8'),
            $this->entry(route('about'), $siteUpdated, 'monthly', '0.7'),
            $this->entry(route('contact'), $siteUpdated, 'yearly', '0.6'),
        ];

        Blog::published()->latestPublished()->get(['slug', 'title', 'featured_image', 'updated_at', 'published_at', 'created_at'])
            ->each(function (Blog $blog) use (&$urls) {
                $images = $blog->featured_image_url ? [['loc' => $blog->featured_image_url, 'title' => $blog->title]] : [];
                $urls[] = $this->entry(route('blog.show', $blog->slug), $blog->updated_at, 'monthly', '0.7', $images);
            });

        Page::published()->orderBy('title')->get(['slug', 'updated_at'])
            ->each(function (Page $page) use (&$urls) {
                $urls[] = $this->entry(route('page.show', $page->slug), $page->updated_at, 'yearly', '0.3');
            });

        // Respect "no index" set per URL in Admin → SEO.
        $noindex = Seo::where('index', false)->pluck('slug')->map(fn ($slug) => url($slug === '/' ? '' : $slug))->all();

        return array_values(array_filter($urls, fn ($url) => ! in_array($url['loc'], $noindex, true)));
    }

    public function toXml(?array $urls = null): string
    {
        $urls ??= $this->urls();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>'.$this->escape($url['loc'])."</loc>\n";
            $xml .= '        <lastmod>'.$url['lastmod']."</lastmod>\n";
            $xml .= '        <changefreq>'.$url['changefreq']."</changefreq>\n";
            $xml .= '        <priority>'.$url['priority']."</priority>\n";

            foreach ($url['images'] as $image) {
                $xml .= "        <image:image>\n";
                $xml .= '            <image:loc>'.$this->escape($image['loc'])."</image:loc>\n";
                $xml .= '            <image:title>'.$this->escape($image['title'])."</image:title>\n";
                $xml .= "        </image:image>\n";
            }

            $xml .= "    </url>\n";
        }

        return $xml.'</urlset>';
    }

    private function entry(string $loc, ?Carbon $lastmod, string $changefreq, string $priority, array $images = []): array
    {
        return [
            'loc' => $loc,
            'lastmod' => ($lastmod ?? now())->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'images' => $images,
        ];
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
