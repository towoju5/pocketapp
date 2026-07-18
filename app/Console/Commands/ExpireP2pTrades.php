<?php

namespace App\Console\Commands;

use App\Models\P2pTrade;
use Illuminate\Console\Command;

class ExpireP2pTrades extends Command
{
    protected $signature = 'p2p:expire-trades';

    protected $description = 'Cancel and refund P2P trades still awaiting payment past their deadline';

    public function handle()
    {
        $expired = P2pTrade::where('status', 'pending_payment')
            ->where('payment_deadline', '<', now())
            ->get();

        foreach ($expired as $trade) {
            $trade->seller->getWallet($trade->wallet_slug)
                ->deposit($trade->amount, ['description' => "P2P trade #{$trade->id} expired, escrow refunded"]);

            $trade->offer()->increment('available_amount', $trade->amount);

            $trade->update(['status' => 'cancelled', 'cancelled_at' => now()]);
        }

        $this->info("Expired {$expired->count()} trade(s).");
    }
}
