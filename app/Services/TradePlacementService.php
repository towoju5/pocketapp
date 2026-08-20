<?php

namespace App\Services;

use App\Events\NewTradeCreated;
use App\Events\TradeUpdated;
use App\Jobs\EvaluateTrade;
use App\Models\Assets;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TradePlacementService
{
    public function __construct(
        private PriceFeedService $priceFeed,
        private BrokeretFeedService $brokeretFeed,
        private DataFeedClService $dataFeedCl,
    ) {}

    public static function validationRules(): array
    {
        return [
            'asset' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'direction' => 'required|in:up,down',
            'duration' => 'required|string', // assuming HH:MM:SS
        ];
    }

    /**
     * Validate → price cascade → debit → persist → schedule settlement → broadcast.
     * Both the browser-facing route and the internal Node relay call this so the
     * money-moving sequence can never diverge between entry points.
     *
     * @return array{status: int, body: array}
     */
    public function place(User $user, array $validated): array
    {
        $symbol = str_replace('--', '/', $validated['asset']);
        $validated['asset'] = $symbol;

        $asset = Assets::where('symbol', $symbol)->first();
        if (!$asset || !$asset->is_active) {
            // Same 404 whether the row genuinely doesn't exist or an admin
            // has deactivated it (e.g. to pick the OTHER source's row for
            // this instrument — see the is_active migration) — a
            // deactivated asset should look exactly like "not found" to a
            // trader, not surface which source got turned off.
            return ['status' => 404, 'body' => ['errors' => 'Asset not found']];
        }

        // BrokeretFeedService/DataFeedClService are checked only when the
        // primary (iqcent-based) pipeline has nothing for this symbol —
        // existing assets' pricing is completely unaffected. This is what
        // lets base_url/ui's live-feed assets (source-tagged
        // price_source='brokeret' — see
        // BrokeretFeedService::ensureAssetRegistered, which registers each
        // one into this table the first time it's seen streaming, well
        // before anyone could select and trade it) and datafeedcl's symbols
        // (price_source='datafeedcl' — see
        // DataFeedClService::ensureAssetRegistered, same idea) actually be
        // tradable, without touching PriceFeedService/the main pipeline at all.
        $onlineViaPriceFeed = $this->priceFeed->isOnline($symbol);
        $onlineViaBrokeret = !$onlineViaPriceFeed && $this->brokeretFeed->isOnline($symbol);

        // DataFeedClService has no cache to check separately from its price —
        // every call is a live HTTP round trip to datafeedcl.xyz, so this is
        // fetched once and reused for both the online check and entry price
        // below, rather than calling isOnline() then getPrice() separately.
        $dataFeedClTick = (!$onlineViaPriceFeed && !$onlineViaBrokeret) ? $this->dataFeedCl->fetchLatestTick($symbol) : null;
        $onlineViaDataFeedCl = $dataFeedClTick !== null;

        if (!$onlineViaPriceFeed && !$onlineViaBrokeret && !$onlineViaDataFeedCl) {
            return ['status' => 422, 'body' => ['status' => false, 'message' => 'This asset is currently unavailable for trading.']];
        }

        if ($onlineViaPriceFeed) {
            $currentPrice = $this->priceFeed->getPrice($symbol);
        } elseif ($onlineViaBrokeret) {
            $latest = $this->brokeretFeed->getLatest($symbol);
            $currentPrice = ($latest && isset($latest['b'], $latest['a']))
                ? (((float) $latest['b'] + (float) $latest['a']) / 2)
                : null;
        } else {
            $currentPrice = $dataFeedClTick['price'];
        }

        if (null === $currentPrice) {
            return ['status' => 422, 'body' => ['status' => false, 'message' => 'Unable to fetch the current price for this asset. Please try again.']];
        }

        $timeParts = explode(':', $validated['duration']);
        $validated['duration'] = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];

        create_user_wallet($user->id);

        $walletSlug = $user->trade_wallet ?? 'qt_demo_usd';

        if (!debit_user($walletSlug, $validated['amount'], "Binary Trade Order")) {
            return ['status' => 402, 'body' => ['errors' => 'Insufficient wallet balance']];
        }

        // asset_profit_margin is stored as a fraction (e.g. 0.92 == 92%), not a
        // 0-100 percentage — dividing by 100 here would shrink every payout
        // to roughly 1% of what it should be.
        $percentage_profit = $asset->asset_profit_margin;
        $profit_amount = $percentage_profit * $validated['amount'];
        $calculated_profit = $validated['amount'] + $profit_amount;

        try {
            $trade = Trade::create([
                "trade_currency" => $symbol,
                "trade_direction" => $validated['direction'],
                "trade_amount" => $validated['amount'],
                "trade_close_time" => now()->addSeconds($validated['duration']),
                "trade_extra_info" => array_merge($validated, ['currentPrice' => $currentPrice]),
                "start_price" => $currentPrice,
                "trade_status" => "pending",
                "trade_copied_count" => 0,
                'user_id' => $user->id,
                'trade_wallet' => $walletSlug,
                'trade_profit' => $calculated_profit,
                'trade_percentage' => $percentage_profit,
            ]);
        } catch (\Exception $e) {
            Log::error("Trade creation failed", ['error' => $e->getMessage()]);
            credit_user($walletSlug, $validated['amount'], "Refund: trade creation failed");
            return ['status' => 200, 'body' => ['status' => false, 'message' => 'Trade creation failed']];
        }

        if (!$trade || !$trade->id) {
            return ['status' => 200, 'body' => ['status' => false, 'message' => 'Error placing trade']];
        }

        // Settlement must be scheduled unconditionally before the broadcasts
        // below — NewTradeCreated is ShouldBroadcastNow (fires synchronously,
        // right here, not queued), so a transient broadcaster failure (Reverb
        // restart, Ably hiccup) throwing would otherwise abort this method
        // before EvaluateTrade ever gets dispatched, permanently stranding a
        // trade that already debited the user's wallet: pending forever, no
        // job ever scheduled to settle it.
        EvaluateTrade::dispatch($trade)->delay(now()->addSeconds($validated['duration']));

        try {
            event(new NewTradeCreated($trade));
            event(new TradeUpdated($trade));
        } catch (\Throwable $e) {
            Log::error('Trade broadcast failed (settlement still scheduled)', ['trade_id' => $trade->id, 'error' => $e->getMessage()]);
        }

        try {
            (new TradeCopyService())->mirror($trade);
        } catch (\Throwable $e) {
            Log::error('Copy-trade mirroring failed', ['trade_id' => $trade->id, 'error' => $e->getMessage()]);
        }

        return [
            'status' => 200,
            'body' => [
                'status' => true,
                'message' => 'Trade placed successfully!',
                'trade' => $trade,
                'html' => view("mini-pages.trade-list", compact('trade'))->render(),
            ],
        ];
    }
}
