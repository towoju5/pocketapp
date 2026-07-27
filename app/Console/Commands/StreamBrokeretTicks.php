<?php

namespace App\Console\Commands;

use App\Models\Assets;
use App\Services\PriceFeedService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use WebSocket\Client as WsClient;

/**
 * Connects directly to Brokeret's live price feed (wss://feed.brokeret.com/ws)
 * from the Laravel backend and writes ticks into the exact same Redis schema
 * websockets-setup/ws.py uses (ticks:{symbol} streams + latest_tick:{symbol}),
 * via PriceFeedService — so app/Console/Commands/BridgeRedisTicks.php (which
 * already tails every ticks:* stream regardless of who wrote it) picks these
 * up and rebroadcasts them over Reverb with no changes needed there or in
 * chart.js.
 *
 * Unlike iqcent (see TickerController's docblock), Brokeret isn't behind
 * Cloudflare, so a plain WebSocket client works here — no headless browser
 * needed. That also means the feed's URL/API key never has to reach the
 * frontend: the browser only ever talks to this app's own Reverb server,
 * never to Brokeret directly, which is the whole point of running this as a
 * backend process rather than a client-side WebSocket.
 *
 * Brokeret pushes an unsolicited stream (no subscribe message required):
 * one 'connected' message, one 'snapshot' with every symbol's current
 * bid/ask, then continuous 'ticks' batches, plus a 'heartbeat' every ~15s
 * (see the 'connected' message's heartbeat_interval). Symbols are compact
 * (e.g. "EURUSD", "BTCUSD", "XAUUSD") rather than this app's "EUR/USD" /
 * "BTC/USDT.X" style, so resolveTargets() below maps each one onto every
 * matching Assets row before storing.
 *
 * Runs forever as one long-lived process (see
 * deploy/supervisor/pocketapp-brokeret-stream.conf), same as
 * ticks:bridge-redis and ws.py before it.
 */
class StreamBrokeretTicks extends Command
{
    protected $signature = 'ticks:stream-brokeret
        {--reconnect-delay=3 : Seconds to wait before reconnecting after a dropped connection}
        {--symbol-rescan=60 : Seconds between re-loading the Assets catalog (picks up newly added assets without a restart)}';

    protected $description = "Connects to Brokeret's WebSocket price feed and streams ticks into Redis for the trading dashboard.";

    /** Message types that carry an array of {s,b,a,t,c} price entries. */
    private const TICK_MESSAGE_TYPES = ['snapshot', 'ticks'];

    /**
     * Brokeret quotes crypto in USD (e.g. "BTCUSD"); this app's Assets
     * catalog only has crypto quoted in USDT (e.g. "BTC/USDT"). USDT is a
     * USD-pegged stablecoin, so treating the two as equivalent for price
     * purposes is close enough for charting/trading, and there's no USDT
     * feed here to prefer instead.
     */
    private const CRYPTO_QUOTE_ALIASES = ['USD' => 'USDT'];

    private PriceFeedService $priceFeed;

    private int $symbolRescanSeconds = 60;

    /** @var array<string, true> Known Assets symbols, refreshed periodically. */
    private array $knownSymbols = [];

    private int $lastSymbolScan = 0;

    public function handle(PriceFeedService $priceFeed): int
    {
        $this->priceFeed = $priceFeed;
        $this->symbolRescanSeconds = max(5, (int) $this->option('symbol-rescan'));

        $wsUrl = config('services.brokeret.ws_url');
        $apiKey = config('services.brokeret.api_key');
        $reconnectDelay = max(1, (int) $this->option('reconnect-delay'));

        if (! $wsUrl) {
            $this->error('services.brokeret.ws_url is not configured.');

            return self::FAILURE;
        }

        $this->refreshKnownSymbols();

        $url = $wsUrl . (str_contains($wsUrl, '?') ? '&' : '?') . 'apikey=' . urlencode((string) $apiKey);

        $this->info('Brokeret tick stream starting...');

        while (true) {
            try {
                $this->streamOnce($url);
            } catch (\Throwable $e) {
                Log::warning('[ticks:stream-brokeret] connection dropped', ['error' => $e->getMessage()]);
            }

            sleep($reconnectDelay);
        }
    }

    private function streamOnce(string $url): void
    {
        $client = new WsClient($url, [
            // Comfortably above Brokeret's ~15s heartbeat interval, so a
            // missed heartbeat or two doesn't force a reconnect, but a truly
            // dead connection still gets noticed and replaced.
            'timeout' => 45,
        ]);

        $this->info('Connected to Brokeret feed.');

        try {
            while (true) {
                $this->refreshKnownSymbols();

                $message = $client->receive();
                if (! is_string($message) || $message === '') {
                    continue;
                }

                $payload = json_decode($message, true);
                if (! is_array($payload) || ! in_array($payload['type'] ?? null, self::TICK_MESSAGE_TYPES, true)) {
                    continue;
                }

                foreach ($payload['data'] ?? [] as $entry) {
                    $this->handleEntry($entry);
                }
            }
        } finally {
            try {
                $client->close();
            } catch (\Throwable $e) {
                // Connection is already gone — nothing to clean up.
            }
        }
    }

    /** @param mixed $entry */
    private function handleEntry($entry): void
    {
        if (! is_array($entry)
            || ! isset($entry['s'], $entry['b'], $entry['a'], $entry['t'])
            || ! is_numeric($entry['b']) || ! is_numeric($entry['a'])
        ) {
            return;
        }

        $rawSymbol = (string) $entry['s'];
        $category = (string) ($entry['c'] ?? '');
        $epochMs = (int) $entry['t'];
        $mid = ((float) $entry['b'] + (float) $entry['a']) / 2;

        foreach ($this->resolveTargets($rawSymbol, $category) as [$symbol, $inverted]) {
            if (! isset($this->knownSymbols[$symbol])) {
                continue;
            }

            $price = $inverted ? ($mid != 0.0 ? 1 / $mid : 0.0) : $mid;

            $this->priceFeed->updatePrice($symbol, $price, $epochMs);
            $this->priceFeed->appendHistoryTick($symbol, $price, $epochMs);
        }
    }

    /**
     * Brokeret's symbols are bare currency-pair codes (e.g. "EURUSD",
     * "XAUUSD", "BTCUSD") with no separator and no OTC suffix, while this
     * app's Assets catalog uses "EUR/USD" (and, for the OTC copy of the same
     * pair, "EUR/USD.X"). Returns every {Assets symbol, inverted} candidate
     * this raw symbol could satisfy — both quoted directions, both the plain
     * and .X variants — so it's on the caller (handleEntry) to skip whichever
     * candidates aren't actually in the catalog.
     *
     * @return array<int, array{0: string, 1: bool}> [assetSymbol, invertPrice]
     */
    private function resolveTargets(string $rawSymbol, string $category): array
    {
        if ($category === 'crypto') {
            // Every crypto quote Brokeret uses (USD/EUR/BTC) is 3 chars.
            if (strlen($rawSymbol) <= 3) {
                return [];
            }
            $quote = strtoupper(substr($rawSymbol, -3));
            $base = strtoupper(substr($rawSymbol, 0, -3));
            $quotes = array_unique([$quote, self::CRYPTO_QUOTE_ALIASES[$quote] ?? $quote]);
        } else {
            // Forex majors/minors/exotics and metals are always base(3)+quote(3).
            if (strlen($rawSymbol) !== 6) {
                return [];
            }
            $base = strtoupper(substr($rawSymbol, 0, 3));
            $quote = strtoupper(substr($rawSymbol, 3, 3));
            $quotes = [$quote];
        }

        $targets = [];
        foreach ($quotes as $q) {
            $targets[] = ["{$base}/{$q}", false];
            $targets[] = ["{$base}/{$q}.X", false];
            $targets[] = ["{$q}/{$base}", true];
            $targets[] = ["{$q}/{$base}.X", true];
        }

        return $targets;
    }

    private function refreshKnownSymbols(): void
    {
        if (! empty($this->knownSymbols) && (time() - $this->lastSymbolScan) < $this->symbolRescanSeconds) {
            return;
        }

        $this->knownSymbols = array_fill_keys(Assets::pluck('symbol')->all(), true);
        $this->lastSymbolScan = time();
    }
}
