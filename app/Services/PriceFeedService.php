<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Reads (and, for legacy callers, writes) live price + tick history straight
 * from Redis — NOT Laravel's Cache. The Redis instance is populated by
 * websockets-setup/ws.py (a Playwright collector that subscribes to iqcent's
 * WS feed directly and writes ticks itself), which owns this key schema:
 *   latest_tick:{symbol} -> JSON string {"s","t","p"}, most recent tick
 *   ticks:{symbol}       -> Redis Stream of {s,t,p} entries, full history
 * ws.py trims both to a rolling 7-day window on every write. This class talks
 * to the "default" Redis connection (config/database.php), which must point
 * at the same host/port/db ws.py is configured for.
 *
 * TickerController's headless-Chrome collector (the previous price source)
 * still calls updatePrice()/appendHistoryTick() below, kept functional in
 * case that pipeline is ever re-enabled — but it now writes into this same
 * Redis schema rather than Laravel's cache, so there is exactly one price
 * store regardless of which collector is running.
 */
class PriceFeedService
{
    /** Seconds since the last received tick after which a symbol is considered offline. */
    private const ONLINE_THRESHOLD_SECONDS = 45;

    /** Rolling backfill window exposed to new chart loads. */
    private const HISTORY_WINDOW_SECONDS = 900;

    /** Cap on ticks replayed to a fresh chart load, regardless of how much a very active symbol has in-window. */
    private const HISTORY_MAX_ENTRIES = 3000;

    /** Matches ws.py's RETENTION_SECONDS — both sides trim the stream to the same window. */
    private const STREAM_RETENTION_SECONDS = 7 * 24 * 60 * 60;

    public function updatePrice(string $symbol, float $price, ?int $epochMs = null): void
    {
        $epochMs ??= (int) (microtime(true) * 1000);

        try {
            Redis::set($this->latestKey($symbol), json_encode(['s' => $symbol, 't' => $epochMs, 'p' => $price]));
        } catch (\Throwable $e) {
            Log::warning('[PriceFeedService] updatePrice failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    public function getPrice(string $symbol): ?float
    {
        $tick = $this->getLatestTick($symbol);

        return $tick !== null && isset($tick['p']) && is_numeric($tick['p']) ? (float) $tick['p'] : null;
    }

    public function lastUpdatedAt(string $symbol): ?int
    {
        $tick = $this->getLatestTick($symbol);

        return $tick !== null && isset($tick['t']) && is_numeric($tick['t']) ? (int) ($tick['t'] / 1000) : null;
    }

    public function isOnline(string $symbol): bool
    {
        $ts = $this->lastUpdatedAt($symbol);

        return $ts !== null && (now()->timestamp - $ts) <= self::ONLINE_THRESHOLD_SECONDS;
    }

    /**
     * Appends a raw {epochMs, price} tick to the symbol's Redis Stream, using
     * ws.py's own MINID-based 7-day trim so both writers keep the same
     * retention window regardless of which one is currently running.
     */
    public function appendHistoryTick(string $symbol, float $price, int $epochMs): void
    {
        try {
            $cutoffMs = (int) ((now()->timestamp - self::STREAM_RETENTION_SECONDS) * 1000);
            Redis::executeRaw(['XADD', $this->streamKey($symbol), 'MINID', '~', (string) $cutoffMs, '*',
                's', $symbol, 't', (string) $epochMs, 'p', (string) $price]);
            Redis::expire($this->streamKey($symbol), self::STREAM_RETENTION_SECONDS);
        } catch (\Throwable $e) {
            Log::warning('[PriceFeedService] appendHistoryTick failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Replays the last HISTORY_WINDOW_SECONDS of ticks for `symbol` from its
     * Redis Stream, oldest first — this is what a fresh chart load backfills
     * through the same candle-bucketing logic the live feed uses (see
     * AssetFeed.fetchHistory in chart.js).
     *
     * @return array<int, array{0: int, 1: float}> [epochMs, price] pairs, oldest first.
     */
    public function getHistoryTicks(string $symbol): array
    {
        $cutoffMs = (int) ((now()->timestamp - self::HISTORY_WINDOW_SECONDS) * 1000);

        try {
            // Newest-first (XREVRANGE from '+' down to the cutoff) so the
            // COUNT cap keeps the most recent ticks when a very active
            // symbol has produced more entries than the cap within the
            // window; re-sorted oldest-first below for chart replay.
            $entries = Redis::executeRaw([
                'XREVRANGE', $this->streamKey($symbol), '+', (string) $cutoffMs, 'COUNT', (string) self::HISTORY_MAX_ENTRIES,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[PriceFeedService] getHistoryTicks failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return [];
        }

        $ticks = [];
        foreach ($entries ?: [] as [$id, $fields]) {
            $kv = [];
            for ($i = 0; $i < count($fields); $i += 2) {
                $kv[$fields[$i]] = $fields[$i + 1] ?? null;
            }
            if (!isset($kv['t'], $kv['p']) || $kv['p'] === '' || !is_numeric($kv['p'])) {
                continue;
            }
            $ticks[] = [(int) $kv['t'], (float) $kv['p']];
        }

        usort($ticks, fn ($a, $b) => $a[0] <=> $b[0]);

        return $ticks;
    }

    private function getLatestTick(string $symbol): ?array
    {
        try {
            $raw = Redis::get($this->latestKey($symbol));
        } catch (\Throwable $e) {
            Log::warning('[PriceFeedService] getLatestTick failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return null;
        }

        if ($raw === null || $raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function streamKey(string $symbol): string
    {
        return "ticks:{$symbol}";
    }

    private function latestKey(string $symbol): string
    {
        return "latest_tick:{$symbol}";
    }
}
