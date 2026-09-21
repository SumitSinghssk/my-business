<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin list filters only ever use plain values (?search=…&status=…). Dropping
 * array values (?search[]=x) keeps string functions in the filters from
 * throwing TypeErrors and returning a 500.
 */
class ScalarQueryString
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->query->replace(array_filter($request->query->all(), fn ($value) => ! is_array($value)));

        return $next($request);
    }
}
