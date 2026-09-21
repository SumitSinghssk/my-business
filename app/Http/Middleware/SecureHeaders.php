<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response) {

            $response->headers->set('X-Frame-Options', 'DENY');

            $response->headers->set('X-Content-Type-Options', 'nosniff');

            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

            // The site uses none of these browser features; the map on the contact page is a Google iframe with its own origin.
            $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');

            // Only sent over HTTPS in production. No includeSubDomains/preload: those would force HTTPS on
            // every subdomain (and can't be undone quickly), so opt in to them deliberately at the server.
            if ($request->isSecure() && app()->isProduction()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
            }
        }

        return $response;
    }
}
