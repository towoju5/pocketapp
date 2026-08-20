<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Issues the key the browser hands to the Node real-time layer
 * (node-services/tradesocket) at socket.io handshake time. Sits behind the
 * same session `auth` middleware as every other authenticated route — no new
 * auth surface, just a new endpoint under the existing guard.
 *
 * Unlike a typical short-lived access token, this key is deliberately
 * long-lived: revocation (logout, ban, suspension) isn't handled by a tight
 * expiry window but by Node's own background heartbeat, which re-validates
 * the underlying session every few minutes and closes the socket if it's no
 * longer valid — see node-services/tradesocket/auth.js. That keeps this
 * endpoint, and the key it issues, off the trade-placement hot path entirely.
 */
class RealtimeAuthController extends Controller
{
    public function issueToken(Request $request)
    {
        $user = $request->user();
        $now = time();

        $token = JWT::encode([
            'sub' => $user->id,
            'sid' => $request->session()->getId(), // checked by the heartbeat, see Internal\SessionVerifyController
            'iat' => $now,
            'exp' => $now + (60 * 60 * 24), // backstop only; see class docblock
            'jti' => (string) Str::uuid(),
        ], config('services.realtime.jwt_secret'), 'HS256');

        return response()->json(['token' => $token]);
    }
}
