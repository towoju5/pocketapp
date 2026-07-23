<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php', 
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(
            \App\Http\Middleware\UserWallets::class
        );
        // Appended to the 'web' *group* rather than the true global stack:
        // global middleware (append() above) runs before the 'web' group's
        // own session/auth setup, so Auth::user() would always be null
        // there. The 'web' group runs after StartSession/Authenticate, so
        // this actually sees the logged-in user.
        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureTwoFactorVerified::class);

        $middleware->alias([
            'kyc.verified' => \App\Http\Middleware\EnsureKycVerified::class,
            'collector.secret' => \App\Http\Middleware\VerifyCollectorSecret::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
