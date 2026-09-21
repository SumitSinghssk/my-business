<?php

namespace App\Http\Middleware;

use App\Enums\CommonStatusEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Signs out a user whose account has been deactivated (or deleted) since they
 * logged in, instead of letting them keep using their existing session.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status !== CommonStatusEnum::ACTIVE) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                abort(401, 'Your account is inactive.');
            }

            return to_route('admin.login')->withErrors(['email' => 'Your account is inactive. Please contact an administrator.']);
        }

        return $next($request);
    }
}
