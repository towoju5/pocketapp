<?php
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;


Route::group(['as' => 'api.'], function () {
    Route::get('trades', [ApiController::class, 'tradeList'])->name('trades');
    Route::post('place-trade', [TradeController::class, 'placeTrade'])->name('trade');
});