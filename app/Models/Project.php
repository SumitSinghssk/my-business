<?php

namespace App\Models;

use App\Casts\SafeHtml;
use App\Enums\CommonStatusEnum;
use App\Models\Concerns\HasSeoRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A case study shown on the public Work pages.
 */
class Project extends Model
{
    use HasSeoRecord, SoftDeletes;

    /** URL path before the slug; Admin → SEO records are keyed by the full path. */
    public const SEO_PATH_PREFIX = 'work';

    protected $fillable = [
        'user_id',
        'service_id',
        'title',
        'slug',
        'client',
        'industry',
        'year',
        'excerpt',
        'results',
        'tags',
        'content',
        'featured_image',
        'project_url',
        'is_featured',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'content' => SafeHtml::class,
        'status' => CommonStatusEnum::class,
        'results' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'deleted_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /** Projects visible on the public website. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    /** Display order set in the admin panel, newest first within the same order. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('year')->orderByDesc('id');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/'.$this->featured_image) : null;
    }

    /** "Industry • Year" label for cards, skipping whatever is empty. */
    public function getMetaLabelAttribute(): string
    {
        return collect([$this->industry, $this->year])->filter()->implode(' • ');
    }

    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            if (! $project->isForceDeleting()) {
                $project->deleted_data = [
                    'slug' => $project->slug,
                    'title' => $project->title,
                ];

                $project->slug = null;

                $project->saveQuietly();
            }
        });

        static::restoring(function (Project $project) {
            if ($project->deleted_data) {
                $project->slug = $project->deleted_data['slug'] ?? $project->slug;
                $project->deleted_data = null;
            }
        });
    }
}
