<?php

namespace App\Models;

use App\Casts\SafeHtml;
use App\Enums\CommonStatusEnum;
use App\Models\Concerns\HasSeoRecord;
use App\Support\ContentToc;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Blog extends Model
{
    use HasSeoRecord, SoftDeletes;

    /** URL path before the slug; Admin → SEO records are keyed by the full path. */
    public const SEO_PATH_PREFIX = 'insights';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'content' => SafeHtml::class,
        'status' => CommonStatusEnum::class,
        'published_at' => 'datetime',
        'deleted_data' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blog');
    }

    /**
     * Posts visible on the public website: active, and published now or earlier
     * (a null published_at counts as published).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', CommonStatusEnum::ACTIVE)
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->orderByRaw('COALESCE(published_at, created_at) DESC')->orderByDesc('id');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/'.$this->featured_image) : null;
    }

    public function getPublishedDateAttribute(): Carbon
    {
        return $this->published_at ?? $this->created_at;
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->content)) / 200));
    }

    /**
     * Content with an id on every <h2> (for anchor links) plus the matching
     * table of contents: ['html' => string, 'toc' => [['id', 'title'], ...]].
     */
    public function contentWithToc(): array
    {
        return ContentToc::build($this->content);
    }

    protected static function booted(): void
    {
        static::deleting(function (Blog $blog) {
            if (! $blog->isForceDeleting()) {
                $blog->deleted_data = [
                    'slug' => $blog->slug,
                    'title' => $blog->title,
                ];

                $blog->slug = null;

                $blog->saveQuietly();
            }
        });

        static::restoring(function (Blog $blog) {
            if ($blog->deleted_data) {
                $blog->slug = $blog->deleted_data['slug'] ?? $blog->slug;
                $blog->deleted_data = null;
            }
        });
    }
}
