<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            ['key' => 'first_trade', 'title' => 'First Trade', 'description' => 'Placed your first trade', 'icon' => '🎯', 'points' => 10],
            ['key' => 'ten_wins', 'title' => '10 Wins', 'description' => 'Won 10 trades', 'icon' => '🏅', 'points' => 25],
            ['key' => 'fifty_wins', 'title' => '50 Wins', 'description' => 'Won 50 trades', 'icon' => '🥇', 'points' => 75],
            ['key' => 'win_streak_5', 'title' => 'Win Streak 5', 'description' => 'Won 5 trades in a row', 'icon' => '🔥', 'points' => 30],
            ['key' => 'high_roller', 'title' => 'High Roller', 'description' => 'Placed a trade of $500 or more', 'icon' => '💎', 'points' => 40],
            ['key' => 'hundred_trades', 'title' => 'Century', 'description' => 'Placed 100 trades', 'icon' => '💯', 'points' => 50],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(['key' => $achievement['key']], $achievement);
        }
    }
}
