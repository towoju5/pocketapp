<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PriceFeedService
{
    /** Seconds since the last received tick after which a symbol is considered offline. */
    private const ONLINE_THRESHOLD_SECONDS = 45;

    /** How long a cached price survives with no fresh tick before it expires outright. */
    private const CACHE_TTL_SECONDS = 300;

    /** Rolling backfill window exposed to new chart loads. */
    private const HISTORY_WINDOW_SECONDS = 600;

    /** Hard cap per symbol regardless of tick rate, so one very active symbol can't balloon the cache entry. */
    private const HISTORY_MAX_ENTRIES = 1500;

    /** Slightly longer than the window itself so a quiet symbol's backfill doesn't vanish early. */
    private const HISTORY_TTL_SECONDS = 900;

    public function updatePrice(string $symbol, float $price): void
    {
        Cache::put($this->priceKey($symbol), $price, self::CACHE_TTL_SECONDS);
        Cache::put($this->tsKey($symbol), now()->timestamp, self::CACHE_TTL_SECONDS);
    }

    public function getPrice(string $symbol): ?float
    {
        $price = Cache::get($this->priceKey($symbol));

        return $price === null ? null : (float) $price;
    }

    public function lastUpdatedAt(string $symbol): ?int
    {
        $ts = Cache::get($this->tsKey($symbol));

        return $ts === null ? null : (int) $ts;
    }

    public function isOnline(string $symbol): bool
    {
        $ts = $this->lastUpdatedAt($symbol);

        return $ts !== null && (now()->timestamp - $ts) <= self::ONLINE_THRESHOLD_SECONDS;
    }

    /**
     * Appends a raw {epochMs, price} tick to a symbol's rolling backfill
     * window — this is what a fresh chart load replays through the same
     * candle-bucketing logic the live feed uses (see AssetFeed.fetchHistory
     * in chart.js), instead of depending on iqcent's own history REST
     * endpoint (unreliable — same Cloudflare protection that blocks
     * server-side WS connections also intermittently blocks that fetch).
     */
    public function appendHistoryTick(string $symbol, float $price, int $epochMs): void
    {
        $key = $this->historyKey($symbol);
        $entries = Cache::get($key, []);
        $entries[] = [$epochMs, $price];

        $cutoffMs = (now()->timestamp - self::HISTORY_WINDOW_SECONDS) * 1000;
        $entries = array_values(array_filter($entries, fn ($e) => $e[0] >= $cutoffMs));
        if (count($entries) > self::HISTORY_MAX_ENTRIES) {
            $entries = array_slice($entries, -self::HISTORY_MAX_ENTRIES);
        }

        Cache::put($key, $entries, self::HISTORY_TTL_SECONDS);
    }

    /** @return array<int, array{0: int, 1: float}> [epochMs, price] pairs, oldest first. */
    public function getHistoryTicks(string $symbol): array
    {
        return Cache::get($this->historyKey($symbol), []);
    }

    private function historyKey(string $symbol): string
    {
        return "asset_tick_history:{$symbol}";
    }

    private function priceKey(string $symbol): string
    {
        return "asset_price:{$symbol}";
    }

    private function tsKey(string $symbol): string
    {
        return "asset_price_ts:{$symbol}";
    }
}
