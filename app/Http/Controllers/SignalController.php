<?php

namespace App\Http\Controllers;

use App\Models\Signal;

class SignalController extends Controller
{
    public function index()
    {
        $signals = Signal::latest()->where('is_active', true)->get();

        // A signal has no persisted "closed" state (unlike a trade) — expiry
        // is purely a function of created_at + duration, computed here so
        // the Active/Expired tabs mirror the trade list's Opened/Closed split.
        [$active, $expired] = $signals->partition(
            fn ($signal) => now()->lt($signal->created_at->clone()->addSeconds($signal->duration))
        );

        return view('signals.index', ['active' => $active->values(), 'expired' => $expired->values()]);
    }
}
