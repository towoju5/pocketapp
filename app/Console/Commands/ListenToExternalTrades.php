<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalTradeWebSocketService;

class ListenToExternalTrades extends Command
{
    protected $signature = 'trades:listen';

    protected $description = 'Listen to external trade websocket and rebroadcast';

    public function handle()
    {
        $service = new ExternalTradeWebSocketService();
        $service->listen();
    }
}