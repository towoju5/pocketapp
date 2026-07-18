<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->evaluateUnlocks($user);

        $achievements = Achievement::all()->map(function ($achievement) use ($user) {
            $unlocked = $user->achievements->firstWhere('id', $achievement->id);
            $achievement->unlocked = (bool) $unlocked;
            $achievement->unlocked_at = $unlocked?->pivot->unlocked_at;
            return $achievement;
        });

        $leaderboard = User::with('achievements')
            ->get()
            ->filter(fn($u) => $u->achievements->isNotEmpty())
            ->map(fn($u) => (object) [
                'user' => $u,
                'points' => $u->achievements->sum('points'),
            ])
            ->sortByDesc('points')
            ->values()
            ->take(20);

        return view('achievements.index', compact('achievements', 'leaderboard'));
    }

    /**
     * Real, minimal rule engine: check the user's real trade history against
     * each catalog entry and persist any newly-met achievement.
     */
    private function evaluateUnlocks(User $user): void
    {
        $trades = Trade::whereUserId($user->id)->orderBy('created_at')->get();
        $unlockedKeys = $user->achievements()->pluck('key')->all();

        $totalTrades = $trades->count();
        $wins = $trades->where('trade_status', 'win')->count();
        $maxAmount = (float) $trades->max('trade_amount');

        $bestStreak = 0;
        $currentStreak = 0;
        foreach ($trades as $trade) {
            if ($trade->trade_status === 'win') {
                $currentStreak++;
                $bestStreak = max($bestStreak, $currentStreak);
            } elseif ($trade->trade_status === 'lose') {
                $currentStreak = 0;
            }
        }

        $met = [
            'first_trade' => $totalTrades >= 1,
            'ten_wins' => $wins >= 10,
            'fifty_wins' => $wins >= 50,
            'win_streak_5' => $bestStreak >= 5,
            'high_roller' => $maxAmount >= 500,
            'hundred_trades' => $totalTrades >= 100,
        ];

        foreach ($met as $key => $isMet) {
            if (!$isMet || in_array($key, $unlockedKeys)) {
                continue;
            }

            $achievement = Achievement::where('key', $key)->first();
            if ($achievement) {
                $user->achievements()->syncWithoutDetaching([
                    $achievement->id => ['unlocked_at' => now()],
                ]);
            }
        }

        $user->load('achievements');
    }
}
