<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    const CACHE_KEY = 'app_settings';

    const CACHE_TTL = 60 * 60 * 24;

    public static function all(): array
    {
        // memo(): read the cache store once per request instead of once per settings() call.
        return Cache::memo()->remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::all()
                ->mapWithKeys(fn ($row) => [$row->key => $row->value])
                ->toArray();
        });
    }

    /**
     * Get a value using dot notation.
     *
     * Examples:
     *   Settings::get('basic_settings.app_name')
     *   Settings::get('basic_settings.logo.light')
     *   Settings::get('basic_settings.phones')
     *   Settings::get('basic_settings')          // returns the full array for that key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return data_get(self::all(), $key, $default);
    }

    public static function flush(): void
    {
        Cache::memo()->forget(self::CACHE_KEY);
    }

    public static function refresh(): void
    {
        self::flush();
        self::all();
    }

    public static function appName(): string
    {
        return self::get('basic_settings.app_name', config('app.name'));
    }

    /** Addresses that have text, each ["label" => …, "text" => …, "map_iframe" => …]. */
    public static function addresses(): array
    {
        return array_values(array_filter((array) self::get('basic_settings.addresses', []), fn ($a) => filled($a['text'] ?? null)));
    }

    /**
     * Addresses ready for display: label, trimmed text, a safe map iframe (or null) and a Google Maps directions link.
     *
     * @return array<int, array{label: string, text: string, map: ?string, directions: string}>
     */
    public static function offices(): array
    {
        return array_map(fn ($a) => [
            'label' => ($a['label'] ?? null) ?: 'Our office',
            'text' => trim($a['text']),
            'map' => self::mapEmbed($a['map_iframe'] ?? null),
            'directions' => 'https://www.google.com/maps/search/?api=1&query='.urlencode(preg_replace('/\s+/', ' ', trim($a['text']))),
        ], self::addresses());
    }

    /** Non-empty phone numbers. */
    public static function phones(): array
    {
        return array_values(array_filter((array) self::get('basic_settings.phones', [])));
    }

    /** Non-empty email addresses. */
    public static function emails(): array
    {
        return array_values(array_filter((array) self::get('basic_settings.emails', [])));
    }

    /** Social links that have a URL, each ["platform" => …, "url" => …]. */
    public static function socialLinks(): array
    {
        return array_values(array_filter((array) self::get('basic_settings.social_links', []), fn ($s) => filled($s['url'] ?? null)));
    }

    /** "tel:" link target for a phone number as typed in the settings. */
    public static function telHref(string $phone): string
    {
        return 'tel:'.preg_replace('/[^0-9+]/', '', $phone);
    }

    public static function logoLight(): ?string
    {
        $path = self::get('basic_settings.logo.light');

        return $path ? asset('storage/'.$path) : null;
    }

    public static function logoDark(): ?string
    {
        $path = self::get('basic_settings.logo.dark');

        return $path ? asset('storage/'.$path) : null;
    }

    /**
     * Rebuild a Google Maps embed as a clean <iframe>, keeping only its src.
     *
     * Accepts either the full embed code or just the embed URL. Returns null for
     * anything that is not an https://www.google.com/maps/embed URL, so stored
     * values can never inject other markup or scripts into the page.
     */
    public static function mapEmbed(?string $raw): ?string
    {
        $raw = trim((string) $raw);

        if ($raw === '') {
            return null;
        }

        $src = preg_match('/\ssrc\s*=\s*(["\x27])(.*?)\1/is', $raw, $m) ? html_entity_decode($m[2]) : $raw;
        $parts = parse_url($src);

        $isGoogleMaps = ($parts['scheme'] ?? null) === 'https'
            && in_array(strtolower($parts['host'] ?? ''), ['www.google.com', 'google.com', 'maps.google.com'], true)
            && str_starts_with($parts['path'] ?? '', '/maps');

        if (! $isGoogleMaps) {
            return null;
        }

        return '<iframe src="'.e($src).'" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Office location map"></iframe>';
    }

    public static function favicon(): ?string
    {
        $path = self::get('basic_settings.favicon');

        return $path ? asset('storage/'.$path) : null;
    }
}
