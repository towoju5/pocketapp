<?php

namespace App\Http\Controllers;

use App\Services\BrokeretFeedService;
use Illuminate\Http\Request;

/**
 * Dedicated to base_url/ui's independent Brokeret price pipeline (see
 * BrokeretFeedService / StreamBrokeretFeed) — deliberately separate from
 * PriceCollectorController's assets.history route, which stays backed by
 * PriceFeedService/the iqcent pipeline for the main dashboard.
 */
class BrokeretController extends Controller
{
    public function history(Request $request, BrokeretFeedService $feed)
    {
        $validated = $request->validate(['symbol' => 'required|string']);

        return response()->json([
            'ticks' => $feed->getHistoryTicks($validated['symbol']),
        ]);
    }
}
