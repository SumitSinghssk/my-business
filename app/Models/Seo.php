<?php

namespace App\Models;

use App\Helpers\Settings;
use App\Traits\Trackable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Seo extends Model
{
    use Trackable;

    /** Placeholder admins can use in meta titles/descriptions; replaced with the current site name (Settings → Basic). */
    public const SITE_NAME = '{site_name}';

    protected $fillable = [
        'page',
        'slug',
        'meta_title',
        'meta_description',
        'og_image',
        'schema',
        'faqs',
        'header_scripts',
        'footer_scripts',
        'custom_css',
        'index',
    ];

    protected $casts = [
        'faqs' => 'array',
        'index' => 'boolean',
    ];

    /**
     * The Admin → SEO record for a URL path ("/" for home, "services", "insights/my-post"…).
     * All records are cached together for a day and the cache is cleared on every save.
     */
    public static function forPath(string $path): ?self
    {
        return Cache::memo()->remember('seo_all', now()->addHours(24), fn () => static::all()->keyBy('slug'))->get($path);
    }

    /** Replace {site_name} with the site name, so titles follow a later change of the name. */
    public static function withSiteName(?string $text): ?string
    {
        return $text === null ? null : str_replace(self::SITE_NAME, Settings::appName(), $text);
    }

    /**
     * FAQs as a clean list of ['question' => …, 'answer' => …], skipping incomplete rows.
     *
     * @return array<int, array{question: string, answer: string}>
     */
    public function faqItems(): array
    {
        return collect($this->faqs ?? [])
            ->filter(fn ($faq) => is_array($faq) && filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->map(fn ($faq) => ['question' => trim($faq['question']), 'answer' => trim($faq['answer'])])
            ->values()
            ->all();
    }

    /**
     * Parse the admin "Schema" field into JSON-LD nodes. Accepts plain JSON or JSON
     * wrapped in one or more <script> tags (with or without type="application/ld+json").
     * Returns null when the value is empty or not valid JSON.
     *
     * @return array<int|string, mixed>|null
     */
    public static function parseSchema(?string $raw): ?array
    {
        $raw = trim((string) $raw);

        if ($raw === '') {
            return null;
        }

        $blocks = preg_match_all('#<script\b[^>]*>(.*?)</script>#is', $raw, $m) ? $m[1] : [$raw];
        $nodes = [];

        foreach ($blocks as $block) {
            $data = json_decode(trim($block), true);

            if (! is_array($data)) {
                return null;
            }

            // A single node or a list of nodes.
            array_push($nodes, ...(array_is_list($data) ? $data : [$data]));
        }

        return $nodes ?: null;
    }

    /** The Schema field as a JSON-LD string ready for <script type="application/ld+json">, or null. */
    public function schemaJson(): ?string
    {
        $nodes = static::parseSchema($this->schema);

        if (! $nodes) {
            return null;
        }

        return json_encode(count($nodes) === 1 ? $nodes[0] : $nodes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::memo()->forget('seo_all');
        });

        static::deleted(function () {
            Cache::memo()->forget('seo_all');
        });
    }
}
