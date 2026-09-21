<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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

        // In production every generated URL (canonicals, sitemap, Open Graph, assets) uses APP_URL,
        // never the host or scheme of the incoming request.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'http')) {
            URL::forceRootUrl(rtrim((string) config('app.url'), '/'));
            URL::forceScheme((string) parse_url((string) config('app.url'), PHP_URL_SCHEME));
        }
    }
}
