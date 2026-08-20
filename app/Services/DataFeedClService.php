<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin REST client for datafeedcl.xyz (https://datafeedcl.xyz/api/*) — used
 * to resolve entry/settlement prices for price_source='datafeedcl' assets,
 * and to hand the browser a symbol catalog to seed the trading dashboard's
 * asset popover.
 *
 * Deliberately NOT PriceFeedService or BrokeretFeedService: no Redis, no
 * background streaming daemon, nothing persisted — every call hits
 * datafeedcl.xyz directly and returns whatever it has right now. Kept as its
 * own fully separate pipeline (own price_source tag, no shared state) so it
 * can't interfere with either existing one.
 *
 * datafeedcl.xyz's REST API sends no Access-Control-Allow-Origin header, so
 * the browser can't call it directly (confirmed: a plain `fetch()` from
 * another origin is rejected outright). Its WebSocket handshake has no such
 * restriction, though, so the chart's actual LIVE ticks (and their own
 * backfill, delivered inline on subscribe) come from a direct
 * browser→datafeedcl.xyz connection — see
 * resources/js/trading/dataFeedClFeed.js — and never touch this class. Only
 * the one-time symbol catalog (fetchSymbolCatalog) is proxied through here,
 * purely because CORS leaves no other way to reach it from the browser.
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

    /** Major currencies — used to classify a 'currency'-type catalog symbol as majors vs minors. */
    private const MAJOR_CURRENCIES = ['EUR', 'USD', 'GBP', 'JPY', 'CHF', 'CAD', 'AUD', 'NZD'];

    /**
     * Symbols already confirmed present in the `assets` table this process
     * lifetime — avoids an insertOrIgnore() on every single call once a
     * symbol is known-registered. Same reasoning as
     * BrokeretFeedService::$registeredSymbols.
     */
    private array $registeredSymbols = [];

    /**
     * GET {api_url}/api/tick?asset={symbol}&timestamp={now} — the nearest
     * tick datafeedcl has to the given timestamp. Passing "now" is how this
     * endpoint doubles as a "latest price" lookup; there's no separate
     * latest-tick endpoint. Also auto-registers `symbol` into the shared
     * `assets` table (price_source='datafeedcl') the first time it's
     * successfully resolved, so it's tradable (TradeController::placeTrade)
     * without needing to be pre-seeded — mirrors
     * BrokeretFeedService::ensureAssetRegistered, never touches an existing
     * row.
     *
     * @return array{price: float, epochMs: int}|null null if the request failed or datafeedcl has no tick for this symbol.
     */
    public function fetchLatestTick(string $symbol): ?array
    {
        $apiUrl = config('services.datafeedcl.api_url');
        if (!$apiUrl) {
            return null;
        }

        try {
            $response = Http::timeout(self::API_TIMEOUT_SECONDS)
                ->get(rtrim($apiUrl, '/') . '/api/tick', [
                    'asset' => $symbol,
                    'timestamp' => now()->timestamp,
                ]);
            if (!$response->successful()) {
                return null;
            }
            $body = $response->json();
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] fetchLatestTick failed', ['symbol' => $symbol, 'error' => $e->getMessage()]);

            return null;
        }

        if (!is_array($body) || !isset($body['value']) || !is_numeric($body['value']) || !isset($body['timestamp']) || !is_numeric($body['timestamp'])) {
            return null;
        }

        $this->ensureAssetRegistered($symbol);

        return [
            'price' => (float) $body['value'],
            'epochMs' => (int) round(((float) $body['timestamp']) * 1000),
        ];
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
     * GET {api_url}/api/assets — datafeedcl's symbol catalog:
     * {type, asset, label, payout, isOtc, active, expTime, min_expiration}.
     * Fetched once, server-side, at dashboard page render (see
     * HomeController) and handed to the browser's direct WebSocket client so
     * it knows which symbols exist — the browser can't call this itself (no
     * CORS headers). Only currently-active (tradable) symbols are returned;
     * `active: false` rows are closed-market duplicates of an `_otc` sibling
     * and aren't worth showing in a live popover.
     *
     * @return array<int, array{symbol: string, name: string, category: string, payout: float, isOtc: bool}>
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
            $rows = $response->json();
        } catch (\Throwable $e) {
            Log::warning('[DataFeedClService] fetchSymbolCatalog failed', ['error' => $e->getMessage()]);

            return [];
        }

        if (!is_array($rows)) {
            return [];
        }

        $catalog = [];
        foreach ($rows as $row) {
            if (!is_array($row) || !isset($row['asset']) || !is_string($row['asset']) || $row['asset'] === '') {
                continue;
            }
            if (($row['active'] ?? false) !== true) {
                continue;
            }

            $catalog[] = [
                'symbol' => $row['asset'],
                'name' => is_string($row['label'] ?? null) ? $row['label'] : $row['asset'],
                'category' => self::classify(is_string($row['type'] ?? null) ? $row['type'] : null, $row['asset']),
                'payout' => is_numeric($row['payout'] ?? null) ? ((float) $row['payout']) / 100 : self::DEFAULT_PROFIT_MARGIN,
                'isOtc' => (bool) ($row['isOtc'] ?? false),
            ];
        }

        return $catalog;
    }

    /** Maps datafeedcl's catalog `type` to this app's popover category keys (majors/minors/exotics/metals/crypto/stocks/indices). */
    private static function classify(?string $type, string $symbol): string
    {
        return match ($type) {
            'commodity' => 'metals',
            'cryptocurrency' => 'crypto',
            'stock' => 'stocks',
            'index' => 'indices',
            'currency' => self::classifyCurrencyPair($symbol),
            default => 'exotics',
        };
    }

    /** 'EURUSD_otc' -> 'majors', 'EURTRY_otc' -> 'minors' — both sides of the pair are majors, or it isn't. */
    private static function classifyCurrencyPair(string $symbol): string
    {
        $base = strtoupper(preg_replace('/_otc$/i', '', $symbol));
        if (strlen($base) !== 6) {
            return 'minors';
        }

        $left = substr($base, 0, 3);
        $right = substr($base, 3, 3);

        return in_array($left, self::MAJOR_CURRENCIES, true) && in_array($right, self::MAJOR_CURRENCIES, true)
            ? 'majors'
            : 'minors';
    }

    /**
     * Registers `symbol` in the shared `assets` table the first time this
     * process resolves a price for it, so it's tradable without waiting for
     * someone to trade it first. No catalog data (category/payout) is
     * available at this call site — see fetchSymbolCatalog for the richer,
     * browser-facing catalog; this is just enough to satisfy the `assets`
     * table's foreign keys.
     */
    private function ensureAssetRegistered(string $symbol): void
    {
        if (isset($this->registeredSymbols[$symbol])) {
            return;
        }
        $this->registeredSymbols[$symbol] = true;

        try {
            DB::table('assets')->insertOrIgnore([
                'symbol' => $symbol,
                'name' => $symbol,
                'asset_group' => 'OTHER',
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
}
