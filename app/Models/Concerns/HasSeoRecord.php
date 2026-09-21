<?php

namespace App\Models\Concerns;

use App\Models\Seo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Links a public model to its Admin → SEO record.
 *
 * SEO records are keyed by the page's URL path (e.g. "insights/my-post"), which
 * is what the website layout looks up, so meta tags, noindex, OG image, schema
 * and scripts all apply to the right page. Models set SEO_PATH_PREFIX to the
 * part of the URL before the slug ('' for pages served at /{slug}).
 */
trait HasSeoRecord
{
    public static function bootHasSeoRecord(): void
    {
        // Keep the SEO record attached when an admin changes the slug.
        static::updated(function (self $model) {
            if (! $model->wasChanged('slug')) {
                return;
            }

            $old = $model->getOriginal('slug');

            if (filled($old) && filled($model->slug)) {
                Seo::where('slug', static::seoPathFor($old))
                    ->whereNotExists(fn ($q) => $q->from('seos as taken')->where('taken.slug', static::seoPathFor($model->slug)))
                    ->update(['slug' => static::seoPathFor($model->slug)]);
            }
        });
    }

    public static function seoPathFor(string $slug): string
    {
        return ltrim(static::SEO_PATH_PREFIX.'/'.$slug, '/');
    }

    public function getSeoPathAttribute(): ?string
    {
        return filled($this->slug) ? static::seoPathFor($this->slug) : null;
    }

    public function seo(): HasOne
    {
        return $this->hasOne(Seo::class, 'slug', 'seo_path');
    }
}
