<?php

use App\Events\NewTradeCreated;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\SignalCopyController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {
    Route::resource('trades', TradeController::class)->names('trade');
    // Route::get('trades/{trade}/evaluate', [TradeController::class, 'evaluateTrade'])->name('trade.evaluate');
    Route::post('binary-trade', [TradeController::class, 'placeTrade'])->name('binary.trade');
    Route::post('social-trade', [TradeController::class, 'socialTrades'])->name('social-trade');

    Route::get('signals', [SignalController::class, 'index'])->name('signals.index');
    Route::post('signals/{signalId}/copy', [SignalCopyController::class, 'copy'])->name('signals.copy');


    Route::get('/api/assets', function () {
        $assets = get_assets();
        return response()->json($assets);
    });

    // Route::get('api/stream/chart/{coin}', function ($coin) {
    //     $chartData = fetchPreChartData($coin);
    //     return $chartData;
    // })->name('stream.chart');

});