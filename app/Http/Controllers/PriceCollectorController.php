<?php

namespace App\Http\Controllers;

use App\Events\AssetPriceBatchUpdated;
use App\Models\Assets;
use App\Services\PriceFeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Backing endpoints for the standalone Node/Playwright price collector
 * (collector/index.js). That process holds the one real connection to
 * iqcent (server-side PHP/Node WS clients get a Cloudflare 403 — only a
 * genuine browser engine passes) and relays what it receives here, so every
 * user's chart is fed the same stream from our own backend instead of each
 * browser connecting to iqcent directly.
 */
class PriceCollectorController extends Controller
{
    /**
     * Called by the collector every ~300ms with everything it received
     * since the last flush, across the whole subscribed catalog (~150
     * assets). Batched deliberately — one HTTP request carrying many ticks,
     * not one request per tick, because this app's dev server has no
     * concurrency headroom for a genuinely per-tick, per-asset request rate
     * (confirmed: even ~30 assets at a few ticks/sec each was enough to back
     * up its connection queue). A production php-fpm/Octane deployment
     * wouldn't need this batching, but it costs nothing there either.
     */
    public function ingestTicks(Request $request, PriceFeedService $priceFeed)
    {
        $validated = $request->validate([
            'ticks' => 'required|array|min:1',
            'ticks.*.symbol' => 'required|string',
            'ticks.*.price' => 'required|numeric|gt:0',
            'ticks.*.t' => 'nullable|integer',
        ]);

        $broadcastTicks = [];

        // One commit for the whole batch instead of one per tick — the cache
        // table backing this (CACHE_STORE=database) is on the same SQLite
        // file as everything else, and per-write commits under this volume
        // is exactly the kind of thing that reintroduces the request-queue
        // backup this batching exists to avoid.
        DB::transaction(function () use ($validated, $priceFeed, &$broadcastTicks) {
            foreach ($validated['ticks'] as $tick) {
                $symbol = $tick['symbol'];
                $price = (float) $tick['price'];
                $epochMs = $tick['t'] ?? (int) (microtime(true) * 1000);

                $priceFeed->updatePrice($symbol, $price);
                $priceFeed->appendHistoryTick($symbol, $price, $epochMs);

                $broadcastTicks[] = ['symbol' => $symbol, 'price' => $price, 't' => $epochMs];
            }
        });

        // Reverb caps message size (10KB default) — a catch-up burst after a
        // relaunch can carry several hundred ticks in one flush, well past
        // that in a single event, which used to throw and 500 the whole
        // request even though the cache/history writes above had already
        // succeeded. Chunked, and never allowed to fail the response —
        // broadcasting is real-time delivery, not the source of truth; a
        // dropped broadcast just means a slightly stale chart until the
        // next flush, not lost data.
        foreach (array_chunk($broadcastTicks, 100) as $chunk) {
            try {
                broadcast(new AssetPriceBatchUpdated($chunk));
            } catch (\Throwable $e) {
                Log::warning('Price broadcast chunk failed', ['error' => $e->getMessage(), 'count' => count($chunk)]);
            }
        }

        return response()->json(['status' => true, 'count' => count($broadcastTicks)]);
    }

    /** The collector calls this at startup (and periodically) to know what to subscribe to. */
    public function symbols()
    {
        return response()->json(Assets::pluck('symbol'));
    }

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
