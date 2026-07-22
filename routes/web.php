<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ExpressTradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PriceCollectorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionHistoryController;
use App\Models\Assets;
use App\Models\User;
use App\Jobs\EvaluateTrade;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubArtifactController;
use App\Http\Controllers\MySafeController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\TradeController;
use App\Models\Trade;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;


Route::get('/', [MarketingController::class, 'home'])->name('marketing.home');



Route::get('dashboard/demo/{coin?}', [HomeController::class, 'demo'])->middleware(['auth', 'verified'])->name('dashboard.demo');
Route::get('dashboard/{coin?}', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('assets/status', [HomeController::class, 'assetStatus'])->middleware(['auth', 'verified'])->name('assets.status');
Route::get('assets/history', [PriceCollectorController::class, 'history'])->middleware(['auth', 'verified'])->name('assets.history');

// Called by the standalone price collector (collector/index.js), not by
// end-user browsers — authenticated via shared secret, not the auth guard.
// Batched: one request per flush interval carrying every tick since the
// last one, not one request per tick (see PriceCollectorController::ingestTicks).
Route::post('internal/assets/ticks', [PriceCollectorController::class, 'ingestTicks'])
    ->middleware('collector.secret')
    ->name('internal.assets.ticks')
    ->withoutMiddleware(VerifyCsrfToken::class);
Route::get('internal/assets/symbols', [PriceCollectorController::class, 'symbols'])->middleware('collector.secret')->name('internal.assets.symbols');

Route::get('dashboard-2', function () {
    return view('dash');
})->middleware(['auth', 'verified'])->name('dash');

Route::middleware(['auth'])->group(function () {
    Route::resource('deposits', DepositController::class);
    Route::get('deposit-history', [DepositController::class, 'getDepositHistory']);
    Route::post('deposits/{deposit}/cancel', [DepositController::class, 'cancelDeposit']);
    Route::get('deposit-stats', [DepositController::class, 'getDepositStats']);

    Route::resource('payout', PayoutController::class)->names('withdrawals');
    Route::resource('deposit', DepositController::class)->names('deposit');
    Route::resource('payout', PayoutController::class)->names('payout');


    Route::post('submit-express-trade', [ExpressTradeController::class, 'bulk'])->name('submit.express.trade');
    Route::get('express-trades', [ExpressTradeController::class, 'index'])->name('express-trades.index');

    Route::get('wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');

    Route::get('achievements', [\App\Http\Controllers\AchievementController::class, 'index'])->name('achievements.index');

    Route::get('tournaments', [\App\Http\Controllers\TournamentController::class, 'index'])->name('tournaments.index');
    Route::post('tournaments/{tournament}/join', [\App\Http\Controllers\TournamentController::class, 'join'])->name('tournaments.join');

    Route::get('social-trading', [\App\Http\Controllers\SocialTradingController::class, 'index'])->name('social-trading.index');
    Route::post('social-trading/{trader}/follow', [\App\Http\Controllers\SocialTradingController::class, 'follow'])->name('social-trading.follow');
    Route::post('social-trading/{trader}/unfollow', [\App\Http\Controllers\SocialTradingController::class, 'unfollow'])->name('social-trading.unfollow');


    Route::post('payout/create', [PayoutController::class, 'store'])->middleware('kyc.verified')->name('payout.create');
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

    Route::get('wallet-change-default/{slug}', [ProfileController::class, 'changeDefaultWallet'])->name('wallet.change.default');

    Route::match(['post', 'patch'], 'profile-update', [ProfileController::class, 'updateProfile'])
        ->name('profile.update.pk')
        ->withoutMiddleware(VerifyCsrfToken::class);

    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('/', [DepositController::class, 'create'])->name('step_1');
        Route::patch('step-2', [DepositController::class, 'step_2'])->name('step_2');
        Route::delete('step-3', [DepositController::class, 'step_3'])->name('step_3');
        Route::delete('step-4', [DepositController::class, 'step_4'])->name('step_4');
    });

    Route::group(['as' => 'finance.', 'prefix' => 'finance'], function () {
        Route::get('cashback', [ChartController::class, 'cashback'])->name('cashback');
        Route::get('promo-codes', [PromoCodeController::class, 'index'])->name('promo-codes');
        Route::post('promo-codes/redeem', [PromoCodeController::class, 'redeem'])->name('promo-codes.redeem');
        Route::get('my-safe', [MySafeController::class, 'index'])->name('my-safe');
        Route::post('my-safe/deposit', [MySafeController::class, 'deposit'])->name('my-safe.deposit');
        Route::post('my-safe/withdraw', [MySafeController::class, 'withdraw'])->name('my-safe.withdraw');
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
require __DIR__ . '/marketing.php';
require __DIR__ . '/kyc.php';
require __DIR__ . '/plans.php';
require __DIR__ . '/p2p.php';
require __DIR__ . '/tasks.php';
require __DIR__ . '/referrals.php';
require __DIR__ . '/support.php';


Route::fallback(function () {
    return response()->json([
        "status" => "error",
        "message" => "Route not found",
        "data" => null
    ], 404);
});
