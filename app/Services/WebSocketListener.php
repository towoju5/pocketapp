<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use WebSocket\Client;

/**
 * One persistent connection to iqcent's price WebSocket, subscribed to
 * every symbol handed to subscribeAll(). Used by the prices:sync console
 * command to keep the whole asset catalog's price cache warm server-side.
 */
class WebSocketListener
{
    protected Client $client;

    public function __construct(string $url = 'wss://iqcent.com/trade-api-ws/api/ws/price')
    {
        $this->client = new Client($url, ['timeout' => 60]);
    }

    public function subscribeAll(array $symbols): void
    {
        foreach ($symbols as $symbol) {
            $this->client->send(json_encode([
                'id' => $symbol,
                'param' => 'Option',
                'operation' => 'SUBSCRIBE.TICK',
            ]));
        }
    }

    /**
     * Blocks, invoking $onTick($symbol, $price, $epochMs) for each decoded
     * tick. Returns once the socket errors out or closes — the caller is
     * responsible for reconnect/retry looping.
     */
    public function listen(callable $onTick): void
    {
        while (true) {
            try {
                $response = $this->client->receive();
            } catch (\Throwable $e) {
                Log::warning('WebSocketListener: receive failed', ['error' => $e->getMessage()]);

                return;
            }

            $data = json_decode($response, true);
            if (! is_array($data) || ! isset($data['id'], $data['p'], $data['t'])) {
                continue;
            }

            $onTick($data['id'], (float) $data['p'], (int) $data['t']);
        }
    }
}
