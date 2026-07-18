<?php

namespace App\Services;

use App\Models\User;

class TraderLeaderboard
{
    /**
     * Real trader leaderboards used by both the dashboard's Social Trading
     * panel and the standalone Social Trading page. Was previously
     * duplicated verbatim across HomeController::dashboard()/demo().
     *
     * @return array{traders24hours: \Illuminate\Support\Collection, tradersTopRanked: \Illuminate\Support\Collection, tradersTop100: \Illuminate\Support\Collection}
     */
    public static function build(): array
    {
        // Demo wins are risk-free (every user is seeded with a demo balance)
        // and must never count toward a public real-money leaderboard.
        $realTradesOnly = fn ($q) => $q->where('trade_wallet', 'not like', '%demo%');

        $traders24hours = User::whereHas('trades', function ($q) use ($realTradesOnly) {
            $realTradesOnly($q);
            $q->where('created_at', '>=', now()->subHours(24))
                ->where('trade_status', 'win');
        })
            ->with(['trades' => function ($q) use ($realTradesOnly) {
                $realTradesOnly($q);
                $q->where('created_at', '>=', now()->subHours(24));
            }])
            ->get()
            ->map(function ($user) {
                $user->total_profit = $user->trades->where('trade_status', 'win')->sum('trade_profit');
                return $user;
            })
            ->sortByDesc('total_profit')
            ->values();

        $tradersTopRanked = User::whereHas('trades', function ($q) use ($realTradesOnly) {
            $realTradesOnly($q);
            $q->where('trade_status', 'win');
        })
            ->with(['trades' => $realTradesOnly])
            ->get()
            ->map(function ($user) {
                $user->total_profit = $user->trades->where('trade_status', 'win')->sum('trade_profit');
                return $user;
            })
            ->sortByDesc('total_profit')
            ->values();

        $tradersTop100 = $tradersTopRanked->take(100);

        return compact('traders24hours', 'tradersTopRanked', 'tradersTop100');
    }
}
