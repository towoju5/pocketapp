<?php

namespace App\Http\Controllers;

use App\Services\PriceFeedService;
use Illuminate\Http\Request;

/**
 * Backfill endpoint for the frontend chart. The live feed itself comes from
 * StreamBrokeretTicks (`php artisan ticks:stream-brokeret`), which connects
 * to Brokeret's feed directly and writes ticks straight into Redis; this
 * controller reads that same Redis-backed store (via PriceFeedService) back
 * out for a freshly-opened chart. See PriceFeedService's docblock for the
 * Redis key schema. Only ever has data for price_source='brokeret' assets —
 * the iqcent collector that used to feed this same store for
 * price_source='iqcent' assets has been removed.
 */
class PriceCollectorController extends Controller
{
    /**
     * Backfill for a freshly-opened chart — served straight from the Redis
     * Stream StreamBrokeretTicks writes each tick into
     * (PriceFeedService::getHistoryTicks). The frontend replays these raw
     * ticks through the exact same candle-bucketing logic the live feed uses
     * (see AssetFeed.fetchHistory in chart.js), so it comes out correctly
     * shaped for whatever period is currently selected.
     */
    public function history(Request $request, PriceFeedService $priceFeed)
    {
        $validated = $request->validate(['symbol' => 'required|string']);

        return response()->json([
            'ticks' => $priceFeed->getHistoryTicks($validated['symbol']),
        ]);
    }
}
