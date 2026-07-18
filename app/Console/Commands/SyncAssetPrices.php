<?php

namespace App\Console\Commands;

use App\Models\Assets;
use App\Services\PriceFeedService;
use App\Services\WebSocketListener;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAssetPrices extends Command
{
    protected $signature = 'prices:sync';

    protected $description = 'Maintain a persistent price subscription to every tradable asset and cache live prices/online status';

    public function handle(PriceFeedService $prices): int
    {
        $this->info('Starting live price sync...');

        while (true) {
            $symbols = Assets::pluck('symbol')->filter()->values()->all();

            if (empty($symbols)) {
                $this->warn('No assets found, retrying in 10s...');
                sleep(10);
                continue;
            }

            try {
                $listener = new WebSocketListener();
                $listener->subscribeAll($symbols);
                $this->info('Subscribed to ' . count($symbols) . ' assets.');

                $listener->listen(function (string $symbol, float $price, int $epochMs) use ($prices) {
                    $prices->updatePrice($symbol, $price);
                });
            } catch (\Throwable $e) {
                Log::error('SyncAssetPrices: connection error', ['error' => $e->getMessage()]);
            }

            $this->warn('Price feed connection dropped, reconnecting in 5s...');
            sleep(5);
        }
    }
}
