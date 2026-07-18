<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use WebSocket\Client;

/**
 * One persistent connection to iqcent's price WebSocket, subscribed to
 * every symbol handed to subscribeAll(). Used by the prices:sync console
 * command to keep the whole asset catalog's price cache warm server-side —
 * this is what lets trade entry/settlement prices come from the server
 * instead of the client, closing off frontend price manipulation.
 */
class WebSocketListener
{
    protected Client $client;

    public function __construct(string $url = 'wss://iqcent.com/trade-api-ws/api/ws/price')
    {
        // A real browser's WS handshake carries a browser User-Agent and an
        // Origin of the page it's running on (this app's own origin, not
        // iqcent's) — the PHP client's defaults ('websocket-client-php', no
        // Origin) look nothing like that and can get silently short-changed
        // by anti-bot heuristics even when the handshake itself succeeds.
        $this->client = new Client($url, [
            'timeout' => 60,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Origin' => rtrim((string) config('app.url'), '/'),
            ],
        ]);
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
        $messagesSeen = 0;
        $ticksMatched = 0;

        while (true) {
            try {
                $response = $this->client->receive();
            } catch (\Throwable $e) {
                Log::warning('WebSocketListener: receive failed', [
                    'error' => $e->getMessage(),
                    'messages_seen' => $messagesSeen,
                    'ticks_matched' => $ticksMatched,
                ]);

                return;
            }

            $messagesSeen++;
            $data = json_decode($response, true);
            if (! is_array($data) || ! isset($data['id'], $data['p'], $data['t'])) {
                if ($messagesSeen <= 3) {
                    // First few non-tick messages are usually subscribe acks —
                    // logging them helps confirm the connection is actually
                    // exchanging data, not just idling silently.
                    Log::debug('WebSocketListener: non-tick message', ['raw' => substr((string) $response, 0, 500)]);
                }
                continue;
            }

            $ticksMatched++;
            if ($ticksMatched <= 5 || $ticksMatched % 500 === 0) {
                Log::info('WebSocketListener: tick received', [
                    'id' => $data['id'], 'price' => $data['p'], 'ticks_matched' => $ticksMatched,
                ]);
            }

            $onTick($data['id'], (float) $data['p'], (int) $data['t']);
        }
    }
}
