<?php

namespace App\Http\Controllers;

use App\Services\PriceFeedService;
use Illuminate\Http\Request;

/**
 * Backfill endpoint for the frontend chart. The live feed itself comes from
 * TickerController's headless-Chrome collector pool (see
 * app/Console/Commands/CollectTicks.php), which writes straight into
 * PriceFeedService in-process; this controller just reads that same store
 * back out for a freshly-opened chart.
 */
class PriceCollectorController extends Controller
{
    /**
     * Backfill for a freshly-opened chart — served straight from our own
     * rolling tick cache (PriceFeedService::appendHistoryTick), not iqcent's
     * history REST endpoint. That endpoint sits behind the same Cloudflare
     * protection that blocks server-side WS connections, and proxying to it
     * repeatedly was both unreliable (intermittent challenge pages instead
     * of JSON) and likely part of what got the collector's browser session
     * rate-limited in the first place. The frontend replays these raw ticks
     * through the exact same candle-bucketing logic the live feed uses (see
     * AssetFeed.fetchHistory in chart.js), so it comes out correctly shaped
     * for whatever period is currently selected.
     */
    public function history(Request $request, PriceFeedService $priceFeed)
    {
        $validated = $request->validate(['symbol' => 'required|string']);

        return response()->json([
            'ticks' => $priceFeed->getHistoryTicks($validated['symbol']),
        ]);
    }
}
