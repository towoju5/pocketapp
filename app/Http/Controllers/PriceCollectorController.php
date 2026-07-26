<?php

namespace App\Http\Controllers;

use App\Services\PriceFeedService;
use Illuminate\Http\Request;

/**
 * Backfill endpoint for the frontend chart. The live feed itself comes from
 * websockets-setup/ws.py, a Playwright collector that subscribes to iqcent's
 * WS feed directly and writes ticks straight into Redis; this controller
 * reads that same Redis-backed store (via PriceFeedService) back out for a
 * freshly-opened chart. See PriceFeedService's docblock for the Redis key
 * schema.
 */
class PriceCollectorController extends Controller
{
    /**
     * Backfill for a freshly-opened chart — served straight from the Redis
     * Stream ws.py writes each tick into (PriceFeedService::getHistoryTicks),
     * not iqcent's history REST endpoint. That endpoint sits behind the same
     * Cloudflare protection that blocks a plain server-side WS/HTTP client,
     * and proxying to it repeatedly was both unreliable (intermittent
     * challenge pages instead of JSON) and likely part of what got earlier
     * collector attempts' browser sessions rate-limited in the first place.
     * The frontend replays these raw ticks through the exact same
     * candle-bucketing logic the live feed uses (see AssetFeed.fetchHistory
     * in chart.js), so it comes out correctly shaped for whatever period is
     * currently selected.
     */
    public function history(Request $request, PriceFeedService $priceFeed)
    {
        $validated = $request->validate(['symbol' => 'required|string']);

        return response()->json([
            'ticks' => $priceFeed->getHistoryTicks($validated['symbol']),
        ]);
    }
}
