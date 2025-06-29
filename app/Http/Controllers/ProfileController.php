<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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

    public function changeDefaultWallet($newDefaultWallet)
    {
        $user                     = auth()->user();
        $user->active_wallet_slug = $newDefaultWallet;
        if ($user->save()) {
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

    public function tradingProfile()
    {
        $trades = Trade::whereUserId(auth()->id())->get();
        $user   = auth()->user();

        // Calculate trading statistics
        $totalTrades                = $trades->count();
        $profitableTrades           = $trades->where('trade_profit', '>', 0)->count(); // Assuming 'trade_profit' field exists
        $profitableTradesPercentage = $totalTrades > 0 ? ($profitableTrades / $totalTrades) * 100 : 0;
        $tradingTurnover            = $trades->sum('amount');       // Assuming 'amount' field exists
        $tradingProfit              = $trades->sum('trade_profit'); // Assuming 'trade_profit' field exists
        $maxTrade                   = $trades->max('amount');
        $minTrade                   = $trades->min('amount');
        $maxProfit                  = $trades->max('trade_profit');

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

        // return [
        //     'user' => $user,
        //     'totalTrades' => $totalTrades,
        //     'profitableTradesPercentage' => $profitableTradesPercentage,
        //     'tradingTurnover' => $tradingTurnover,
        //     'tradingProfit' => $tradingProfit,
        //     'maxTrade' => $maxTrade,
        //     'minTrade' => $minTrade,
        //     'maxProfit' => $maxProfit,
        //     'profitabilityData' => $profitabilityData,
        //     'tradeAmountsByAssets' => $tradeAmountsByAssets,
        //     'tradesDistributionByAssets' => $tradesDistributionByAssets
        // ];

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
            "name"  => "required|string",
            "value" => "required|string",
        ]);

        $user = auth()->user();

        // Safely get config as array
        $config = is_array($user->config) ? $user->config : json_decode($user->config, true);
        $key    = $request->name;
        $value  = $request->value;

        if (array_key_exists($key, $config)) {
            $config[$key] = $value;
            $user->config = $config;

            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Setting updated successfully!',
                    'updated' => ['key' => $key, 'value' => $value]
                ]);
            }
        } else {
            // If it's not part of config, update a normal column (e.g., name, email, etc.)
            if (in_array($key, ['name', 'email', 'phone', 'username'])) {
                $user->$key = $value;
                if ($user->save()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profile updated successfully!',
                        'updated' => ['key' => $key, 'value' => $value]
                    ]);
                }
            }
        }

        // Fallback error response
        return response()->json([
            'success' => false,
            'message' => 'Update failed or invalid field.',
        ], 422);
    }


    public function security()
    {
        $userId   = auth()->id();
        $logins   = User::find($userId)->authentications;
        $sessions = $this->getActiveSessions($userId);

        $user   = Auth::user();
        $secret = $user->google2fa_secret;

        if (! $secret) {
            // If the user hasn't set up 2FA, generate a new secret and QR code
            $secret                 = $this->google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();
        }

        // Generate the QR code URL for Google Authenticator
        $google2fa_url = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('profile.security', compact('sessions', 'logins', 'google2fa_url', 'secret'));
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
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (! Hash::check($request->current_password, $user->password)) {
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
            'one_time_password' => 'required|string|size:6',
        ]);

        $user   = Auth::user();
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
        $user                    = Auth::user();
        $user->google2fa_secret  = null;
        $user->google2fa_enabled = false;
        $user->save();

        return redirect()->route('profile')->with('status', 'Two-factor authentication disabled successfully.');
    }

    public function updateUserConfig(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name"  => "required|string",
            "value" => "required",
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $allowedKeys = [
            'email_notifications',
            'manager_updates',
            'company_news',
            'company_promotions',
            'trading_analytics',
            'trading_statements',
            'education_emails',
            'sound_notifications',
            'default_language',
        ];

        $key = $request->name;

        if (! in_array($key, $allowedKeys)) {
            return response()->json([
                "status"  => "error",
                "success" => false,
                "message" => "Invalid configuration key.",
            ], 400);
        }

        $user         = $request->user();
        $config       = is_array($user->config) ? $user->config : json_decode($user->config, true);
        $config[$key] = $request->value;
        $user->config = $config;

        if ($user->save()) {
            return response()->json([
                "status"  => "success",
                "success" => true,
                "message" => "User config updated successfully",
                "user"    => $user,
            ]);
        }

        return response()->json([
            "status"  => "error",
            "success" => false,
            "message" => "Failed to update user config",
        ], 500);
    }
}
