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
     * Uses withCount()/withSum() (correlated subqueries) instead of eager-
     * loading each qualifying user's full `trades` relation into PHP just to
     * count/sum it — tradersTopRanked in particular has no time bound, so on
     * a site with real trading history that used to mean pulling every
     * real-money trade ever placed by every winning user into memory, on
     * every single dashboard page load, just to compute a count and a sum.
     *
     * @return array{traders24hours: \Illuminate\Support\Collection, tradersTopRanked: \Illuminate\Support\Collection, tradersTop100: \Illuminate\Support\Collection}
     */
    public static function build(): array
    {
        // Demo wins are risk-free (every user is seeded with a demo balance)
        // and must never count toward a public real-money leaderboard.
        $realTradesOnly = fn ($q) => $q->where('trade_wallet', 'not like', '%demo%');

        $last24h = fn ($q) => $q->where('created_at', '>=', now()->subHours(24));
        $wins = fn ($q) => $q->where('trade_status', 'win');

        $traders24hours = User::query()
            ->withCount(['trades as trades_count' => fn ($q) => $realTradesOnly($last24h($q))])
            ->withCount(['trades as win_trades_count' => fn ($q) => $realTradesOnly($last24h($wins($q)))])
            ->withSum(['trades as total_profit' => fn ($q) => $realTradesOnly($last24h($wins($q)))], 'trade_profit')
            ->whereHas('trades', fn ($q) => $realTradesOnly($last24h($wins($q))))
            ->orderByDesc('total_profit')
            ->get();

        $tradersTopRanked = User::query()
            ->withCount(['trades as trades_count' => $realTradesOnly])
            ->withCount(['trades as win_trades_count' => fn ($q) => $realTradesOnly($wins($q))])
            ->withSum(['trades as total_profit' => fn ($q) => $realTradesOnly($wins($q))], 'trade_profit')
            ->whereHas('trades', fn ($q) => $realTradesOnly($wins($q)))
            ->orderByDesc('total_profit')
            ->get();

        $tradersTop100 = $tradersTopRanked->take(100);

        return compact('traders24hours', 'tradersTopRanked', 'tradersTop100');
    }
}
