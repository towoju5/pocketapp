<?php

namespace App\Providers;

use App\Models\Bitgo;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
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
        if (tableExists('options')) {
            $u_option = [];
            $options = Option::where("autoload", "yes")->get();
            foreach ($options as $option) {
                $u_option[] = [$option->option_name => $option->option_value];
            }

            // Share user wallets with all views
            View::composer('*', function ($view) use(&$u_option) {
                if (Auth::check()) {
                    $user = Auth::user();
                    $uwallets = [
                        'all_wallets' => $user->wallets,
                        'main_wallets' => $user->wallets()->where('description', 'main')->first(),
                        'forex_mt4_wallets' => $user->wallets()->where('description', 'forex_mt4')->first(),
                        'forex_mt5_wallets' => $user->wallets()->where('description', 'forex_mt5')->first(),
                        'shares_trading_wallets' => $user->wallets()->where('description', 'shares_trading')->first(),
                        // 'active_wallet' => $user->wallets()->where('currently_active', true)->first(),
                        'live_wallets' => $user->wallets()->where('description', 'live')->get(),
                        'test_wallets' => $user->wallets()->where('description', 'test')->get()
                    ];

                    $view->with('uwallets', $uwallets);
                    $view->with('u_option', $u_option);
                }
            });
        }
    }
}
