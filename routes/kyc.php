<?php

use App\Http\Controllers\KycController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('kyc', [KycController::class, 'show'])->name('kyc.show');
    Route::get('kyc/create', [KycController::class, 'create'])->name('kyc.create');
    Route::post('kyc', [KycController::class, 'store'])->name('kyc.store');
    Route::post('kyc/start', [KycController::class, 'start'])->name('kyc.start');
    Route::get('kyc/return', [KycController::class, 'returnFromHosted'])->name('kyc.return');
});
