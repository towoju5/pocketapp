<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates every authenticated route behind a one-time-password challenge for
 * accounts that have 2FA enabled — Auth::attempt() alone fully logs a user
 * in regardless of google2fa_enabled, so without this a customer could
 * enable 2FA in settings and it would never actually be asked for again.
 * Session-scoped (cleared on logout): once verified, the rest of that
 * session doesn't need to re-enter a code on every request.
 *
 * Uses Auth::user() rather than $request->user(): this is registered as
 * global middleware (bootstrap/app.php), which runs before the 'web'
 * group's own Authenticate middleware has set up the request's
 * user-resolver — $request->user() would always be null here. Auth::user()
 * resolves straight from the session instead, same as the existing
 * UserWallets global middleware already does.
 */
class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->google2fa_enabled || session('2fa_passed')) {
            return $next($request);
        }

        // Avoid a redirect loop on the challenge page itself and on logout.
        if ($request->routeIs('two-factor.challenge', 'two-factor.verify', 'logout')) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Two-factor authentication required.'], 409);
        }

        return redirect()->route('two-factor.challenge');
    }
}
