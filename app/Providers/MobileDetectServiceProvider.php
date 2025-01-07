<?php

namespace App\Providers;

use Detection\MobileDetect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MobileDetectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $browser = new MobileDetect();
        View::share('browser', $browser);
    }
}
