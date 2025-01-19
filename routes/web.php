<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionHistoryController;
use App\Models\Assets;
use App\Models\User;
use App\Jobs\EvaluateTrade;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubArtifactController;



Route::get('/', function () {
    return view('act_welcome');
});


Route::get('dashboard/{coin?}', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('dashboard-2', function () {
//     return view('dash');
// })->middleware(['auth', 'verified'])->name('dash');

Route::middleware(['auth'])->group(function () {
    Route::resource('deposits', DepositController::class);
    Route::get('deposit-history', [DepositController::class, 'getDepositHistory']);
    Route::post('deposits/{deposit}/cancel', [DepositController::class, 'cancelDeposit']);
    Route::get('deposit-stats', [DepositController::class, 'getDepositStats']);

    Route::resource('payout', PayoutController::class)->names('withdrawals');
    Route::resource('deposit', DepositController::class)->names('deposit');
    Route::resource('payout', PayoutController::class)->names('payout');
    // });

    Route::get("finance-history", [TransactionHistoryController::class, 'history'])->name('finance.history');

    // Route::middleware('auth')->group(function () {
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('trading-profile', [ProfileController::class, 'tradingProfile'])->name('trading.profile');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['post', 'patch'], 'profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/photo/update', [ProfileController::class, 'update'])->name('profile.photo.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::match(['post', 'patch'], 'profile-update', [ProfileController::class, 'updateProfile'])
            ->name('profile.update.pk')
            ->withoutMiddleware(VerifyCsrfToken::class);

    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('/', [DepositController::class, 'create'])->name('step_1');
        Route::patch('step-2', [DepositController::class, 'step_2'])->name('step_2');
        Route::delete('step-3', [DepositController::class, 'step_3'])->name('step_3');
        Route::delete('step-4', [DepositController::class, 'step_4'])->name('step_4');
    });
});



require __DIR__ . '/auth.php';
require __DIR__ . '/temp.php';
require __DIR__ . '/chat.php';
require __DIR__ . '/trade.php';
require __DIR__ . '/admin.php';


Route::fallback(function () {
    sleep(10);
    return response()->json([
        "status" => "error",
        "message" => "Route not found",
        "data" => null
    ], 404);
});