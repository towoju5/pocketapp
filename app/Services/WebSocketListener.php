<?php

namespace App\Services;

use WebSocket\Client;
use Illuminate\Support\Facades\Event;

class WebSocketListener
{
    protected $client;

    public function __construct()
    {
        // Connect to the WebSocket server
        $this->client = new Client("wss://ws-plus.olymptrade.com/connect");
    }

    public function subscribe()
    {
        // Send subscription message
        $message = [
            [
                "e" => 10,
                "t" => 2,
                "d" => [
                    "pairs" => ["GBPUSD_OTC"],
                    "chart_tfs" => [3600],
                    "with_forecast" => false,
                ]
            ]
        ];

        $this->client->send(json_encode($message));
        $this->listenAndBroadcast();
        echo "Subscribed to GBPUSD_OTC data.\n";
    }

    public function listenAndBroadcast()
    {
        try {
            // Subscribe on connect
            $this->subscribe();

            // Continuously listen for events from the WebSocket
            while (true) {
                $response = $this->client->receive(); // Receive WebSocket messages
                $data = json_decode($response, true);

                // Dispatch an event with the WebSocket data
                Event::dispatch('websocket.message.received', $data);

                // Log or debug (optional)
                echo "Received WebSocket message: " . $response . "\n";
            }
        } catch (\Exception $e) {
            // Handle connection errors or other exceptions
            echo "WebSocket error: " . $e->getMessage() . "\n";
        }
    }
}
