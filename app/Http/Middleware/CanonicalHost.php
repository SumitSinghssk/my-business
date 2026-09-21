<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * In production, send every request to the scheme + host in APP_URL with one 301
 * (http → https and www ↔ non-www together), so each page has a single indexable URL.
 */
class CanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->isProduction() || ! in_array($request->method(), ['GET', 'HEAD'], true) || $request->is('up')) {
            return $next($request);
        }

        $canonical = parse_url((string) config('app.url'));
        $scheme = $canonical['scheme'] ?? null;
        $host = strtolower($canonical['host'] ?? '');

        if (! $scheme || ! $host || in_array($host, ['localhost', '127.0.0.1'], true)) {
            return $next($request);
        }

        if ($request->getScheme() !== $scheme || strtolower($request->getHost()) !== $host) {
            // Keep the requested path as is: APP_URL may include a sub-folder that is already part of it.
            $port = isset($canonical['port']) ? ':'.$canonical['port'] : '';

            return redirect()->to("{$scheme}://{$host}{$port}".$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
