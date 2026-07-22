<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the internal endpoints the price collector (collector/index.js)
 * calls — these carry no user session, so they're authenticated by a shared
 * secret header instead of the normal auth guard.
 */
class VerifyCollectorSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.price_collector.secret');
        $given = (string) $request->header('X-Collector-Secret');

        if (!$expected || !hash_equals((string) $expected, $given)) {
            abort(403);
        }

        return $next($request);
    }
}
