<?php

namespace App\Providers;

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
        $options = Option::where("autoload", "yes")->get();
        foreach ($options as $option) {
            config(["option" => [$option->option_name => $option->option_value]]);
        }

        // Share user wallets with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $wallets = [
                    'all_wallets' => $user->wallets,
                    'main_wallets' => $user->wallets()->where('type', 'main')->get(),
                    'forex_mt4_wallets' => $user->wallets()->where('type', 'forex_mt4')->get(),
                    'forex_mt5_wallets' => $user->wallets()->where('type', 'forex_mt5')->get(),
                    'shares_trading_wallets' => $user->wallets()->where('type', 'shares_trading')->get(),
                    'active_wallet' => $user->wallets()->where('currently_active', true)->first(),
                    'live_wallets' => $user->wallets()->where('mode', 'live')->get(),
                    'test_wallets' => $user->wallets()->where('mode', 'test')->get()
                ];
                
                $view->with('wallets', $wallets);
            }
        });
    }
}
