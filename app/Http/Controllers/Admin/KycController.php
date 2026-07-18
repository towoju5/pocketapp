<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $submissions = KycVerification::with('user')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.kyc.index', compact('submissions'));
    }

    public function show(KycVerification $kyc)
    {
        return view('admin.kyc.show', compact('kyc'));
    }

    public function approve(KycVerification $kyc)
    {
        $kyc->update([
            'status' => 'verified',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        $kyc->user->notify(new GenericNotification(
            'Identity verified',
            'Your KYC documents have been approved. Withdrawals are now unlocked.',
            route('kyc.show')
        ));

        return back()->with('success', 'KYC submission approved.');
    }

    public function reject(Request $request, KycVerification $kyc)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $kyc->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['reason'],
        ]);

        $kyc->user->notify(new GenericNotification(
            'Identity verification rejected',
            $validated['reason'],
            route('kyc.show')
        ));

        return back()->with('success', 'KYC submission rejected.');
    }
}
