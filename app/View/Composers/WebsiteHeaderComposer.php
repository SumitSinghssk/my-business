<?php

namespace App\View\Composers;

use App\Helpers\Settings;
use Illuminate\View\View;

/**
 * Data for the website header (layouts/partials/website/header).
 */
class WebsiteHeaderComposer
{
    public function compose(View $view): void
    {
        $logoPath = Settings::get('basic_settings.logo.light');

        // Intrinsic size so the browser reserves the logo's space before it loads (no layout shift).
        [$logoWidth, $logoHeight] = $logoPath ? (@getimagesize(storage_path('app/public/'.$logoPath)) ?: [null, null]) : [null, null];

        $view->with([
            'appName' => Settings::appName(),
            'logo' => Settings::logoLight(),
            'logoWidth' => $logoWidth,
            'logoHeight' => $logoHeight,
            'links' => [
                ['label' => 'Services', 'url' => route('services'), 'pattern' => 'services*'],
                ['label' => 'Work', 'url' => route('work.index'), 'pattern' => 'work*'],
                ['label' => 'About', 'url' => route('about'), 'pattern' => 'about'],
                ['label' => 'Insights', 'url' => route('blog.index'), 'pattern' => 'insights*'],
            ],
        ]);
    }
}
