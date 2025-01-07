<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WebSocketService;

class ListenToWebSocket extends Command
{
    protected $signature = 'websocket:listen';
    protected $description = 'Listen to WebSocket and broadcast events';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $webSocketService = new WebSocketService();
        $webSocketService->sendSubscriptionMessage(); // Send subscription message
        $webSocketService->listenAndBroadcast(); // Listen and broadcast WebSocket events
    }
}
