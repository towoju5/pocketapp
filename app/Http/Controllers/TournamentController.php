<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentSubscribers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TournamentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tournaments = Tournament::orderBy('tournament_start_date_time')->get();
        $active = $tournaments->filter(fn($t) => Carbon::parse($t->tournament_start_date_time)->isFuture())->values();
        $ended = $tournaments->filter(fn($t) => Carbon::parse($t->tournament_start_date_time)->isPast())->values();

        $joinedTournamentIds = TournamentSubscribers::where('user_id', $user->id)->pluck('tournament_id')->all();

        $myResults = TournamentSubscribers::where('user_id', $user->id)
            ->with('tournament')
            ->latest()
            ->get();

        return view('tournament.index', compact('active', 'ended', 'joinedTournamentIds', 'myResults'));
    }

    public function join(Request $request, Tournament $tournament)
    {
        $user = auth()->user();

        $alreadyJoined = TournamentSubscribers::where('user_id', $user->id)
            ->where('tournament_id', $tournament->id)
            ->exists();

        if ($alreadyJoined) {
            return back()->with('error', 'You have already joined this tournament.');
        }

        $fee = (float) $tournament->tournament_participation_fee;

        if ($fee > 0) {
            create_user_wallet($user->id);
            if (!debit_user($user->trade_wallet ?? 'qt_demo_usd', $fee, "Tournament entry: {$tournament->tournament_title}")) {
                return back()->with('error', 'Insufficient wallet balance to join this tournament.');
            }
        }

        TournamentSubscribers::create([
            'user_id' => $user->id,
            'tournament_id' => $tournament->id,
            'tournament_subscription_fee' => $fee,
            'tournament_subscription_date_time' => now(),
            'tournament_subscription_status' => 'active',
            'tournament_wining_status' => 'pending',
        ]);

        return back()->with('success', 'You have joined ' . $tournament->tournament_title . '!');
    }
}
