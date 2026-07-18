<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    /**
     * Display the consolidated profile page (Account / Verification / Security /
     * Preferences / Trading Stats / Loyalty tabs).
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $sessions = $this->getActiveSessions($user->id);
        $logins = $user->authentications;

        $secret = $user->google2fa_secret;
        if (!$secret) {
            $secret = $this->google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();
        }
        $google2fa_url = $this->google2fa->getQRCodeUrl(config('app.name'), $user->email, $secret);

        $tradingStats = $this->buildTradingStats($user);
        $loyalty = $this->buildLoyaltyTier($user);

        return view('profile.index', [
            'user' => $user,
            'sessions' => $sessions,
            'logins' => $logins,
            'google2fa_url' => $google2fa_url,
            'tab' => $request->query('tab', 'account'),
        ] + $tradingStats + ['loyalty' => $loyalty]);
    }

    private function buildTradingStats(User $user): array
    {
        $trades = Trade::whereUserId($user->id)->get();

        $totalTrades = $trades->count();
        $profitableTrades = $trades->where('trade_status', 'win')->count();
        $profitableTradesPercentage = $totalTrades > 0 ? ($profitableTrades / $totalTrades) * 100 : 0;
        $tradingTurnover = $trades->sum('trade_amount');
        $tradingProfit = $trades->sum('trade_profit');
        $maxTrade = $trades->max('trade_amount') ?? 0;
        $minTrade = $trades->min('trade_amount') ?? 0;
        $maxProfit = $trades->max('trade_profit') ?? 0;

        $profitabilityData = $trades->groupBy(function ($trade) {
            return \Carbon\Carbon::parse($trade->trade_close_time)->format('Y-m-d H:00:00');
        })->map(fn($group) => $group->sum('trade_profit'));

        $tradeAmountsByAssets = $trades->groupBy('trade_currency')->map(fn($group) => $group->sum('trade_amount'));
        $tradesDistributionByAssets = $trades->groupBy('trade_currency')->map(fn($group) => $group->count());

        return compact(
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
        );
    }

    private function buildLoyaltyTier(User $user): array
    {
        $totalDeposited = (float) $user->user_deposit()->where('deposit_status', 'completed')->sum('deposit_amount');

        $tiers = [
            ['name' => 'Bronze', 'min' => 0],
            ['name' => 'Silver', 'min' => 500],
            ['name' => 'Gold', 'min' => 2000],
            ['name' => 'Platinum', 'min' => 10000],
        ];

        $current = $tiers[0];
        $next = null;
        foreach ($tiers as $i => $tier) {
            if ($totalDeposited >= $tier['min']) {
                $current = $tier;
                $next = $tiers[$i + 1] ?? null;
            }
        }

        $progress = $next
            ? min(100, round((($totalDeposited - $current['min']) / ($next['min'] - $current['min'])) * 100))
            : 100;

        return [
            'tier' => $current['name'],
            'nextTier' => $next['name'] ?? null,
            'totalDeposited' => $totalDeposited,
            'nextTierThreshold' => $next['min'] ?? null,
            'progressToNextTier' => $progress,
        ];
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

    public function changeDefaultWallet($newDefaultWallet)
    {
        $isAllowed = collect(allowed_wallets())->contains('symbol', $newDefaultWallet);
        if (!$isAllowed) {
            return back()->with('error', 'Invalid wallet selected.');
        }

        $user = auth()->user();
        $user->active_wallet_slug = $newDefaultWallet;
        // trade_wallet is what TradeController/SignalCopyController actually debit
        // from when placing a trade — keep it in lockstep with the displayed wallet
        // so switching wallets here genuinely changes which funds are at risk.
        $user->trade_wallet = $newDefaultWallet;
        if($user->save()) {
            return back()->with('success', 'Default wallet updated successfully.');
        }

        return back()->with('error', 'Failed to update default wallet.');
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

    /**
     * Deep-links into the consolidated profile page's Trading Stats tab.
     */
    public function tradingProfile()
    {
        return redirect()->route('profile.edit', ['tab' => 'trading']);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "value" => "required|string",
        ]);

        $user = auth()->user();
        $user = User::findOrFail($user->id);

        if (array_key_exists($request->name, $user->config ?? [])) {
            $config = $user->config;
            $config[$request->name] = $request->value;
            $user->config = $config;
            $user->save();

            return response()->json(['status' => 200, 'message' => 'Preference updated successfully']);
        }

        $column = $request->name;
        $user->$column = $request->value;

        if ($user->save()) {
            return response()->json(['status' => true, 'message' => 'Profile updated successfully!']);
        }
    }

    /**
     * Deep-links into the consolidated profile page's Security tab.
     */
    public function security()
    {
        return redirect()->route('profile.edit', ['tab' => 'security']);
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
        DB::table('sessions')->where('id', $sessionId)->delete();
        return response()->json(['status' => true, 'message' => 'Session destroyed successfully!']);
    }


    public function logoutSession($sessionId)
    {
        if ($sessionId === Session::getId()) {
            return redirect()->route('profile.security')->with('error', 'You cannot logout the current session.');
        }
    
        DB::table('sessions')->where('id', $sessionId)->delete();
    
        return redirect()->route('profile.security')->with('success', 'Session logged out successfully.');
    }
    

    // Method to handle password change request via AJAX
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['status' => 'success']);
    }

    // Method to handle 2FA verification request via AJAX
    public function verify2fa(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $secret = $user->google2fa_secret;

        // Verify the OTP
        if ($this->google2fa->verifyKey($secret, $request->one_time_password)) {
            $user->google2fa_enabled = true;
            $user->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid 2FA code.']);
    }

    // Disable 2FA
    public function disable2fa(Request $request)
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->google2fa_enabled = false;
        $user->save();

        return redirect()->route('profile.edit', ['tab' => 'security'])->with('status', 'Two-factor authentication disabled successfully.');
    }

    /**
     * Deep-links into the consolidated profile page's Security tab (fixes the
     * previously-broken profile.twofa route, which had no controller method).
     */
    public function show2faForm()
    {
        return redirect()->route('profile.edit', ['tab' => 'security']);
    }
}
