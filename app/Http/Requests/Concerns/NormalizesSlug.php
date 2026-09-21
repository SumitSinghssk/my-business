<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Str;

/**
 * Turns the submitted slug (or, when empty, the title/name) into a URL-safe slug
 * before validation, so the unique and alpha_dash rules check the final value.
 *
 * Titles with no Latin characters (e.g. "关于我们" or "🚀") slugify to an empty
 * string, which would break route generation; those get a short random slug.
 */
trait NormalizesSlug
{
    protected function normalizeSlug(string $sourceField = 'title'): void
    {
        $slug = Str::slug((string) ($this->input('slug') ?: $this->input($sourceField)));

        $this->merge(['slug' => $slug !== '' ? $slug : Str::lower(Str::random(8))]);
    }
}
