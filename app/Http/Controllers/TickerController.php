<?php

namespace App\Http\Controllers;

use App\Events\AssetPriceBatchUpdated;
use App\Services\PriceFeedService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Panther\Client;

/**
 * The backend price collector — the one real connection to iqcent's price
 * feed. Ticks flow into PriceFeedService (same store PriceCollectorController
 * and the charts already read from) and out over Reverb via
 * AssetPriceBatchUpdated, exactly like the retired Node/Playwright collector
 * did.
 *
 * Runs as a pool of long-lived CLI processes, NOT as a web request — see
 * `php artisan ticks:collect` (app/Console/Commands/CollectTicks.php) and
 * deploy/supervisor/pocketapp-ticker-collector.conf. Each process drives its
 * own headless Chrome instance forever, so this can never run inside a normal
 * php-fpm/Octane request lifecycle (no timeout would survive it, and one
 * request thread holding one browser open is a bad trade against every other
 * request the app needs to serve).
 *
 * A real browser is required rather than a plain WS/HTTP client because
 * iqcent sits behind Cloudflare, which 403s non-browser connection attempts
 * before the handshake completes.
 *
 * iqcent also appears to cap how many SUBSCRIBE.TICK subscriptions a single
 * WS connection actually honors — one connection subscribed to the full
 * catalog only ever ticked for a small fraction of it, regardless of send
 * pacing (this was verified while the same feed ran through Playwright).
 * Splitting the catalog into small batches — one Chrome client per batch,
 * paced subscriptions within each — keeps every connection's count low
 * enough to stay reliable.
 */
class TickerController extends Controller
{
    private const IQCENT_ORIGIN = 'https://iqcent.com';
    private const IQCENT_WS_URL = 'wss://iqcent.com/trade-api-ws/api/ws/price';

    /** How often buffered ticks are drained from the browser and stored/broadcast. */
    private const POLL_INTERVAL_SECONDS = 1;

    /** How long to wait for the WS handshake before giving up on this batch. */
    private const CONNECT_TIMEOUT_SECONDS = 30;

    public function __construct(private readonly PriceFeedService $priceFeed)
    {
    }

    /**
     * Runs forever: opens one headless Chrome client, subscribes it to the
     * given symbols (paced 5-10s apart so a ~10-symbol batch doesn't look
     * like an automated burst), then continuously drains ticks into
     * PriceFeedService and broadcasts them.
     *
     * Only returns via a thrown exception (browser crash, WS never opening,
     * etc) — the calling command lets that propagate and exit non-zero on
     * purpose, so Supervisor restarts the process with a clean browser
     * fingerprint instead of this method retrying internally against a
     * possibly-flagged session.
     *
     * @param string[] $symbols
     */
    public function collectBatch(array $symbols, int $batchIndex = 0): void
    {
        if (empty($symbols)) {
            return;
        }

        $client = Client::createChromeClient(null, [
            '--headless',
            '--no-sandbox',
            '--disable-sandbox',
            '--disable-gpu',
            '--disable-dev-shm-usage',
            '--disable-software-rasterizer',
            '--disable-blink-features=AutomationControlled',
            '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ]);

        try {
            Log::info("[ticker:batch {$batchIndex}] navigating to iqcent for Cloudflare clearance", [
                'symbols' => count($symbols),
            ]);

            // Load the base site first so the WS connection presents like a
            // real visitor's tab, with Cloudflare's cookies already set.
            $client->request('GET', self::IQCENT_ORIGIN);
            sleep(5);

            $wsUrl = self::IQCENT_WS_URL;
            $client->executeScript(<<<JS
                window.__tickerBuffer = [];
                window.__tickerReady = false;

                const ws = new WebSocket("{$wsUrl}");

                ws.onopen = function () {
                    window.__tickerReady = true;
                };

                ws.onmessage = function (event) {
                    try {
                        const msg = JSON.parse(event.data);
                        // Tick pushes key the symbol as "s" — a different
                        // field than "id", which is what SUBSCRIBE.TICK
                        // requests use to name the symbol being subscribed.
                        if (msg && typeof msg.p === 'number' && typeof msg.t === 'number' && msg.s) {
                            window.__tickerBuffer.push({ s: msg.s, p: msg.p, t: msg.t });
                        }
                    } catch (e) {
                        // non-JSON / non-tick frame — ignore
                    }
                };

                window.__tickerSend = function (payload) {
                    if (ws.readyState === WebSocket.OPEN) {
                        ws.send(JSON.stringify(payload));
                        return true;
                    }
                    return false;
                };
            JS);

            $connected = false;
            for ($i = 0; $i < self::CONNECT_TIMEOUT_SECONDS; $i++) {
                if ($client->executeScript('return window.__tickerReady === true;')) {
                    $connected = true;
                    break;
                }
                sleep(1);
            }

            if (!$connected) {
                throw new \RuntimeException("[ticker:batch {$batchIndex}] WebSocket never reached OPEN state");
            }

            // Subscribe one symbol at a time, spaced out rather than sent as
            // a tight burst — pacing like a person clicking through assets,
            // not a script blasting the whole batch at once.
            $last = array_key_last($symbols);
            foreach ($symbols as $i => $symbol) {
                $client->executeScript(sprintf(
                    'window.__tickerSend(%s);',
                    json_encode(['id' => $symbol, 'param' => 'Option', 'operation' => 'SUBSCRIBE.TICK'])
                ));

                Log::info("[ticker:batch {$batchIndex}] subscribed", [
                    'symbol' => $symbol,
                    'progress' => ($i + 1) . '/' . count($symbols),
                ]);

                if ($i !== $last) {
                    sleep(random_int(5, 10));
                }
            }

            Log::info("[ticker:batch {$batchIndex}] all symbols subscribed, streaming ticks");

            while (true) {
                $ticks = $client->executeScript(<<<'JS'
                    const data = window.__tickerBuffer;
                    window.__tickerBuffer = [];
                    return data;
                JS);

                if (!empty($ticks)) {
                    $this->storeTicks($ticks);
                }

                sleep(self::POLL_INTERVAL_SECONDS);
            }
        } catch (\Throwable $e) {
            Log::error("[ticker:batch {$batchIndex}] failed", ['error' => $e->getMessage()]);
            throw $e;
        } finally {
            $client->quit();
        }
    }

    /**
     * Persists ticks into the same store PriceCollectorController::history
     * and the "online" status check already read from, then broadcasts them
     * on the same channel the charts already listen to (AssetPriceBatchUpdated
     * on 'asset-prices').
     *
     * @param array<int, array{s: string, p: float, t: int}> $ticks
     */
    private function storeTicks(array $ticks): void
    {
        $broadcastTicks = [];

        foreach ($ticks as $tick) {
            $symbol = (string) $tick['s'];
            $price = (float) $tick['p'];
            $epochMs = (int) $tick['t'];

            $this->priceFeed->updatePrice($symbol, $price);
            $this->priceFeed->appendHistoryTick($symbol, $price, $epochMs);

            $broadcastTicks[] = ['symbol' => $symbol, 'price' => $price, 't' => $epochMs];
        }

        // Reverb caps message size — chunk, and never let a broadcast
        // failure take down the collector loop; the price/history writes
        // above already succeeded, so a dropped broadcast is just a stale
        // chart until the next flush, not lost data.
        foreach (array_chunk($broadcastTicks, 100) as $chunk) {
            try {
                broadcast(new AssetPriceBatchUpdated($chunk));
            } catch (\Throwable $e) {
                Log::warning('Price broadcast chunk failed', ['error' => $e->getMessage(), 'count' => count($chunk)]);
            }
        }
    }
}
