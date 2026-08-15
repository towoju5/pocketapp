<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Independent price + history store for datafeedcl.xyz (wss://datafeedcl.xyz/ws,
 * https://datafeedcl.xyz/api/*), fed by StreamDataFeedClTicks
 * (`php artisan ticks:stream-datafeedcl`).
 *
 * Deliberately NOT PriceFeedService or BrokeretFeedService: this uses its own
 * Redis key namespace (datafeedcl:* rather than ticks:*/latest_tick:* or
 * brokeret:*) and stores every symbol datafeedcl streams under its own raw
 * code (e.g. "BTCUSD"), auto-registered into the shared `assets` table tagged
 * price_source='datafeedcl' — same approach as BrokeretFeedService, kept as a
 * fully separate pipeline so it can't interfere with either existing one.
 *
 * Also wraps datafeedcl's REST API directly (fetchSymbolCatalog/
 * fetchLatestFromApi) — used by StreamDataFeedClTicks to know which symbols
 * to subscribe to, and by EvaluateTrade as a live fallback for settling a
 * trade when the Redis-cached tick isn't fresh enough.
 */
class DataFeedClService
{
    /** Seconds since the last received tick after which a symbol is considered offline. */
    private const ONLINE_THRESHOLD_SECONDS = 10;

    /** Rolling backfill window exposed to a fresh chart load. */
    private const HISTORY_WINDOW_SECONDS = 900;

    /** Cap on ticks replayed to a fresh chart load. */
    private const HISTORY_MAX_ENTRIES = 3000;

    /** How long a symbol's tick history is retained in Redis. */
    private const STREAM_RETENTION_SECONDS = 7 * 24 * 60 * 60;

    /** Default profit margin for a datafeedcl symbol auto-registered into `assets`. */
    private const DEFAULT_PROFIT_MARGIN = 0.85;

    /** HTTP timeout for the REST fallback used on the trade settlement path — must stay short, it's in the critical path of EvaluateTrade. */
    private const API_TIMEOUT_SECONDS = 5;

    /**
     * Symbols already confirmed present in the `assets` table this process
     * lifetime — same reasoning as BrokeretFeedService::$registeredSymbols.
     */
    private array $registeredSymbols = [];

    public function updateLatest(string $symbol, float $price, string $category, int $epochMs): void
    {
        $this->ensureAssetRegistered($symbol, $category);

        try {
            Redis::set($this->latestKey($symbol), json_encode([
                's' => $symbol, 't' => $epochMs, 'p' => $price, 'c' => $category,
            ]));
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] updateLatest failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Registers `symbol` in the shared `assets` table the first time this
     * process sees it stream, so it's tradable (TradeController::placeTrade)
     * without waiting for someone to trade it first. Mirrors
     * BrokeretFeedService::ensureAssetRegistered — never touches an existing
     * row, only ever inserts a datafeedcl-tagged one.
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
                'price_source' => 'datafeedcl',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] asset auto-registration failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    public function getLatest(string $symbol): ?array
    {
        try {
            $raw = Redis::get($this->latestKey($symbol));
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] getLatest failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return null;
        }

        if ($raw === null || $raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    public function getPrice(string $symbol): ?float
    {
        $tick = $this->getLatest($symbol);

        return $tick !== null && isset($tick['p']) && is_numeric($tick['p']) ? (float) $tick['p'] : null;
    }

    public function isOnline(string $symbol): bool
    {
        $tick = $this->getLatest($symbol);
        $ts = $tick['t'] ?? null;

        return is_numeric($ts) && (now()->timestamp - ((int) $ts / 1000)) <= self::ONLINE_THRESHOLD_SECONDS;
    }

    /** Appends a {epochMs, price} tick to the symbol's Redis Stream, trimmed to a rolling retention window. */
    public function appendHistoryTick(string $symbol, float $price, int $epochMs): void
    {
        try {
            $cutoffMs = (int) ((now()->timestamp - self::STREAM_RETENTION_SECONDS) * 1000);
            Redis::executeRaw(['XADD', $this->streamKey($symbol), 'MINID', '~', (string) $cutoffMs, '*',
                's', $symbol, 't', (string) $epochMs, 'p', (string) $price]);
            Redis::expire($this->streamKey($symbol), self::STREAM_RETENTION_SECONDS);
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] appendHistoryTick failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Replays the last HISTORY_WINDOW_SECONDS of ticks for `symbol`, oldest
     * first.
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
            Log::warning('[DataFeedClService] getHistoryTicks failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

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

    /**
     * GET {api_url}/api/assets — datafeedcl's own symbol catalog, used by
     * StreamDataFeedClTicks to know which symbols to WS-subscribe to (this
     * feed has no fixed enumerable list to hardcode, and unlike Brokeret it
     * won't stream a symbol unsolicited). Parsed leniently since the exact
     * response envelope isn't documented: accepts a bare array, or an object
     * with an 'assets'/'data' array; each entry may be a bare symbol string
     * or an object exposing 'symbol' (or 's').
     *
     * @return array<int, string>
     */
    public function fetchSymbolCatalog(): array
    {
        $apiUrl = config('services.datafeedcl.api_url');
        if (!$apiUrl) {
            return [];
        }

        try {
            $response = Http::timeout(self::API_TIMEOUT_SECONDS)->get(rtrim($apiUrl, '/') . '/api/assets');
            if (!$response->successful()) {
                return [];
            }
            $body = $response->json();
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] fetchSymbolCatalog failed', ['error' => $e->getMessage()]);

            return [];
        }

        $rows = is_array($body) ? ($body['assets'] ?? $body['data'] ?? $body) : [];
        if (!is_array($rows)) {
            return [];
        }

        $symbols = [];
        foreach ($rows as $row) {
            $symbol = is_string($row) ? $row : (is_array($row) ? ($row['symbol'] ?? $row['s'] ?? null) : null);
            if (is_string($symbol) && $symbol !== '') {
                $symbols[] = $symbol;
            }
        }

        return array_values(array_unique($symbols));
    }

    /**
     * GET {api_url}/api/assets/{symbol}/ticks?limit=1 — the live REST
     * fallback EvaluateTrade reaches for when the Redis-cached tick for a
     * datafeedcl symbol is missing or stale, so a trade still settles
     * against a real datafeedcl price rather than falling through to the
     * generic ad-hoc scrape.
     */
    public function fetchLatestFromApi(string $symbol): ?float
    {
        $apiUrl = config('services.datafeedcl.api_url');
        if (!$apiUrl) {
            return null;
        }

        try {
            $response = Http::timeout(self::API_TIMEOUT_SECONDS)
                ->get(rtrim($apiUrl, '/') . '/api/assets/' . urlencode($symbol) . '/ticks', ['limit' => 1]);
            if (!$response->successful()) {
                return null;
            }
            $body = $response->json();
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] fetchLatestFromApi failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return null;
        }

        $rows = is_array($body) ? ($body['ticks'] ?? $body['data'] ?? $body) : [];
        if (!is_array($rows) || empty($rows)) {
            return null;
        }

        $entry = self::parseTickEntry(is_array($rows[0] ?? null) ? $rows[0] : (is_array($rows) ? reset($rows) : null));

        return $entry['price'] ?? null;
    }

    /**
     * Parses one tick/history entry as sent by datafeedcl over both the WS
     * feed ({id, assetId, price, raw, receivedAt}) and the REST ticks
     * endpoint (same shape) — 'price' is already the precomputed mid, so no
     * bid/ask averaging is needed here (unlike Brokeret). Prefers the
     * source-exchange timestamp packed into 'raw' (raw.t) over 'receivedAt'
     * when both are available, since that's the actual tick time rather than
     * when this feed happened to receive it.
     *
     * @return array{price: float, epochMs: int, category: ?string}|null
     */
    public static function parseTickEntry(?array $entry): ?array
    {
        if ($entry === null || !isset($entry['price']) || !is_numeric($entry['price'])) {
            return null;
        }

        $raw = null;
        if (isset($entry['raw']) && is_string($entry['raw'])) {
            $decoded = json_decode($entry['raw'], true);
            $raw = is_array($decoded) ? $decoded : null;
        }

        $epochMs = null;
        if ($raw !== null && isset($raw['t']) && is_numeric($raw['t'])) {
            $epochMs = (int) $raw['t'];
        } elseif (isset($entry['receivedAt']) && is_string($entry['receivedAt'])) {
            try {
                $epochMs = \Carbon\Carbon::parse($entry['receivedAt'])->valueOf();
            } catch (\Throwable $e) {
                $epochMs = null;
            }
        }
        $epochMs ??= (int) (microtime(true) * 1000);

        return [
            'price' => (float) $entry['price'],
            'epochMs' => $epochMs,
            'category' => is_string($raw['c'] ?? null) ? $raw['c'] : null,
        ];
    }

    private function streamKey(string $symbol): string
    {
        return "datafeedcl:ticks:{$symbol}";
    }

    private function latestKey(string $symbol): string
    {
        return "datafeedcl:latest:{$symbol}";
    }
}
