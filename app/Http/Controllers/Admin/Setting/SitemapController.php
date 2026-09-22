<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SitemapBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SitemapController extends Controller
{
    public function generate(Request $request, SitemapBuilder $sitemap)
    {
        Gate::authorize('admin.settings.sitemap.update');

        try {
            $sitemapXml = $sitemap->toXml();

            if (file_put_contents(public_path('sitemap.xml'), $sitemapXml) === false) {
                return back()->with('error', 'Failed to write sitemap.xml. Please check that the public directory is writable.');
            }

            Setting::updateOrCreate(
                ['key' => 'sitemap_info'],
                [
                    'value' => [
                        'lastGenerated' => now()->format('Y-m-d H:i:s'),
                        'fileSize' => $this->formatBytes(strlen($sitemapXml)),
                        'totalUrls' => substr_count($sitemapXml, '<url>'),
                    ],
                ]
            );

            return redirect()
                ->to(route('admin.settings.index', ['tab' => 'sitemap']))
                ->with('success', 'Sitemap generated successfully.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to generate sitemap: '.$e->getMessage());
        }
    }

    public function upload(Request $request)
    {
        Gate::authorize('admin.settings.sitemap.update');

        $validator = Validator::make($request->all(), [
            'sitemap' => 'required|file|mimes:xml|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $content = file_get_contents($request->file('sitemap')->getPathname());

            $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NONET);

            if ($xml === false) {
                return back()->withErrors(['sitemap' => 'The file contains invalid XML.']);
            }

            // The file is served from the site's own domain, so only accept a real sitemap:
            // any other XML (e.g. an XHTML document with <script>) could run in visitors' browsers.
            if (! $this->isSafeSitemap($content)) {
                return back()->withErrors(['sitemap' => 'The file must be a standard sitemap (<urlset> or <sitemapindex> in the sitemaps.org namespace).']);
            }

            if (file_put_contents(public_path('sitemap.xml'), $content) === false) {
                return back()->with('error', 'Failed to write sitemap.xml. Please check that the public directory is writable.');
            }

            Setting::updateOrCreate(
                ['key' => 'sitemap_info'],
                [
                    'value' => [
                        'lastGenerated' => now()->format('Y-m-d H:i:s'),
                        'fileSize' => $this->formatBytes(strlen($content)),
                        'totalUrls' => substr_count($content, '<url>'),
                        'uploaded' => true,
                    ],
                ]
            );

            return redirect()
                ->to(route('admin.settings.index', ['tab' => 'sitemap']))
                ->with('success', 'Sitemap uploaded successfully.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to upload sitemap: '.$e->getMessage());
        }
    }

    public function download()
    {
        Gate::authorize('admin.settings.sitemap.view');

        $filePath = public_path('sitemap.xml');

        if (! file_exists($filePath)) {
            return back()->with('error', 'Sitemap file not found.');
        }

        return response()->download($filePath, 'sitemap.xml', [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, $precision).' '.$units[$i];
    }

    /**
     * A standard sitemap: <urlset>/<sitemapindex> root in the sitemaps.org namespace,
     * and every element in a known sitemap extension namespace (XHTML only for the
     * hreflang <xhtml:link>). Rejects documents that could carry script markup.
     */
    private function isSafeSitemap(string $content): bool
    {
        $sitemapNs = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $allowed = [
            $sitemapNs,
            'http://www.google.com/schemas/sitemap-image/1.1',
            'http://www.google.com/schemas/sitemap-video/1.1',
            'http://www.google.com/schemas/sitemap-news/0.9',
        ];

        $dom = new \DOMDocument;
        if (! @$dom->loadXML($content, LIBXML_NONET)) {
            return false;
        }

        $root = $dom->documentElement;
        if (! $root || $root->namespaceURI !== $sitemapNs || ! in_array($root->localName, ['urlset', 'sitemapindex'], true)) {
            return false;
        }

        // A DOCTYPE (entities) or processing instruction (an xml-stylesheet one can run XSLT) has no place in a sitemap.
        if ($dom->doctype !== null || (new \DOMXPath($dom))->query('//processing-instruction()')->length > 0) {
            return false;
        }

        foreach ($dom->getElementsByTagName('*') as $element) {
            $isHreflangLink = $element->namespaceURI === 'http://www.w3.org/1999/xhtml' && $element->localName === 'link';

            if (! $isHreflangLink && ! in_array($element->namespaceURI, $allowed, true)) {
                return false;
            }

            // Attributes are where script hides (onload=, href="javascript:"): only the hreflang
            // link's rel/hreflang/href (http or https) and xsi:schemaLocation are allowed.
            foreach ($element->attributes as $attribute) {
                $name = $attribute->localName;

                if ($name === 'schemaLocation' && $attribute->namespaceURI === 'http://www.w3.org/2001/XMLSchema-instance') {
                    continue;
                }

                if (! $isHreflangLink || $attribute->namespaceURI !== null || ! in_array($name, ['rel', 'hreflang', 'href'], true)) {
                    return false;
                }

                if ($name === 'href' && ! preg_match('#^https?://#i', trim($attribute->value))) {
                    return false;
                }
            }
        }

        return true;
    }
}
