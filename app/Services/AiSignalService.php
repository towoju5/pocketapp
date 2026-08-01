<?php

namespace App\Services;

use App\Models\Assets;
use App\Models\Signal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generates one trade signal per currently-online asset, from each asset's
 * recent price trend — as many as are online, not just a single "best"
 * pick. Uses DeepSeek's chat-completions API (OpenAI-compatible) to decide
 * a direction and write a short rationale per asset when DEEPSEEK_API_KEY
 * is configured; otherwise falls back to a local heuristic (direction
 * follows the sign of the recent % move) so the "Generate with AI" button
 * still works out of the box without any API key.
 */
class AiSignalService
{
    public function __construct(private PriceFeedService $priceFeed, private BrokeretFeedService $brokeretFeed)
    {
    }

    /** Each asset's live feed lives in a different Redis namespace depending on its price_source. */
    private function feedFor(Assets $asset): PriceFeedService|BrokeretFeedService
    {
        return $asset->price_source === 'brokeret' ? $this->brokeretFeed : $this->priceFeed;
    }

    /**
     * @throws \RuntimeException when there isn't enough live data to generate a signal
     * @return array<int, Signal> one signal per online asset
     */
    public function generate(?int $createdBy = null): array
    {
        $provider = get_option('active_chart_provider', 'all');
        $candidates = $this->buildCandidates($provider);

        if (empty($candidates)) {
            $assetCount = Assets::query()
                ->when($provider !== 'all', fn ($q) => $q->where('price_source', $provider))
                ->count();

            if ($assetCount === 0) {
                throw new \RuntimeException(
                    $provider === 'all'
                        ? 'No assets are configured yet — add one under Assets first.'
                        : "No assets are configured for the active chart provider ({$provider}). Add one, or change the Active Chart Provider in Settings."
                );
            }

            throw new \RuntimeException(
                $provider === 'all'
                    ? 'No assets currently have a live price feed. Make sure the price feed collector is running.'
                    : "No assets on the active chart provider ({$provider}) currently have a live price feed. Make sure that provider's price feed collector is running, or switch Active Chart Provider in Settings."
            );
        }

        $apiKey = get_option('deepseek_api_key') ?: config('services.deepseek.api_key');
        $picks = $apiKey
            ? $this->pickAllWithDeepSeek($candidates, $apiKey)
            : $this->pickAllWithHeuristic($candidates);

        $signals = [];
        foreach ($picks as $pick) {
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

            $signals[] = $signal;
        }

        return $signals;
    }

    /**
     * @return array<int, array{symbol: string, price: float, change_pct: float}>
     */
    private function buildCandidates(string $provider = 'all'): array
    {
        $candidates = [];

        $assets = Assets::query()
            ->when($provider !== 'all', fn ($q) => $q->where('price_source', $provider))
            ->get();

        foreach ($assets as $asset) {
            $feed = $this->feedFor($asset);

            if (!$feed->isOnline($asset->symbol)) {
                continue;
            }

            $ticks = $feed->getHistoryTicks($asset->symbol);
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
        // material, so rank by magnitude rather than sign. Not capped: every
        // online asset with enough history becomes its own signal.
        usort($candidates, fn ($a, $b) => abs($b['change_pct']) <=> abs($a['change_pct']));

        return $candidates;
    }

    /** @param array{symbol: string, price: float, change_pct: float} $candidate */
    private function pickHeuristic(array $candidate): array
    {
        $direction = $candidate['change_pct'] >= 0 ? 'up' : 'down';

        return [
            'asset' => $candidate['symbol'],
            'direction' => $direction,
            'duration' => 300,
            'amount' => 50,
            'expected_profit' => null,
            'start_price' => $candidate['price'],
            'notes' => sprintf(
                'Auto-generated: %s moved %.2f%% over the last observed window (no AI key configured — using trend heuristic).',
                $candidate['symbol'],
                $candidate['change_pct']
            ),
        ];
    }

    /** @param array<int, array{symbol: string, price: float, change_pct: float}> $candidates */
    private function pickAllWithHeuristic(array $candidates): array
    {
        return array_map(fn ($candidate) => $this->pickHeuristic($candidate), $candidates);
    }

    /**
     * One DeepSeek call covering every candidate, so generating signals for
     * dozens of online assets doesn't cost dozens of API calls. Any asset
     * DeepSeek's response omits or gets wrong falls back individually to
     * the heuristic rather than failing the whole batch.
     *
     * @param array<int, array{symbol: string, price: float, change_pct: float}> $candidates
     */
    private function pickAllWithDeepSeek(array $candidates, string $apiKey): array
    {
        $lines = collect($candidates)
            ->map(fn ($c) => sprintf('%s: last price %.5f, %.2f%% change over the recent window', $c['symbol'], $c['price'], $c['change_pct']))
            ->implode("\n");

        $bySymbol = collect($candidates)->keyBy('symbol');

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post(config('services.deepseek.url'), [
                    'model' => config('services.deepseek.model', 'deepseek-chat'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a binary-options trade signal generator for a trading platform. '
                                . 'Given a list of currently-tradeable assets and their recent short-term price change, '
                                . 'decide a direction (up or down) you consider most promising for EVERY asset in the '
                                . 'list — cover all of them, not just the best one. Respond with ONLY a JSON array, no '
                                . 'markdown, no commentary outside the JSON: [{"asset": "<symbol from the list, exact '
                                . 'match>", "direction": "up|down", "duration": <seconds, integer between 60 and 1800>, '
                                . '"notes": "<one short sentence explaining the pick>"}, ...] with exactly one entry '
                                . 'per asset listed.',
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

            if (!is_array($parsed)) {
                Log::warning('AiSignalService: DeepSeek response unusable, falling back to heuristic for all candidates', ['content' => $content]);

                return $this->pickAllWithHeuristic($candidates);
            }

            $picksBySymbol = [];
            foreach ($parsed as $entry) {
                if (
                    is_array($entry)
                    && isset($entry['asset'], $entry['direction'])
                    && $bySymbol->has($entry['asset'])
                    && in_array($entry['direction'], ['up', 'down'], true)
                    && !isset($picksBySymbol[$entry['asset']])
                ) {
                    $candidate = $bySymbol[$entry['asset']];
                    $picksBySymbol[$entry['asset']] = [
                        'asset' => $candidate['symbol'],
                        'direction' => $entry['direction'],
                        'duration' => max(60, min(1800, (int) ($entry['duration'] ?? 300))),
                        'amount' => 50,
                        'expected_profit' => null,
                        'start_price' => $candidate['price'],
                        'notes' => (string) ($entry['notes'] ?? 'AI-generated signal.'),
                    ];
                }
            }

            // Any candidate DeepSeek didn't return a usable entry for still gets a signal, via the heuristic.
            foreach ($candidates as $candidate) {
                if (!isset($picksBySymbol[$candidate['symbol']])) {
                    $picksBySymbol[$candidate['symbol']] = $this->pickHeuristic($candidate);
                }
            }

            return array_values($picksBySymbol);
        } catch (\Throwable $e) {
            Log::warning('AiSignalService: DeepSeek call failed, falling back to heuristic for all candidates', ['error' => $e->getMessage()]);

            return $this->pickAllWithHeuristic($candidates);
        }
    }
}
