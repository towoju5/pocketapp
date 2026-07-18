<?php

use App\Http\Controllers\ReferralController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('referrals', [ReferralController::class, 'index'])->name('referrals.index');
});
