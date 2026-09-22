<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'url',
        'seen_at',
    ];

    protected $casts = [
        'data' => 'array',
        'seen_at' => 'datetime',
    ];

    /**
     * Permission needed to see each notification type: enquiry notifications carry the visitor's
     * name and email, login notifications an admin's IP and browser. Other types are visible to all.
     */
    public const TYPE_PERMISSIONS = [
        'Enquiry' => 'admin.enquiries.view',
        'Admin Login' => 'admin.users.view',
    ];

    public function scopeVisibleTo(Builder $query, Authorizable $user): void
    {
        $hidden = collect(self::TYPE_PERMISSIONS)->reject(fn ($permission) => $user->can($permission))->keys();

        $query->whereNotIn('type', $hidden->all());
    }

    public function isSeen(): bool
    {
        return ! is_null($this->seen_at);
    }
}
