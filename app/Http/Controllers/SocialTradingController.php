<?php

namespace App\Http\Controllers;

use App\Models\TraderFollow;
use App\Models\User;
use App\Services\TraderLeaderboard;
use Illuminate\Http\Request;

class SocialTradingController extends Controller
{
    public function index()
    {
        $leaderboards = TraderLeaderboard::build();

        $following = TraderFollow::where('follower_id', auth()->id())
            ->where('is_active', true)
            ->pluck('copy_stake_amount', 'trader_id');

        return view('social-trading.index', $leaderboards + ['following' => $following]);
    }

    public function follow(Request $request, User $trader)
    {
        $validated = $request->validate([
            'copy_stake_amount' => 'required|numeric|min:1',
        ]);

        if ($trader->id === auth()->id()) {
            return response()->json(['status' => false, 'message' => "You can't copy yourself."], 422);
        }

        TraderFollow::updateOrCreate(
            ['follower_id' => auth()->id(), 'trader_id' => $trader->id],
            ['copy_stake_amount' => $validated['copy_stake_amount'], 'is_active' => true]
        );

        return response()->json(['status' => true, 'message' => "You're now copying this trader's trades."]);
    }

    public function unfollow(User $trader)
    {
        TraderFollow::where('follower_id', auth()->id())
            ->where('trader_id', $trader->id)
            ->update(['is_active' => false]);

        return response()->json(['status' => true, 'message' => 'Stopped copying this trader.']);
    }
}
