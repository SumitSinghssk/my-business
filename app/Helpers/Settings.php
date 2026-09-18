<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    const CACHE_KEY = 'app_settings';

    const CACHE_TTL = 60 * 60 * 24;

    protected static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
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
        Cache::forget(self::CACHE_KEY);
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

    public static function addresses(): array
    {
        return (array) self::get('basic_settings.addresses', []);
    }

    public static function phones(): array
    {
        return (array) self::get('basic_settings.phones', []);
    }

    public static function emails(): array
    {
        return (array) self::get('basic_settings.emails', []);
    }

    public static function socialLinks(): array
    {
        return (array) self::get('basic_settings.social_links', []);
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

    public static function favicon(): ?string
    {
        $path = self::get('basic_settings.favicon');

        return $path ? asset('storage/'.$path) : null;
    }
}
