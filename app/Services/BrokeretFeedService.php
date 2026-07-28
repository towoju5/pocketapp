<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Independent price + history store for the direct-from-PHP Brokeret feed
 * that powers base_url/ui (see StreamBrokeretFeed / dashboard-ui.blade.php).
 *
 * Deliberately NOT PriceFeedService: this uses its own Redis key namespace
 * (brokeret:* rather than PriceFeedService's ticks:* / latest_tick:* keys) and stores every
 * symbol Brokeret streams under its own raw code (e.g. "XAUUSD"), not just
 * ones present in the Assets table under this app's "XAU/USD" catalog
 * naming. That keeps this pipeline fully separate from — and unable to
 * interfere with — the existing iqcent-based pipeline PriceFeedService
 * drives for the main dashboard, which is left completely untouched and
 * keeps working as a fallback regardless of this feed's state.
 */
class BrokeretFeedService
{
    /** Seconds since the last received tick after which a symbol is considered offline. */
    private const ONLINE_THRESHOLD_SECONDS = 45;

    /** Rolling backfill window exposed to a fresh chart load. */
    private const HISTORY_WINDOW_SECONDS = 900;

    /** Cap on ticks replayed to a fresh chart load. */
    private const HISTORY_MAX_ENTRIES = 3000;

    /** How long a symbol's tick history is retained in Redis. */
    private const STREAM_RETENTION_SECONDS = 7 * 24 * 60 * 60;

    public function updateLatest(string $symbol, float $bid, float $ask, string $category, int $epochMs): void
    {
        try {
            Redis::set($this->latestKey($symbol), json_encode([
                's' => $symbol, 't' => $epochMs, 'b' => $bid, 'a' => $ask, 'c' => $category,
            ]));
        } catch (\Throwable $e) {
            Log::warning('[BrokeretFeedService] updateLatest failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    public function getLatest(string $symbol): ?array
    {
        try {
            $raw = Redis::get($this->latestKey($symbol));
        } catch (\Throwable $e) {
            Log::warning('[BrokeretFeedService] getLatest failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return null;
        }

        if ($raw === null || $raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    public function isOnline(string $symbol): bool
    {
        $tick = $this->getLatest($symbol);
        $ts = $tick['t'] ?? null;

        return is_numeric($ts) && (now()->timestamp - ((int) $ts / 1000)) <= self::ONLINE_THRESHOLD_SECONDS;
    }

    /** Appends a {epochMs, mid-price} tick to the symbol's Redis Stream, trimmed to a rolling retention window. */
    public function appendHistoryTick(string $symbol, float $bid, float $ask, int $epochMs): void
    {
        try {
            $cutoffMs = (int) ((now()->timestamp - self::STREAM_RETENTION_SECONDS) * 1000);
            $mid = ($bid + $ask) / 2;
            Redis::executeRaw(['XADD', $this->streamKey($symbol), 'MINID', '~', (string) $cutoffMs, '*',
                's', $symbol, 't', (string) $epochMs, 'p', (string) $mid]);
            Redis::expire($this->streamKey($symbol), self::STREAM_RETENTION_SECONDS);
        } catch (\Throwable $e) {
            Log::warning('[BrokeretFeedService] appendHistoryTick failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Replays the last HISTORY_WINDOW_SECONDS of ticks for `symbol`, oldest
     * first — what a fresh /ui chart load backfills through before live
     * ticks take over.
     *
     * @return array<int, array{0: int, 1: float}> [epochMs, price] pairs, oldest first.
     */
    public function getHistoryTicks(string $symbol): array
    {
        $cutoffMs = (int) ((now()->timestamp - self::HISTORY_WINDOW_SECONDS) * 1000);

        try {
            $entries = Redis::executeRaw([
                'XREVRANGE', $this->streamKey($symbol), '+', (string) $cutoffMs, 'COUNT', (string) self::HISTORY_MAX_ENTRIES,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[BrokeretFeedService] getHistoryTicks failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

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

    private function streamKey(string $symbol): string
    {
        return "brokeret:ticks:{$symbol}";
    }

    private function latestKey(string $symbol): string
    {
        return "brokeret:latest:{$symbol}";
    }
}
