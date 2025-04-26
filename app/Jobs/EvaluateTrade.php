<?php

namespace App\Jobs;

use App\Models\Tournament;
use App\Models\User;
use BinaryTrading\ExpressTrade\Models\Trade;
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

    public function handle()
    {
        try {
            $trade = $this->trade;
            $user = User::where('user_id', $trade->user_id)->first();

            $site_mode = strtolower($trade->trade_wallet) ?? 'qt_demo_usd';
            $data = fetchPreChartData($trade->trade_currency, true);
            Log::info(json_encode($data));
            $finalPrice = $data ?? 0;

            // Evaluate trade
            if ($trade->trade_direction == 'up' && $finalPrice > $trade->start_price) {
                $trade->trade_status = 'win';
                $trade->trade_profit = $trade->trade_profit;
            } elseif ($trade->trade_direction == 'down' && $finalPrice < $trade->start_price) {
                $trade->trade_status = 'win';
                $trade->trade_profit = $trade->trade_profit;
            } else {
                $trade->trade_status = 'lose';
            }


            $trade->end_price = $finalPrice;
            $trade->save();

            if ($trade->trade_status == 'win') {
                // return the user trade with profit
                credit_user($trade->trade_wallet, $trade->trade_profit, "Successfully won trade ID {$trade->id}");
            }

            event(new \App\Events\TradeUpdated($trade));
        } catch (\Throwable $th) {
            Log::info(json_encode($th));
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
