<?php

namespace App\Services;

use App\Events\TradeDataReceived;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;
use WebSocket\TimeoutException;

class ExternalTradeWebSocketService
{
    public function listen($ticker)
    {
        $client = new Client('wss://iqcent.com/trade-api-ws/api/ws/price', [
            'timeout' => 30,
        ]);

        // ✨ Subscribe immediately after connecting
        $payload = [
            "id" => $ticker,
            "param" => "Option",
            "operation" => "SUBSCRIBE.TICK"
        ];
        Log::debug('Subscription payload: ' . json_encode($payload));
        $subscriptionPayload = json_encode($payload);
        $client->send($subscriptionPayload);

        try {
            $message = $client->receive();

            if ($message) {
                $tradeData = json_decode($message, true);
                return $tradeData;
            }
        } catch (\Exception $e) {
            logger()->error('WebSocket Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
