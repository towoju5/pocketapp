<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MySafeController extends Controller
{
    private const SAFE_SLUG = 'qt_safe_usd';

    public function index()
    {
        $user = auth()->user();
        $enabled = is_safebox_enabled();

        if (!$user->hasWallet(self::SAFE_SLUG)) {
            $user->createWallet(['name' => 'My Safe', 'slug' => self::SAFE_SLUG]);
        }

        $safeBalance = $user->getWallet(self::SAFE_SLUG)->balance;
        $realBalance = $user->getWallet('qt_real_usd')->balance;

        $history = $user->transactions()
            ->whereHas('wallet', fn ($q) => $q->where('slug', self::SAFE_SLUG))
            ->latest()
            ->paginate(10);

        return view('finance.my-safe', compact('enabled', 'safeBalance', 'realBalance', 'history'));
    }

    public function deposit(Request $request)
    {
        if (!is_safebox_enabled()) {
            return back()->with('error', 'My Safe is currently disabled by the platform.');
        }

        $validated = $request->validate(['amount' => 'required|numeric|min:0.01']);
        $user = auth()->user();

        if (!$user->hasWallet(self::SAFE_SLUG)) {
            $user->createWallet(['name' => 'My Safe', 'slug' => self::SAFE_SLUG]);
        }

        $real = $user->getWallet('qt_real_usd');
        if ($real->balance < $validated['amount']) {
            return back()->with('error', 'Insufficient real account balance.');
        }

        $real->transfer($user->getWallet(self::SAFE_SLUG), $validated['amount'], [
            'description' => 'Moved to My Safe',
        ]);

        return back()->with('success', 'Funds moved to your Safe.');
    }

    public function withdraw(Request $request)
    {
        if (!is_safebox_enabled()) {
            return back()->with('error', 'My Safe is currently disabled by the platform.');
        }

        $validated = $request->validate(['amount' => 'required|numeric|min:0.01']);
        $user = auth()->user();
        $safe = $user->getWallet(self::SAFE_SLUG);

        if (!$safe || $safe->balance < $validated['amount']) {
            return back()->with('error', 'Insufficient Safe balance.');
        }

        $safe->transfer($user->getWallet('qt_real_usd'), $validated['amount'], [
            'description' => 'Withdrawn from My Safe',
        ]);

        return back()->with('success', 'Funds moved back to your Real account.');
    }
}
