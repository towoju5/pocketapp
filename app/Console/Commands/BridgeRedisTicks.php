<?php

namespace App\Console\Commands;

use App\Events\AssetPriceBatchUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Bridges Redis Streams to the frontend: websockets-setup/ws.py (now
 * SUPERSEDED, see its own docblock) used to write ticks straight into Redis
 * (ticks:{symbol} streams, see its own docblock for the schema) with no
 * knowledge of this app's WebSocket layer; the current writer is
 * StreamBrokeretTicks (app/Console/Commands/StreamBrokeretTicks.php, `php
 * artisan ticks:stream-brokeret`), which connects to Brokeret's feed
 * directly from this app and writes into the exact same Redis schema via
 * PriceFeedService. Either way, this process only ever tails ticks:*
 * streams for newly-appended entries — it has no idea which process wrote
 * them — and rebroadcasts them as AssetPriceBatchUpdated over Reverb — the
 * exact same event TickerController::collectBatch used to emit — so
 * chart.js's ChartManager._initBroadcast and every other Echo listener need
 * no changes regardless of which writer is active.
 *
 * Runs forever as one long-lived process (see
 * deploy/supervisor/pocketapp-redis-tick-bridge.conf), not per-request:
 * XREAD blocks waiting on live data, which has no place in a normal
 * php-fpm/Octane request lifecycle.
 */
class BridgeRedisTicks extends Command
{
    protected $signature = 'ticks:bridge-redis
        {--rescan=15 : Seconds between re-scanning Redis for newly-appeared ticks:* streams}
        {--block=2000 : XREAD BLOCK timeout in ms}
        {--broadcast-chunk=100 : Max ticks per broadcast (Reverb caps message size)}';

    protected $description = 'Tails Redis ticks:{symbol} streams written by ws.py and rebroadcasts them over Reverb for the chart/trading frontend.';

    public function handle(): int
    {
        $rescanEvery = max(1, (int) $this->option('rescan'));
        $blockMs = max(100, (int) $this->option('block'));
        $chunkSize = max(1, (int) $this->option('broadcast-chunk'));

        /** @var array<string, string> $lastIds streamKey => last-consumed entry ID */
        $lastIds = [];
        $lastScan = 0;

        $this->info('Redis tick bridge starting — watching for ticks:* streams...');

        while (true) {
            if ((time() - $lastScan) >= $rescanEvery) {
                $discovered = $this->discoverStreams($lastIds);
                if ($discovered > 0) {
                    $this->info("Discovered {$discovered} new stream(s), now watching " . count($lastIds) . ' total.');
                }
                $lastScan = time();
            }

            if (empty($lastIds)) {
                sleep(1);
                continue;
            }

            try {
                $result = Redis::xread($lastIds, $chunkSize, $blockMs);
            } catch (\Throwable $e) {
                Log::warning('[ticks:bridge-redis] XREAD failed', ['error' => $e->getMessage()]);
                sleep(1);
                continue;
            }

            if (empty($result)) {
                continue;
            }

            $ticks = [];
            foreach ($result as $streamKey => $entries) {
                foreach ($entries as $id => $fields) {
                    $lastIds[$streamKey] = $id;

                    if (!isset($fields['s'], $fields['t'], $fields['p']) || $fields['p'] === '' || !is_numeric($fields['p'])) {
                        continue;
                    }

                    $ticks[] = ['symbol' => (string) $fields['s'], 'price' => (float) $fields['p'], 't' => (int) $fields['t']];
                }
            }

            foreach (array_chunk($ticks, $chunkSize) as $chunk) {
                if (empty($chunk)) {
                    continue;
                }
                try {
                    broadcast(new AssetPriceBatchUpdated($chunk));
                } catch (\Throwable $e) {
                    Log::warning('[ticks:bridge-redis] broadcast chunk failed', ['error' => $e->getMessage(), 'count' => count($chunk)]);
                }
            }
        }
    }

    /**
     * SCANs for ticks:* keys and starts tracking any not already known,
     * from '$' (only entries appended from this point forward) — so a
     * symbol that starts ticking after this process boots gets picked up
     * without needing a restart.
     */
    private function discoverStreams(array &$lastIds): int
    {
        $found = 0;
        // Must start as null, not '0'/0 — phpredis's scan() treats any
        // non-null iterator that's loosely-equal to 0 as "already finished"
        // and returns false without issuing a command, so seeding with '0'
        // (the value Laravel's own SCAN docs example uses) makes the very
        // first call silently discover nothing.
        $cursor = null;

        do {
            $page = Redis::scan($cursor, ['match' => 'ticks:*', 'count' => 500]);
            if ($page === false) {
                break;
            }
            [$cursor, $keys] = $page;
            foreach ($keys as $key) {
                if (!array_key_exists($key, $lastIds)) {
                    $lastIds[$key] = '$';
                    $found++;
                }
            }
        } while ($cursor != 0);

        return $found;
    }
}
