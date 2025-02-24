<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserWallets
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = Auth::user();

            if (! $user) {
                return $next($request);
            }

            // Check if user has any wallet, if not create wallets
            if (! $user->wallets()->exists()) {
                create_user_wallet($user->id);
            }

            // If user has no trade wallet, set a default one
            if (empty($user->trade_wallet)) {
                $user->update([
                    'trade_wallet' => 'qt_demo_usd',
                ]);
            }
        }

        return $next($request);
    }
}
