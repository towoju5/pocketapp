<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards routes/internal.php — endpoints only the Node real-time services
 * (node-services/) call, never the browser. Checked via a shared secret
 * header rather than the session guard, since these are server-to-server
 * calls with no session cookie involved.
 */
class EnsureInternalServiceRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.realtime.api_secret');

        if (!$expected || !hash_equals($expected, (string) $request->header('X-Internal-Secret'))) {
            abort(401, 'Unauthorized internal request');
        }

        return $next($request);
    }
}
