<?php

namespace App\Console\Commands;

use App\Http\Controllers\TickerController;
use App\Models\Assets;
use Illuminate\Console\Command;

/**
 * Entry point for one ticker-collector process. Run one instance per batch
 * under Supervisor (see deploy/supervisor/pocketapp-ticker-collector.conf,
 * which sets --batch=%(process_num)s) — each instance opens its own headless
 * Chrome client and streams only its slice of the asset catalog, because a
 * single WS connection can't reliably carry the full catalog's subscriptions
 * (see TickerController's docblock for why).
 */
class CollectTicks extends Command
{
    protected $signature = 'ticks:collect
        {--batch=0 : Zero-based index of this process\'s slice of the asset catalog}
        {--size=10 : Symbols per batch/client}';

    protected $description = 'Streams live ticks for one batch of assets via a dedicated headless-Chrome client';

    public function handle(TickerController $ticker): int
    {
        $batchIndex = (int) $this->option('batch');
        $batchSize = max(1, (int) $this->option('size'));

        $batch = $this->resolveBatch($batchIndex, $batchSize);

        if (empty($batch)) {
            // Catalog is currently smaller than the configured process count
            // (or shrank) — nothing for this process to do. Idle and
            // periodically re-check instead of exiting, so Supervisor
            // doesn't spin it in a fast restart loop while waiting for the
            // catalog to grow back into this batch.
            $this->warn("No symbols for batch {$batchIndex} (size {$batchSize}) — idling.");

            while (empty($batch)) {
                sleep(300);
                $batch = $this->resolveBatch($batchIndex, $batchSize);
            }
        }

        $this->info("Batch {$batchIndex}: streaming " . count($batch) . ' symbol(s)');

        $ticker->collectBatch($batch, $batchIndex);

        return self::SUCCESS;
    }

    /** @return string[] */
    private function resolveBatch(int $batchIndex, int $batchSize): array
    {
        $batches = Assets::orderBy('id')->pluck('symbol')->chunk($batchSize)->values();

        return ($batches->get($batchIndex) ?? collect())->values()->all();
    }
}
