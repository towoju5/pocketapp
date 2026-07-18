<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PriceFeedService
{
    /** Seconds since the last received tick after which a symbol is considered offline. */
    private const ONLINE_THRESHOLD_SECONDS = 45;

    /** How long a cached price survives with no fresh tick before it expires outright. */
    private const CACHE_TTL_SECONDS = 300;

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

    private function priceKey(string $symbol): string
    {
        return "asset_price:{$symbol}";
    }

    private function tsKey(string $symbol): string
    {
        return "asset_price_ts:{$symbol}";
    }
}
