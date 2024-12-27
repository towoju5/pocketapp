<?php

use App\Events\NewTradeCreated;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;


Route::resource('trades', TradeController::class)->names('trade');
Route::post('binary-trade', [TradeController::class, 'placeTrade'])->name('binary.trade');


Route::get('/api/assets', function() {
    $assets = get_assets();
    return response()->json($assets);
});
