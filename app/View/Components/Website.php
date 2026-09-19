<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Website extends Component
{
    /**
     * Per-page SEO defaults. An admin SEO record for the same URL always wins;
     * when neither exists, the `default-seo` record is used.
     *
     * @param  bool  $noindex  Emit `noindex, follow` (e.g. search/filter result pages).
     * @param  string|null  $canonical  Override the canonical URL.
     * @param  string  $ogType  Open Graph type, e.g. `website` or `article`.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
        public bool $noindex = false,
        public ?string $canonical = null,
        public string $ogType = 'website',
    ) {}

    public function render(): View|Closure|string
    {
        return view('layouts.website');
    }
}
