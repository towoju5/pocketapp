<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TradePlacementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Called only by node-services/tradesocket over HTTP (guarded by
 * EnsureInternalServiceRequest), never by the browser directly. Delegates to
 * the exact same TradePlacementService the browser-facing
 * TradeController::placeTrade uses, so the validate -> price -> debit ->
 * persist -> schedule -> broadcast sequence can't diverge between the fetch
 * path and the socket.io path.
 */
class TradeRelayController extends Controller
{
    public function place(Request $request, TradePlacementService $placementService)
    {
        $validated = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            ...TradePlacementService::validationRules(),
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $validated = $validated->validated();
        $user = User::findOrFail($validated['user_id']);
        unset($validated['user_id']);

        // debit_user()/credit_user() (app/Helpers/helper.php) read auth()->user()
        // directly — they ignore any $user passed around them. This route has no
        // session (guarded by a shared secret, not the session guard), so without
        // this, every internal-relay trade would 402 with a false "insufficient
        // balance" regardless of the wallet's real balance. setUser() only binds
        // the resolved model to this request's auth instance, it never touches
        // the session store.
        Auth::setUser($user);

        $result = $placementService->place($user, $validated);

        return response()->json($result['body'], $result['status']);
    }
}
