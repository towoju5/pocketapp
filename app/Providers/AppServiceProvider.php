<?php

namespace App\Providers;

use App\Listeners\BroadcastWalletBalance;
use App\Models\Bitgo;
use App\Models\Option;
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Real-time balance sync across every open tab/device (see
        // WalletBalanceUpdated's docblock) — hooked at the wallet-package
        // level so it covers every balance-affecting action generically,
        // not just trade placement/settlement.
        Event::listen(BalanceUpdatedEventInterface::class, BroadcastWalletBalance::class);

        // Check if the options table exists before querying
        if (Schema::hasTable('options')) {
            $social_trades = social_trades();
            View::composer('*', compact('social_trades'));
            $u_option = [];
            $options = Option::where("autoload", "yes")->get();
            foreach ($options as $option) {
                $u_option[$option->option_name] = $option->option_value;
            }

            // Share data with all views
            View::composer('*', function ($view) use ($u_option) {
                $wallet_balance = ["balance" => 0]; // Default wallet balance

                if (Auth::check()) {
                    $user = Auth::user();

                    $uwallets = $user->wallets();

                    // Fetch user's wallet balance
                    $wallet_balance = $user->getWallet($user->active_wallet_slug ?? 'qt_demo_usd') ?? ["balance" => 0];

                    $view->with('_user', $user);
                    $view->with('uwallets', $uwallets);
                    $view->with('wallet_balance', $wallet_balance);
                }

                // Share options regardless of authentication
                $view->with('u_option', $u_option);
            });
        }
    }
}
