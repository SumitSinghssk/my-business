<?php

namespace App\Models;

use App\Casts\SafeHtml;
use App\Enums\CommonStatusEnum;
use App\Models\Concerns\HasSeoRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasSeoRecord, SoftDeletes;

    /** URL path before the slug; Admin → SEO records are keyed by the full path. */
    public const SEO_PATH_PREFIX = 'services';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'highlights',
        'tags',
        'content',
        'featured_image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'content' => SafeHtml::class,
        'status' => CommonStatusEnum::class,
        'highlights' => 'array',
        'tags' => 'array',
        'sort_order' => 'integer',
        'deleted_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /** Services visible on the public website. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    /** Display order set in the admin panel, then title. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/'.$this->featured_image) : null;
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->content)) / 200));
    }

    protected static function booted(): void
    {
        static::deleting(function (Service $service) {
            if (! $service->isForceDeleting()) {
                $service->deleted_data = [
                    'slug' => $service->slug,
                    'title' => $service->title,
                ];

                $service->slug = null;

                $service->saveQuietly();
            }
        });

        static::restoring(function (Service $service) {
            if ($service->deleted_data) {
                $service->slug = $service->deleted_data['slug'] ?? $service->slug;
                $service->deleted_data = null;
            }
        });
    }
}
