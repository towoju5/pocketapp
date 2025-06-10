<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

/**
 * Handles 2FA authentication using Google Authenticator.
 */
class Google2faController extends Controller
{

    /**
     * Generate a new secret key for 2FA.
     *
     * @return 
     */
    public function generateSecret()
    {
        try {
            $user = request()->user();
            if ($user->google2fa_enabled == 1 || $user->google2fa_enabled == true) {
                return response()->json(['error' => '2FA is already enabled for this user'], 400);
            }
            $google2fa = new Google2FA();
            $secret = $google2fa->generateSecretKey();
            return response()->json(['secret' => $secret]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    /**
     * Enable 2FA for a user.
     *
     * @param Request $request
     * @return
     */
    public function enable2fa(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->google2fa_secret) {
                return response()->json(['error' => '2FA is already enabled for this user'], 400);
            }

            $google2fa = new Google2FA();

            if (!$google2fa->verifyKey($request->secret, $request->otp)) {
                return response()->json(['error' => 'Invalid OTP'], 401);
            }

            $user->google2fa_enabled = true;
            $user->google2fa_secret = $request->secret;
            $user->save();

            return response()->json(['message' => '2FA enabled successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    /**
     * Verify a 2FA code.
     *
     * @param Request $request
     * @return 
     */
    public function verify2fa(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->google2fa_secret) {
                return response()->json(['error' => '2FA is not enabled for this user'], 400);
            }

            $google2fa = new Google2FA();

            if (!$google2fa->verifyKey($user->google2fa_secret, $request->otp)) {
                return response()->json(['error' => 'Invalid OTP'], 401);
            }

            return response()->json(['message' => '2FA verified successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    /**
     * Disable 2FA for a user.
     *
     * @param Request $request
     * @return 
     */
    public function disable2fa(Request $request)
    {
        try {
            $user = $request->user();
            $google2fa = new Google2FA();

            if (!$google2fa->verifyKey($user->google2fa_secret, $request->otp)) {
                return response()->json(['error' => 'Invalid OTP'], 401);
            }
            $user->google2fa_enabled = false;
            $user->google2fa_secret = null;
            $user->save();
            return response()->json(['message' => '2FA disabled successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}
