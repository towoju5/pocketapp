<?php

namespace App\Console\Commands;

use App\Events\BrokeretTicksUpdated;
use App\Services\BrokeretFeedService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use WebSocket\Client as WsClient;

/**
 * Fully independent price pipeline dedicated to base_url/ui. Connects
 * directly to Brokeret's WebSocket feed from the backend (it isn't behind
 * Cloudflare, so a plain WebSocket client works — no headless-browser
 * workaround needed, same reasoning as StreamBrokeretTicks) and persists +
 * broadcasts EVERY symbol it streams, not just ones present in the Assets
 * table.
 *
 * Deliberately shares no code, Redis keys, or broadcast channel with
 * StreamBrokeretTicks / PriceFeedService / BridgeRedisTicks / the
 * 'asset-prices' channel — that whole existing pipeline (iqcent-sourced,
 * DB-Assets-gated) is left completely untouched and keeps driving the main
 * dashboard as-is, serving as a fallback independent of this one.
 *
 * One process handles collect + persist + broadcast (no separate bridge
 * process like BridgeRedisTicks) — broadcast(new BrokeretTicksUpdated(...))
 * fires immediately (ShouldBroadcastNow), so there's no queue-worker hop
 * between a tick arriving here and it reaching the browser.
 *
 * Run permanently (see deploy/supervisor/pocketapp-brokeret-ui-stream.conf):
 *   php artisan ticks:stream-brokeret-ui
 */
class StreamBrokeretFeed extends Command
{
    protected $signature = 'ticks:stream-brokeret-ui
        {--reconnect-delay=3 : Seconds to wait before reconnecting after a dropped connection}
        {--broadcast-chunk=200 : Max ticks per broadcast}
        {--broadcast-interval-ms=200 : Minimum time between broadcasts — Ably publishes are an HTTP round trip, so broadcasting on every single incoming WS message (several per second) throttles the whole loop; this window batches them instead}';

    protected $description = "Connects to Brokeret's WebSocket feed directly and streams+broadcasts every symbol for base_url/ui, fully independent of the main dashboard's price pipeline.";

    /** Message types that carry an array of {s,b,a,t,c} price entries. */
    private const TICK_MESSAGE_TYPES = ['snapshot', 'ticks'];

    public function handle(BrokeretFeedService $feed): int
    {
        $wsUrl = config('services.brokeret.ws_url');
        $apiKey = config('services.brokeret.api_key');
        $reconnectDelay = max(1, (int) $this->option('reconnect-delay'));
        $chunkSize = max(1, (int) $this->option('broadcast-chunk'));
        $broadcastIntervalMs = max(0, (int) $this->option('broadcast-interval-ms'));

        if (! $wsUrl) {
            $this->error('services.brokeret.ws_url is not configured.');

            return self::FAILURE;
        }

        $url = $wsUrl . (str_contains($wsUrl, '?') ? '&' : '?') . 'apikey=' . urlencode((string) $apiKey);

        $this->info('Brokeret UI feed starting...');

        while (true) {
            try {
                $this->streamOnce($url, $feed, $chunkSize, $broadcastIntervalMs);
            } catch (\Throwable $e) {
                Log::warning('[ticks:stream-brokeret-ui] connection dropped', ['error' => $e->getMessage()]);
            }

            sleep($reconnectDelay);
        }
    }

    private function streamOnce(string $url, BrokeretFeedService $feed, int $chunkSize, int $broadcastIntervalMs): void
    {
        $client = new WsClient($url, [
            // Comfortably above Brokeret's ~15s heartbeat interval.
            'timeout' => 45,
        ]);

        $this->info('Connected to Brokeret feed.');

        // Broadcasting is a synchronous Ably REST call (HTTP round trip) —
        // firing one per incoming WS message (Brokeret sends several per
        // second) throttles this loop badly enough that Redis/broadcast
        // data drifts tens of seconds behind the real feed. Instead, ticks
        // are buffered here (only the latest per symbol matters for a live
        // display, so a symbol repeated within the window just overwrites
        // its buffered entry) and flushed on a short timer — bounded added
        // latency, far fewer HTTP calls. Redis writes below stay per-tick
        // (cheap, local) so history/backfill accuracy is unaffected.
        $pending = [];
        $lastFlush = microtime(true);
        $flushEvery = $broadcastIntervalMs / 1000;

        $flush = function () use (&$pending, &$lastFlush, $chunkSize) {
            if (empty($pending)) {
                $lastFlush = microtime(true);

                return;
            }
            foreach (array_chunk(array_values($pending), $chunkSize) as $chunk) {
                try {
                    broadcast(new BrokeretTicksUpdated($chunk));
                } catch (\Throwable $e) {
                    Log::warning('[ticks:stream-brokeret-ui] broadcast chunk failed', ['error' => $e->getMessage(), 'count' => count($chunk)]);
                }
            }
            $pending = [];
            $lastFlush = microtime(true);
        };

        try {
            while (true) {
                $message = $client->receive();
                if (! is_string($message) || $message === '') {
                    if ($broadcastIntervalMs > 0 && (microtime(true) - $lastFlush) >= $flushEvery) {
                        $flush();
                    }
                    continue;
                }

                $payload = json_decode($message, true);
                if (! is_array($payload) || ! in_array($payload['type'] ?? null, self::TICK_MESSAGE_TYPES, true)) {
                    continue;
                }

                foreach ($payload['data'] ?? [] as $entry) {
                    $tick = $this->parseEntry($entry);
                    if ($tick === null) {
                        continue;
                    }

                    $feed->updateLatest($tick['symbol'], $tick['bid'], $tick['ask'], $tick['category'], $tick['t']);
                    $feed->appendHistoryTick($tick['symbol'], $tick['bid'], $tick['ask'], $tick['t']);
                    $pending[$tick['symbol']] = $tick;
                }

                if ($broadcastIntervalMs <= 0 || (microtime(true) - $lastFlush) >= $flushEvery) {
                    $flush();
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

    /** @param mixed $entry @return array{symbol:string,bid:float,ask:float,mid:float,category:string,t:int}|null */
    private function parseEntry($entry): ?array
    {
        if (! is_array($entry)
            || ! isset($entry['s'], $entry['b'], $entry['a'], $entry['t'])
            || ! is_numeric($entry['b']) || ! is_numeric($entry['a'])
        ) {
            return null;
        }

        $bid = (float) $entry['b'];
        $ask = (float) $entry['a'];

        return [
            'symbol' => (string) $entry['s'],
            'bid' => $bid,
            'ask' => $ask,
            'mid' => ($bid + $ask) / 2,
            'category' => (string) ($entry['c'] ?? 'other'),
            't' => (int) $entry['t'],
        ];
    }
}
