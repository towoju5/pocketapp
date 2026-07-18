<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KycController extends Controller
{
    public function show(): View
    {
        return view('kyc.show', ['kyc' => auth()->user()->kyc]);
    }

    public function create(): View|RedirectResponse
    {
        $kyc = auth()->user()->kyc;

        if ($kyc && $kyc->status === 'pending') {
            return redirect()->route('kyc.show');
        }

        return view('kyc.create', ['kyc' => $kyc]);
    }

    public function store(Request $request): RedirectResponse
    {
        $existing = auth()->user()->kyc;
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('kyc.show');
        }

        $validated = $request->validate([
            'document_type' => ['required', 'in:passport,national_id,drivers_license'],
            'document_front' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'document_back' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'selfie' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $frontPath = $request->file('document_front')->store('kyc/' . auth()->id(), 'public');
        $backPath = $request->hasFile('document_back')
            ? $request->file('document_back')->store('kyc/' . auth()->id(), 'public')
            : null;
        $selfiePath = $request->hasFile('selfie')
            ? $request->file('selfie')->store('kyc/' . auth()->id(), 'public')
            : null;

        auth()->user()->kyc()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'document_type' => $validated['document_type'],
                'document_front_path' => $frontPath,
                'document_back_path' => $backPath,
                'selfie_path' => $selfiePath,
                'status' => 'pending',
                'rejection_reason' => null,
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('kyc.show')->with('success', 'Your identity documents have been submitted for review.');
    }
}
