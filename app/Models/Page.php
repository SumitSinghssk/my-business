<?php

namespace App\Models;

use App\Casts\SafeHtml;
use App\Enums\CommonStatusEnum;
use App\Models\Concerns\HasSeoRecord;
use App\Support\ContentToc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasSeoRecord, SoftDeletes;

    /** URL path before the slug; Admin → SEO records are keyed by the full path. */
    public const SEO_PATH_PREFIX = '';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'content' => SafeHtml::class,
        'published_at' => 'datetime',
        'deleted_data' => 'array',
        'status' => CommonStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pages visible on the public website: active, and published now or earlier
     * (a null published_at counts as published, same as blog posts).
     */
    public function scopePublished($query)
    {
        return $query
            ->where('status', CommonStatusEnum::ACTIVE->value)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /**
     * Slugs a CMS page may not use because a built-in route already owns them.
     */
    public const RESERVED_SLUGS = ['about', 'contact', 'services', 'work', 'insights', 'admin', 'sitemap', 'sitemap-xml', 'robots', 'storage', 'build', 'images', 'plugins', 'login', 'logout', 'up'];

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/'.$this->featured_image) : null;
    }

    public function contentWithToc(): array
    {
        return ContentToc::build($this->content);
    }

    protected static function booted(): void
    {
        static::deleting(function (Page $page) {
            if (! $page->isForceDeleting()) {
                $page->deleted_data = [
                    'slug' => $page->slug,
                    'title' => $page->title,
                ];
                $page->slug = null;
                $page->saveQuietly();
            }
        });

        static::restoring(function (Page $page) {
            if ($page->deleted_data) {
                $page->slug = $page->deleted_data['slug'] ?? $page->slug;
                $page->deleted_data = null;
            }
        });
    }
}
