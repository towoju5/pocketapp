<?php

use App\Http\Controllers\ApiController;
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
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    // return auth()->user()->authentications;
    return view('act_welcome');
});


Route::get('dashboard/{coin?}', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('dashboard/demo/{coin?}', [HomeController::class, 'demo'])->middleware(['auth', 'verified'])->name('dashboard.demo');

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
    Route::get('profile/security', [ProfileController::class, 'security'])->name('profile.security');
    Route::delete('/sessions/{sessionId}/logout', [ProfileController::class, 'logoutSession'])->name('sessions.logout');
    Route::get('trading-profile', [ProfileController::class, 'tradingProfile'])->name('trading.profile');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['post', 'patch'], 'profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/photo/update', [ProfileController::class, 'update'])->name('profile.photo.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');


    Route::middleware('auth')->group(function () {
        Route::get('/profile/2fa', [ProfileController::class, 'show2faForm'])->name('profile.twofa');
        Route::post('/profile/2fa/verify', [ProfileController::class, 'verify2fa'])->name('profile.verify2fa');
        Route::post('/profile/2fa/disable', [ProfileController::class, 'disable2fa'])->name('profile.disable2fa');
    });



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



Route::group(['as' => 'api.', 'prefix' => 'api'], function () {
    Route::get('trades', [ApiController::class, 'tradeList'])->name('trades');
    Route::post('place-trade', [TradeController::class, 'placeTrade'])->name('trade');
});

Route::get('tt', function () {
    $walletId = "678eb4cf13b9813a3e3ad0cdc6a6d74d";
    $coin = "txrp";
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('bitgo.access_token')
    ])->post('https://app.bitgo-test.com/api/v2/' . $coin . '/wallet/' .
        $walletId . '/address', [
        "chain" => 0
    ]);

    return $response->json();
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
