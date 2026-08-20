<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Backs node-services/tradesocket's ~5-minute per-socket heartbeat (see
 * RealtimeAuthController's docblock): confirms the exact Laravel session a
 * realtime key was issued from is still live, so a stale/long-lived key
 * can't outlive the login it came from by more than one heartbeat interval.
 * Node closes the socket if this reports invalid.
 */
class SessionVerifyController extends Controller
{
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'session_id' => 'required|string',
        ]);

        $valid = DB::table('sessions')
            ->where('id', $validated['session_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();

        return response()->json(['valid' => $valid]);
    }
}
