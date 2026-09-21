<?php

namespace Database\Seeders\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Lets content seeders be re-run safely: a record is only created when it has
 * never existed. Records an admin has edited are left untouched, and records an
 * admin deleted (soft-deleted, slug kept in deleted_data) are not brought back.
 */
trait SeedsMissingRecords
{
    /**
     * @param  class-string<Model>  $model
     */
    protected function alreadySeeded(string $model, string $slug): bool
    {
        return $model::withTrashed()
            ->where(fn ($q) => $q->where('slug', $slug)->orWhere('deleted_data->slug', $slug))
            ->exists();
    }
}
