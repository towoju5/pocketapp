<?php

namespace App\Http\Controllers;

use App\Services\TraderLeaderboard;

class SocialTradingController extends Controller
{
    public function index()
    {
        $leaderboards = TraderLeaderboard::build();

        return view('social-trading.index', $leaderboards);
    }
}
