<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AssetController,
    BitgoController,
    UserController,
    CashbackRuleController,
    PayoutController,
    SignalController,
    WalletController,
};

Route::prefix('admin')->middleware(['auth', 'verified'])->as('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');

    Route::resources([
        'assets' => AssetController::class,
        'users' => UserController::class,
        'cashbacks' => CashbackRuleController::class,
        'signals' => SignalController::class,
        'bitgo' => BitgoController::class,
        'payouts' => PayoutController::class
    ]);

    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallets.index');
        Route::post('/{userId}/credit', [WalletController::class, 'credit'])->name('wallets.credit');
        Route::post('/{userId}/debit', [WalletController::class, 'debit'])->name('wallets.debit');
    });
    Route::post('{user}/wallet', [UserController::class, 'updateWalletAction'])->name('wallets.update');
});
