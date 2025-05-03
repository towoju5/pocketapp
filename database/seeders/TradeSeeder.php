<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1; // Adjust this to match an actual user in your DB
        $wallet = 'qt_demo_usd';
        $now = Carbon::now();

        $assets = ['BTC/USD', 'ETH/USD', 'EUR/USD', 'XAU/USD', 'AAPL/USD'];
        $trades = [];

        for ($i = 0; $i < 15; $i++) {
            $symbol = $assets[array_rand($assets)];
            $direction = ['up', 'down'][rand(0, 1)];
            $amount = rand(50, 500);
            $profitPercentage = rand(60, 90);
            $profitAmount = $amount + (($profitPercentage / 100) * $amount);
            $durationSeconds = rand(60, 900); // 1–15 minutes
            $price = rand(100, 50000); // Mocked price
            $durationFormatted = gmdate('H:i:s', $durationSeconds);

            $trades[] = [
                'trade_currency' => $symbol,
                'trade_direction' => $direction,
                'trade_amount' => $amount,
                'trade_close_time' => $now->copy()->addSeconds($durationSeconds),
                'trade_extra_info' => json_encode([
                    'currentPrice' => $price,
                    'duration' => $durationFormatted,
                    'asset' => $symbol,
                    'direction' => $direction,
                    'amount' => $amount
                ]),
                'start_price' => $price,
                'trade_status' => 'pending',
                'trade_copied_count' => 0,
                'user_id' => $userId,
                'trade_wallet' => $wallet,
                'trade_profit' => $profitAmount,
                'trade_percentage' => $profitPercentage,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('trades')->insert($trades);
    }
}
