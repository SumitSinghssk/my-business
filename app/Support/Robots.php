<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * robots.txt as edited in Admin → Settings → Robots. The file lives in storage (not public/)
 * so it is always served by the /robots.txt route, which adds the Sitemap line for the
 * current domain: no localhost URLs leak into production after a deploy.
 */
class Robots
{
    public const DEFAULT = "User-agent: *\nDisallow: /admin\n";

    public static function path(): string
    {
        return storage_path('app/robots.txt');
    }

    /** The rules as edited by the admin (without the automatic Sitemap line). */
    public static function content(): string
    {
        return File::exists(self::path()) ? File::get(self::path()) : self::DEFAULT;
    }

    public static function save(string $content): bool
    {
        return File::put(self::path(), self::withoutSitemap($content)) !== false;
    }

    /** The file served at /robots.txt. */
    public static function render(): string
    {
        return rtrim(self::withoutSitemap(self::content()))."\n\nSitemap: ".route('sitemap')."\n";
    }

    private static function withoutSitemap(string $content): string
    {
        $lines = preg_split('/\R/', $content);
        $lines = array_filter($lines, fn ($line) => ! preg_match('/^\s*sitemap\s*:/i', $line) && ! preg_match('/^\s*#.*sitemap/i', $line));

        return rtrim(implode("\n", $lines))."\n";
    }
}
