<?php

namespace App\Jobs;

use App\Models\Tournament;
use App\Models\User;
use App\Services\PriceFeedService;
use App\Services\TradeSettlementService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Necmicolak\YahooFinance\FinanceAsset;

class EvaluateTrade implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $trade;

    public function __construct($trade)
    {
        $this->trade = $trade;
    }

    public function handle(PriceFeedService $priceFeed, TradeSettlementService $settlement)
    {
        try {
            Log::debug("Evaluating trade: " . $this->trade->id);
            $trade = $this->trade->fresh();

            // Already settled — an admin's force-win/force-lose/void action
            // (see Admin\TradeController) can close a trade before its
            // natural expiry, and this same delayed job still fires at the
            // original close time regardless. Without this, that later
            // firing would re-evaluate an already-closed trade against a
            // fresh price and re-run wallet crediting on top of the admin's
            // decision.
            if (!$trade || $trade->trade_status !== 'pending') {
                return;
            }

            if ($trade->admin_forced_outcome) {
                $settlement->settle($trade, $trade->admin_forced_outcome);
                return;
            }

            // Server-cached price (kept warm by the ticker collector — see
            // TickerController::collectBatch) is authoritative — falls back
            // to the ad-hoc REST scrape only if
            // the cache has nothing for this symbol.
            $currentPrice = $priceFeed->getPrice($trade->trade_currency) ?? getAssetData($trade->trade_currency, true);
            if (is_array($currentPrice)) {
                Log::info("checking rate as an array: ". json_encode($currentPrice));
            }
            $finalPrice = is_numeric($currentPrice) ? (float) $currentPrice : 0;

            // Evaluate trade
            if ($trade->trade_direction == 'up' && $finalPrice > 0 && $finalPrice > $trade->start_price) {
                $outcome = 'win';
            } elseif ($trade->trade_direction == 'down' && $finalPrice > 0 && $finalPrice < $trade->start_price) {
                $outcome = 'win';
            } else {
                $outcome = 'lose';
            }

            $settlement->settle($trade, $outcome, $finalPrice);
        } catch (\Throwable $th) {
            Log::info(json_encode($th->getTraceAsString()));
        }

        // $tournament = DB::table('tournament_participants')
        //     ->where('user_id', $trade->user_id)
        //     ->where('tournament_id', $activeTournament->id ?? null)
        //     ->first();

        // if ($tournament) {
        //     $profit = $trade->trade_status === 'win' ? $trade->amount : -$trade->amount;
        //     DB::table('tournament_participants')->where('id', $tournament->id)->increment('total_profit', $profit);
        // }

        // $tournament = Tournament::find($id);
        // $participants = $tournament->participants()->orderByDesc('total_profit')->take(3)->get();

        // $split = json_decode($tournament->reward_split);
        // $rewardPool = $participants->count() * $tournament->entry_fee;

        // foreach ($participants as $index => $participant) {
        //     $reward = ($rewardPool * $split[$index]) / 100;
        //     $participant->user->wallet_balance += $reward;
        //     $participant->user->save();
        // }
    }
}
