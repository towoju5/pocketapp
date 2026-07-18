<?php

use App\Http\Controllers\P2pOfferController;
use App\Http\Controllers\P2pTradeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('p2p-offers', P2pOfferController::class)->except(['destroy', 'show']);
    Route::post('p2p-offers/{p2pOffer}/cancel', [P2pOfferController::class, 'cancel'])->name('p2p-offers.cancel');
    Route::post('p2p-offers/{offer}/trades', [P2pTradeController::class, 'store'])->name('p2p-trades.store');

    Route::get('p2p-trades', [P2pTradeController::class, 'index'])->name('p2p-trades.index');
    Route::get('p2p-trades/{p2pTrade}', [P2pTradeController::class, 'show'])->name('p2p-trades.show');
    Route::post('p2p-trades/{p2pTrade}/pay', [P2pTradeController::class, 'pay'])->name('p2p-trades.pay');
    Route::post('p2p-trades/{p2pTrade}/release', [P2pTradeController::class, 'release'])->name('p2p-trades.release');
    Route::post('p2p-trades/{p2pTrade}/cancel', [P2pTradeController::class, 'cancel'])->name('p2p-trades.cancel');
    Route::post('p2p-trades/{p2pTrade}/dispute', [P2pTradeController::class, 'dispute'])->name('p2p-trades.dispute');
    Route::post('p2p-trades/{p2pTrade}/dispute/undo', [P2pTradeController::class, 'undoDispute'])->name('p2p-trades.dispute.undo');
});
