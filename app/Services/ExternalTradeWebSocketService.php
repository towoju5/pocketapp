<?php
namespace App\Services;

use App\Events\TradeDataReceived;
use WebSocket\Client;
use WebSocket\TimeoutException;

class ExternalTradeWebSocketService
{
    public function listen()
    {
        $client = new Client('wss://iqcent.com/trade-api-ws/api/ws/price', [
            'timeout' => 30, // Increase timeout a bit
        ]);

        // ✨ Subscribe immediately after connecting
        $subscriptionPayload = json_encode([
            "id"=> "EUR/USD.X",
            "param"=> "Option",
            "operation"=> "SUBSCRIBE.TICK"
        ]);
        $client->send($subscriptionPayload);

        while (true) {
            try {
                $message = $client->receive();

                if ($message) {
                    $tradeData = json_decode($message, true);

                    broadcast(new TradeDataReceived($tradeData));
                }
            } catch (TimeoutException $e) {
                // No message received yet — not a problem
                continue;
            } catch (\Exception $e) {
                logger()->error('WebSocket Error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                break; 
            }
        }
    }
}
