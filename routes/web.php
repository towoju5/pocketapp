<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Jobs\EvaluateTrade;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubArtifactController;



Route::get('/', function () {
    return view('act_welcome');
});


Route::get('dashboard', function () {
    return view('dash');
}); //->middleware(['auth', 'verified'])->name('dashboard');

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
    // });


    // Route::middleware('auth')->group(function () {
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/photo/update', [ProfileController::class, 'update'])->name('profile.photo.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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