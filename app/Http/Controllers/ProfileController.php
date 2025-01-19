<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Trade;
use App\Models\User;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function tradingProfile()
    {
        $trades = Trade::whereUserId(auth()->id())->get();
        return $user = auth()->user();

        // Calculate trading statistics
        $totalTrades = $trades->count();
        $profitableTrades = $trades->where('trade_profit', '>', 0)->count(); // Assuming 'trade_profit' field exists
        $profitableTradesPercentage = $totalTrades > 0 ? ($profitableTrades / $totalTrades) * 100 : 0;
        $tradingTurnover = $trades->sum('amount'); // Assuming 'amount' field exists
        $tradingProfit = $trades->sum('trade_profit'); // Assuming 'trade_profit' field exists
        $maxTrade = $trades->max('amount');
        $minTrade = $trades->min('amount');
        $maxProfit = $trades->max('trade_profit');

        // Chart Data: Profitability over time
        $profitabilityData = $trades->groupBy(function ($trade) {
            return \Carbon\Carbon::parse($trade->trade_close_time)->format('Y-m-d H:00:00'); // Group by hour
        })->map(function ($group) {
            return $group->sum('trade_profit'); // Sum profits per group
        });

        // Data: Trade amounts by assets
        $tradeAmountsByAssets = $trades->groupBy('trade_currency')->map(function ($group) {
            return $group->sum('trade_amount'); // Sum amounts per currency
        });

        // Data: Trades distribution by assets
        $tradesDistributionByAssets = $trades->groupBy('trade_currency')->map(function ($group) {
            return $group->count(); // Count trades per currency
        });


        return view('profile.trading-profile', compact(
            'user',
            'totalTrades',
            'profitableTradesPercentage',
            'tradingTurnover',
            'tradingProfit',
            'maxTrade',
            'minTrade',
            'maxProfit',
            'profitabilityData',
            'tradeAmountsByAssets',
            'tradesDistributionByAssets'
        ));
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "value" => "required|string",
        ]);

        $user = auth()->user();
        $user = User::findOrFail($user->id);

        if (array_key_exists($request->name, $user->config)) {
            $user->config[$request->name] = $$request->value;
            $user->save();

            return response()->json(['status' => 200, 'message' => 'Language updated successfully']);
        }

        $column = $request->name;
        $user->$column = $request->value;
        
        if ($user->save()) {
            return response()->json(['status' => true, 'message' => 'Profile updated successfully!']);
        }
    }

    public function security()
    {
        $userId = auth()->id();
        $login_history = User::find($userId)->authentications;
        $active_sessions = $this->getActiveSessions($userId);
        return view('profile.security', compact('active_sessions'));
    }

    public function getActiveSessions($userId)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->get();

        return $sessions;
    }

    public function destroySession($sessionId)
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->delete();

        return response()->json(['status' => true, 'message' => 'Session destroyed successfully!']);
    }
}
