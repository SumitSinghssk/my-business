<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CanonicalHost;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\LogAdminActivity;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ScalarQueryString;
use App\Http\Middleware\SecureHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Authenticate::class,
            'active' => EnsureUserIsActive::class,
            'log.admin.activity' => LogAdminActivity::class,
            'guest' => RedirectIfAuthenticated::class,
            'scalar.query' => ScalarQueryString::class,
        ]);
        $middleware->web(prepend: [
            CanonicalHost::class,
        ], append: [
            SecureHeaders::class,
        ]);
        // Behind a load balancer / CDN that terminates HTTPS, trust its X-Forwarded-Proto/Port so
        // Laravel sees https (no redirect loop in CanonicalHost, https URLs in canonicals and the sitemap).
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_PORT);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
