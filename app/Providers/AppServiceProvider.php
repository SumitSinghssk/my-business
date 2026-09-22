<?php

namespace App\Providers;

use App\Helpers\Settings;
use App\View\Composers\NotFoundComposer;
use App\View\Composers\WebsiteFooterComposer;
use App\View\Composers\WebsiteHeaderComposer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super admins pass every permission check, including permissions created
        // later in the admin panel that were never attached to their role.
        Gate::before(fn ($user) => $user->hasRole(['super admin', 'super-admin']) ? true : null);

        // Website view data: the site name everywhere, plus the header, footer and 404 page.
        View::composer(['website.*', 'errors::*', 'layouts.partials.website.*'], fn ($view) => $view->with('appName', Settings::appName()));
        View::composer('layouts.partials.website.header', WebsiteHeaderComposer::class);
        View::composer('layouts.partials.website.footer', WebsiteFooterComposer::class);
        View::composer('errors::404', NotFoundComposer::class);

        // In production every generated URL (canonicals, sitemap, Open Graph, assets) uses APP_URL,
        // never the host or scheme of the incoming request.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'http')) {
            URL::forceRootUrl(rtrim((string) config('app.url'), '/'));
            URL::forceScheme((string) parse_url((string) config('app.url'), PHP_URL_SCHEME));
        }
    }
}
