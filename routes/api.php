<?php
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PriceCollectorController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;

// Called by the standalone price collector (collector/index.js), not by
// end-user browsers — authenticated via a shared secret (collector.secret
// middleware), not the auth guard. Deliberately routed here rather than
// routes/web.php: the 'api' group is stateless by default (no session,
// cookies, or CSRF), whereas 'web' pulls those in as a cohesive unit — with
// SESSION_DRIVER=database, every 'web' request (including these, arriving
// every ~300ms from the collector) was also writing a session row to the
// same SQLite file the price cache lives in, and several 'web'-group
// middlewares (UserWallets, ShareErrorsFromSession) assume a session exists
// on the request and throw without one, so selectively stripping pieces out
// of 'web' turned into removing one, hitting the next failure, repeat.
// Being naturally stateless here avoids all of it at once.
//
// Batched: one request per flush interval carrying every tick since the
// last one, not one request per tick (see PriceCollectorController::ingestTicks).
Route::post('internal/assets/ticks', [PriceCollectorController::class, 'ingestTicks'])
    ->middleware('collector.secret')
    ->name('internal.assets.ticks');
Route::get('internal/assets/symbols', [PriceCollectorController::class, 'symbols'])
    ->middleware('collector.secret')
    ->name('internal.assets.symbols');
