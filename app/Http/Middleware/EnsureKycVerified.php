<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKycVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (optional($request->user()->kyc)->status !== 'verified') {
            return back()->with('error', 'You must complete identity verification (KYC) before withdrawing funds.');
        }

        return $next($request);
    }
}
