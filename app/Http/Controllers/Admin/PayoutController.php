<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $payouts = Payout::with('user')
            ->when($request->status, fn ($q) => $q->where('payout_status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payouts.index', compact('payouts'));
    }

    public function show(Payout $payout)
    {
        return view('admin.payouts.show', compact('payout'));
    }

    public function approve(Payout $payout)
    {
        if ($payout->payout_status !== 'pending') {
            return back()->with('error', 'This payout has already been processed.');
        }

        $payout->update(['payout_status' => 'completed']);

        $payout->user->notify(new GenericNotification(
            'Withdrawal approved',
            "Your withdrawal of " . formatPrice((float) $payout->payout_amount) . " has been processed.",
            route('payout.show', $payout)
        ));

        return back()->with('success', 'Payout approved and marked as completed.');
    }

    public function reject(Request $request, Payout $payout)
    {
        if ($payout->payout_status !== 'pending') {
            return back()->with('error', 'This payout has already been processed.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        // Funds were held from the user's real wallet at request time — return them now.
        $payout->user->getWallet('qt_real_usd')->deposit(
            (float) $payout->payout_amount,
            ['description' => 'Withdrawal rejected — funds returned']
        );

        $payout->update([
            'payout_status' => 'rejected',
            'payout_extra_info' => array_merge($payout->payout_extra_info ?? [], ['rejection_reason' => $validated['reason']]),
        ]);

        $payout->user->notify(new GenericNotification(
            'Withdrawal rejected',
            $validated['reason'],
            route('payout.show', $payout)
        ));

        return back()->with('success', 'Payout rejected and funds returned to the user.');
    }
}
