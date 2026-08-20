<?php

use App\Http\Controllers\Internal\SessionVerifyController;
use App\Http\Controllers\Internal\TradeRelayController;
use Illuminate\Support\Facades\Route;

/*
| Called only by node-services/ (the Node real-time layer) over internal
| HTTP, never by the browser — guarded by EnsureInternalServiceRequest
| (shared-secret header) rather than the session guard. See the migration
| plan / TradePlacementService docblock for why trade placement is relayed
| here instead of duplicated in Node.
*/

Route::post('trades', [TradeRelayController::class, 'place'])->name('internal.trades.place');
Route::post('session/verify', [SessionVerifyController::class, 'verify'])->name('internal.session.verify');
