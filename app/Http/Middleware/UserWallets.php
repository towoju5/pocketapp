<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserWallets
{
    /**
     * Wallet types configuration
     */
    private $walletTypes = [
        'main' => ['live', 'test'],
        'forex_mt4' => ['live', 'test'],
        'forex_mt5' => ['live', 'test'],
        'shares_trading' => ['live', 'test']
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // if ($user) {
        //     foreach ($this->walletTypes as $type => $modes) {
        //         foreach ($modes as $mode) {
        //             $slug = "{$type}_{$mode}";
        //             $name = ucwords(str_replace('_', ' ', $type)) . " " . ucfirst($mode);

        //             if (!$user->hasWallet($slug)) {
        //                 $wallet = $user->createWallet([
        //                     'name' => $name,
        //                     'slug' => $slug,
        //                     'mode' => $mode,
        //                     'type' => $type,
        //                     'currently_active' => ($mode === 'live' && $type === 'main') ? true : false,
        //                     'meta' => [
        //                         'description' => "Official {$name} Wallet",
        //                         'created_at' => now()
        //                     ]
        //                 ]);
        //             }
        //         }
        //     }
        // }

        return $next($request);
    }
}
