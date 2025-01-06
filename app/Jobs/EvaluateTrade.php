<?php

namespace App\Jobs;

use App\Models\Tournament;
use BinaryTrading\ExpressTrade\Models\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Necmicolak\YahooFinance\FinanceAsset;

class EvaluateTrade implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $trade;

    public function __construct($trade)
    {
        $this->trade = $trade;
    }

    public function _handle()
    {
        $asset = new FinanceAsset($this->trade->trade_currency);
        if($asset->getMeta() == null){
            return response()->json(["error" => "Asset not found"]);
        }
        $finalPrice = $asset->getMeta()?->regularMarketPrice;

        

    }

    public function handle()
    {
        $data = fetchPreChartData($this->trade->trade_currency);
        $trade = $this->trade;
        $finalPrice = $data['c'] ?? 0;


        // Evaluate trade
        if ($this->trade->direction === 'up' && $finalPrice > $this->trade->start_price) {
            $this->trade->status = 'win';
        } elseif ($this->trade->direction === 'down' && $finalPrice < $this->trade->start_price) {
            $this->trade->status = 'win';
        } else {
            $this->trade->status = 'lose';
        }

        $this->trade->end_price = $finalPrice;
        $this->trade->save();

        // $tournament = DB::table('tournament_participants')
        //     ->where('user_id', $this->trade->user_id)
        //     ->where('tournament_id', $activeTournament->id ?? null)
        //     ->first();

        // if ($tournament) {
        //     $profit = $this->trade->status === 'win' ? $this->trade->amount : -$this->trade->amount;
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
