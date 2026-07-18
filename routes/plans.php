<?php

use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
    Route::get('my-plans', [PlanController::class, 'subscriptions'])->name('plans.subscriptions');
    Route::get('plans/{plan}', [PlanController::class, 'show'])->name('plans.show');
    Route::post('plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');
    Route::post('plan-subscriptions/{subscription}/reinvest', [PlanController::class, 'reinvest'])->name('plans.reinvest');
});
