<?php
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/api', 'as' => 'api.'], function () {
    Route::get('trades', [TradeController::class, 'index'])->name('trades');
    Route::post('place-trade', [TradeController::class, 'placeTrade'])->name('trade');
});