<?php

namespace App\Http\Requests\Concerns;

/**
 * SEO records are looked up by request()->path(): "/" for the home page, otherwise the path with
 * no leading/trailing slash ("about", "insights/my-post"). Accept what admins naturally type
 * ("/about/", "home", a full URL of this site, capitals) and store it in that exact form.
 */
trait NormalizesSeoPath
{
    protected function prepareForValidation(): void
    {
        if (! is_string($this->input('slug'))) {
            return;
        }

        $path = trim($this->input('slug'));
        $base = rtrim(url('/'), '/');

        if (str_starts_with(strtolower($path), strtolower($base))) {
            $path = substr($path, strlen($base));
        }

        $path = strtolower(trim(strtok($path, '?#') ?: '', "/ \t"));

        $this->merge(['slug' => in_array($path, ['', 'home', 'index'], true) ? '/' : $path]);
    }
}
