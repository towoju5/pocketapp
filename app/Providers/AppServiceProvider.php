<?php

namespace App\Providers;

use App\Models\Bitgo;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
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
        \App\Models\Assets::whereRaw('LOWER(name) NOT LIKE ?', ['%otc%'])->update(['is_otc' => false]);
        \App\Models\Assets::whereRaw('LOWER(name) LIKE ?', ['%otc%'])->update(['is_otc' => true]);

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
                    
                    create_user_wallet($user->id);
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
