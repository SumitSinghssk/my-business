<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * public/robots.txt, edited in Admin → Settings → Robots. It is a plain static file served
 * directly by the web server at /robots.txt, saved exactly as the admin typed it.
 */
class Robots
{
    public static function path(): string
    {
        return public_path('robots.txt');
    }

    /** Suggested content when the file does not exist yet. */
    public static function default(): string
    {
        return "User-agent: *\nDisallow: /admin/\n\nSitemap: ".url('sitemap.xml')."\n";
    }

    public static function content(): string
    {
        return File::exists(self::path()) ? File::get(self::path()) : self::default();
    }

    public static function save(string $content): bool
    {
        // Normalise Windows line endings from the textarea and end with a newline.
        $content = rtrim(str_replace("\r\n", "\n", $content))."\n";

        return File::put(self::path(), $content) !== false;
    }
}
