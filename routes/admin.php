<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AssetController,
    UserController,
    CashbackRuleController,
    SignalController
};

Route::prefix('admin')->middleware(['auth', 'verified'])->as('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');

    Route::resources([
        'assets' => AssetController::class,
        'users' => UserController::class,
        'cashbacks' => CashbackRuleController::class,
        'signals' => SignalController::class
    ]);
});
