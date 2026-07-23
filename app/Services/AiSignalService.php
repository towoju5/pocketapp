<?php

namespace App\Services;

use App\Models\Assets;
use App\Models\Signal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generates a trade signal from currently-online assets' recent price
 * trend. Uses DeepSeek's chat-completions API (OpenAI-compatible) to pick
 * the asset/direction and write a short rationale when DEEPSEEK_API_KEY is
 * configured; otherwise falls back to a local heuristic (largest recent
 * % move wins, direction follows that move) so the "Generate Signal"
 * button still works out of the box without any API key.
 */
class AiSignalService
{
    public function __construct(private PriceFeedService $priceFeed)
    {
    }

    /** @throws \RuntimeException when there isn't enough live data to generate a signal */
    public function generate(?int $createdBy = null): Signal
    {
        $candidates = $this->buildCandidates();

        if (empty($candidates)) {
            throw new \RuntimeException('No assets currently have enough live price history to generate a signal from.');
        }

        $pick = config('services.deepseek.api_key')
            ? $this->pickWithDeepSeek($candidates)
            : $this->pickWithHeuristic($candidates);

        $signal = Signal::create([
            'asset' => $pick['asset'],
            'amount' => $pick['amount'],
            'direction' => $pick['direction'],
            'duration' => $pick['duration'],
            'expected_profit' => $pick['expected_profit'] ?? null,
            'start_price' => $pick['start_price'] ?? null,
            'notes' => $pick['notes'],
            'is_active' => true,
            'created_by' => $createdBy,
        ]);

        event(new \App\Events\SignalCreated($signal));

        return $signal;
    }

    /**
     * @return array<int, array{symbol: string, price: float, change_pct: float}>
     */
    private function buildCandidates(): array
    {
        $candidates = [];

        foreach (Assets::all() as $asset) {
            if (!$this->priceFeed->isOnline($asset->symbol)) {
                continue;
            }

            $ticks = $this->priceFeed->getHistoryTicks($asset->symbol);
            if (count($ticks) < 2) {
                continue;
            }

            $first = $ticks[0][1];
            $last = end($ticks)[1];
            if ($first <= 0) {
                continue;
            }

            $candidates[] = [
                'symbol' => $asset->symbol,
                'price' => (float) $last,
                'change_pct' => (($last - $first) / $first) * 100,
            ];
        }

        // Strongest recent moves first — both directions are useful signal
        // material, so rank by magnitude rather than sign.
        usort($candidates, fn ($a, $b) => abs($b['change_pct']) <=> abs($a['change_pct']));

        return array_slice($candidates, 0, 15);
    }

    /** @param array<int, array{symbol: string, price: float, change_pct: float}> $candidates */
    private function pickWithHeuristic(array $candidates): array
    {
        $top = $candidates[0];
        $direction = $top['change_pct'] >= 0 ? 'up' : 'down';

        return [
            'asset' => $top['symbol'],
            'direction' => $direction,
            'duration' => 300,
            'amount' => 50,
            'expected_profit' => null,
            'start_price' => $top['price'],
            'notes' => sprintf(
                'Auto-generated: %s moved %.2f%% over the last observed window (no AI key configured — using trend heuristic).',
                $top['symbol'],
                $top['change_pct']
            ),
        ];
    }

    /** @param array<int, array{symbol: string, price: float, change_pct: float}> $candidates */
    private function pickWithDeepSeek(array $candidates): array
    {
        $lines = collect($candidates)
            ->map(fn ($c) => sprintf('%s: last price %.5f, %.2f%% change over the recent window', $c['symbol'], $c['price'], $c['change_pct']))
            ->implode("\n");

        try {
            $response = Http::withToken(config('services.deepseek.api_key'))
                ->timeout(15)
                ->post(config('services.deepseek.url'), [
                    'model' => config('services.deepseek.model', 'deepseek-chat'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a binary-options trade signal generator for a trading platform. '
                                . 'Given a list of currently-tradeable assets and their recent short-term price change, '
                                . 'pick exactly one asset and a direction (up or down) you consider the most promising '
                                . 'short-term signal. Respond with ONLY a JSON object, no markdown, no commentary outside '
                                . 'the JSON: {"asset": "<symbol from the list, exact match>", "direction": "up|down", '
                                . '"duration": <seconds, integer between 60 and 1800>, "notes": "<one short sentence '
                                . 'explaining the pick>"}',
                        ],
                        [
                            'role' => 'user',
                            'content' => "Currently tradeable assets:\n{$lines}",
                        ],
                    ],
                    'temperature' => 0.4,
                ]);

            $content = $response->json('choices.0.message.content');
            $parsed = $content ? json_decode(trim($content), true) : null;

            $bySymbol = collect($candidates)->keyBy('symbol');
            if (
                is_array($parsed)
                && isset($parsed['asset'], $parsed['direction'])
                && $bySymbol->has($parsed['asset'])
                && in_array($parsed['direction'], ['up', 'down'], true)
            ) {
                $picked = $bySymbol[$parsed['asset']];

                return [
                    'asset' => $picked['symbol'],
                    'direction' => $parsed['direction'],
                    'duration' => max(60, min(1800, (int) ($parsed['duration'] ?? 300))),
                    'amount' => 50,
                    'expected_profit' => null,
                    'start_price' => $picked['price'],
                    'notes' => (string) ($parsed['notes'] ?? 'AI-generated signal.'),
                ];
            }

            Log::warning('AiSignalService: DeepSeek response unusable, falling back to heuristic', ['content' => $content]);
        } catch (\Throwable $e) {
            Log::warning('AiSignalService: DeepSeek call failed, falling back to heuristic', ['error' => $e->getMessage()]);
        }

        return $this->pickWithHeuristic($candidates);
    }
}
