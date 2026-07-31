<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
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
    private const ONLINE_THRESHOLD_SECONDS = 10;

    /** Rolling backfill window exposed to a fresh chart load. */
    private const HISTORY_WINDOW_SECONDS = 900;

    /** Cap on ticks replayed to a fresh chart load. */
    private const HISTORY_MAX_ENTRIES = 3000;

    /** How long a symbol's tick history is retained in Redis. */
    private const STREAM_RETENTION_SECONDS = 7 * 24 * 60 * 60;

    /** Default profit margin for a Brokeret symbol auto-registered into `assets` — matches the Gold migration's. */
    private const DEFAULT_PROFIT_MARGIN = 0.85;

    /**
     * Symbols already confirmed present in the `assets` table this process
     * lifetime — avoids an insertOrIgnore() on every single tick (several
     * per second, per symbol) once a symbol is known-registered. Resets on
     * restart, which just costs one extra (still cheap, still ignored-if-
     * exists) query per symbol the first time it's seen again — never a
     * correctness issue, only ever a minor efficiency one.
     */
    private array $registeredSymbols = [];

    public function updateLatest(string $symbol, float $bid, float $ask, string $category, int $epochMs): void
    {
        $this->ensureAssetRegistered($symbol, $category);

        try {
            Redis::set($this->latestKey($symbol), json_encode([
                's' => $symbol, 't' => $epochMs, 'b' => $bid, 'a' => $ask, 'c' => $category,
            ]));
        } catch (\Throwable $e) {
            Log::warning('[BrokeretFeedService] updateLatest failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Registers `symbol` in the shared `assets` table the first time this
     * process sees it stream, so it's tradable (TradeController::placeTrade)
     * and appears in any DB-gated asset list (e.g. ExpressTradeController)
     * without waiting for someone to trade it first. Brokeret has no fixed,
     * enumerable symbol catalog to pre-seed from (see StreamBrokeretFeed's
     * docblock) — this is that catalog, built as symbols are actually seen.
     *
     * Deliberately does NOT touch PriceFeedService, the 'asset-prices'
     * channel, or anything iqcent-side — this only ever inserts a Brokeret-
     * tagged row (price_source='brokeret'), never modifies an existing one,
     * so it can't clobber an iqcent asset even on a symbol collision.
     */
    private function ensureAssetRegistered(string $symbol, string $category): void
    {
        if (isset($this->registeredSymbols[$symbol])) {
            return;
        }
        $this->registeredSymbols[$symbol] = true;

        try {
            DB::table('assets')->insertOrIgnore([
                'symbol' => $symbol,
                'name' => $symbol,
                'asset_group' => strtoupper($category ?: 'OTHER'),
                'exchange_float' => 0,
                'asset_profit_margin' => self::DEFAULT_PROFIT_MARGIN,
                'is_otc' => false,
                'price_source' => 'brokeret',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('[BrokeretFeedService] asset auto-registration failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
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
