<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin REST client for datafeedcl.xyz (https://datafeedcl.xyz/api/*) — used
 * to resolve entry/settlement prices for price_source='datafeedcl' assets.
 *
 * Deliberately NOT PriceFeedService or BrokeretFeedService: no Redis, no
 * background streaming daemon, nothing persisted. Every call hits
 * datafeedcl.xyz directly and returns whatever it has right now — kept as
 * its own fully separate pipeline (own price_source tag, no shared state)
 * so it can't interfere with either existing one.
 *
 * Callers should fetch once per decision via fetchLatestTick() and derive
 * both "is this symbol online" and "what's the price" from that single
 * result, rather than calling isOnline()/getPrice() separately — each call
 * here is a live HTTP round trip, not a cache read.
 */
class DataFeedClService
{
    /** How old a tick's own timestamp can be before the symbol is considered offline/stale. */
    private const ONLINE_THRESHOLD_SECONDS = 60;

    /** Default profit margin for a datafeedcl symbol auto-registered into `assets`. */
    private const DEFAULT_PROFIT_MARGIN = 0.85;

    /** HTTP timeout — this sits in the request/settlement critical path, so it must stay short. */
    private const API_TIMEOUT_SECONDS = 5;

    /**
     * Symbols already confirmed present in the `assets` table this process
     * lifetime — avoids an insertOrIgnore() on every single call once a
     * symbol is known-registered. Same reasoning as
     * BrokeretFeedService::$registeredSymbols.
     */
    private array $registeredSymbols = [];

    /**
     * GET {api_url}/api/assets/{symbol}/ticks?limit=1 — the single source of
     * truth this service reads from. Also auto-registers `symbol` into the
     * shared `assets` table (price_source='datafeedcl') the first time it's
     * successfully resolved, so it's tradable (TradeController::placeTrade)
     * without needing to be pre-seeded — mirrors
     * BrokeretFeedService::ensureAssetRegistered, never touches an existing
     * row.
     *
     * @return array{price: float, epochMs: int, category: ?string}|null null if the request failed or the symbol has no ticks.
     */
    public function fetchLatestTick(string $symbol): ?array
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
            Log::warning('[DataFeedClService] fetchLatestTick failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return null;
        }

        $rows = is_array($body) ? ($body['ticks'] ?? $body['data'] ?? $body) : [];
        if (!is_array($rows) || empty($rows)) {
            return null;
        }

        $first = reset($rows);
        $tick = self::parseTickEntry(is_array($first) ? $first : null);
        if ($tick === null) {
            return null;
        }

        $this->ensureAssetRegistered($symbol, $tick['category'] ?? 'other');

        return $tick;
    }

    /** Convenience wrapper over fetchLatestTick() for callers that only need the price. */
    public function getPrice(string $symbol): ?float
    {
        return $this->fetchLatestTick($symbol)['price'] ?? null;
    }

    /** Convenience wrapper over fetchLatestTick() for callers that only need an online check. Prefer fetchLatestTick() directly when you need both, to avoid a second HTTP call. */
    public function isOnline(string $symbol): bool
    {
        $tick = $this->fetchLatestTick($symbol);

        return $tick !== null && (now()->valueOf() - $tick['epochMs']) <= (self::ONLINE_THRESHOLD_SECONDS * 1000);
    }

    /**
     * Registers `symbol` in the shared `assets` table the first time this
     * process resolves a price for it, so it's tradable without waiting for
     * someone to trade it first.
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

    /**
     * Parses one tick entry as returned by GET /api/assets/{symbol}/ticks
     * ({id, assetId, price, raw, receivedAt}) — 'price' is already the
     * precomputed mid, so no bid/ask averaging is needed here (unlike
     * Brokeret). Prefers the source-exchange timestamp packed into 'raw'
     * (raw.t) over 'receivedAt' when both are available, since that's the
     * actual tick time rather than when datafeedcl happened to receive it.
     *
     * @return array{price: float, epochMs: int, category: ?string}|null
     */
    private static function parseTickEntry(?array $entry): ?array
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
}
