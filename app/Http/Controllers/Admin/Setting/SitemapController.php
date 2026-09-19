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

            if (simplexml_load_string($content) === false) {
                return back()->withErrors(['sitemap' => 'The file contains invalid XML.']);
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
}
