<?php

namespace App\Http\Controllers;

use App\Models\KycCustomField;
use App\Models\KycProvider;
use App\Services\Kyc\KycProviderResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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

        $provider = $this->activeProvider();

        if ($provider->slug !== 'manual') {
            return view('kyc.create-hosted', ['kyc' => $kyc, 'provider' => $provider]);
        }

        $customFields = KycCustomField::where('is_active', true)->orderBy('sort_order')->get();

        return view('kyc.create', ['kyc' => $kyc, 'customFields' => $customFields]);
    }

    public function store(Request $request): RedirectResponse
    {
        $existing = auth()->user()->kyc;
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('kyc.show');
        }

        $customFields = KycCustomField::where('is_active', true)->get();

        $rules = [
            'document_type' => ['required', 'in:passport,national_id,drivers_license'],
            'document_front' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'document_back' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'selfie' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];

        foreach ($customFields as $field) {
            $required = $field->is_required ? 'required' : 'nullable';
            $key = "custom_fields.{$field->field_key}";

            $rules[$key] = match ($field->field_type) {
                'file' => [$required, 'file', 'max:5120'],
                'date' => [$required, 'date'],
                'number' => [$required, 'numeric'],
                'checkbox' => ['nullable', 'boolean'],
                'select' => [$required, 'string', Rule::in($field->optionList())],
                default => [$required, 'string', 'max:2000'],
            };
        }

        $validated = $request->validate($rules);

        $frontPath = $request->file('document_front')->store('kyc/'.auth()->id(), 'public');
        $backPath = $request->hasFile('document_back')
            ? $request->file('document_back')->store('kyc/'.auth()->id(), 'public')
            : null;
        $selfiePath = $request->hasFile('selfie')
            ? $request->file('selfie')->store('kyc/'.auth()->id(), 'public')
            : null;

        $customValues = [];
        foreach ($customFields as $field) {
            $inputKey = "custom_fields.{$field->field_key}";

            if ($field->field_type === 'file') {
                if ($request->hasFile($inputKey)) {
                    $customValues[$field->field_key] = $request->file($inputKey)->store('kyc/'.auth()->id(), 'public');
                }
            } elseif ($field->field_type === 'checkbox') {
                $customValues[$field->field_key] = $request->boolean($inputKey);
            } elseif ($request->filled($inputKey)) {
                $customValues[$field->field_key] = $request->input($inputKey);
            }
        }

        auth()->user()->kyc()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'provider' => 'manual',
                'provider_reference' => null,
                'document_type' => $validated['document_type'],
                'document_front_path' => $frontPath,
                'document_back_path' => $backPath,
                'selfie_path' => $selfiePath,
                'custom_field_values' => $customValues,
                'status' => 'pending',
                'rejection_reason' => null,
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('kyc.show')->with('success', 'Your identity documents have been submitted for review.');
    }

    /**
     * POST target of the "Start Verification" button on kyc.create-hosted —
     * opens a session with whichever hosted provider (Didit/Sumsub/Persona)
     * is currently active and redirects the user to it. Nothing here
     * decides verified/rejected; only KycWebhookController does, once the
     * provider has an actual result.
     */
    public function start(): RedirectResponse
    {
        $provider = $this->activeProvider();
        if ($provider->slug === 'manual') {
            return redirect()->route('kyc.create');
        }

        $existing = auth()->user()->kyc;
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('kyc.show');
        }

        $user = auth()->user();

        // document_type has no meaning for a hosted flow (the provider
        // handles document capture itself) — the column is still NOT NULL,
        // so the provider's own slug goes in as a self-documenting
        // placeholder rather than making the column nullable just for this.
        $kyc = $user->kyc()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'provider' => $provider->slug,
                'provider_reference' => null,
                'document_type' => $provider->slug,
                'document_front_path' => null,
                'document_back_path' => null,
                'selfie_path' => null,
                'custom_field_values' => null,
                'status' => 'pending',
                'rejection_reason' => null,
                'submitted_at' => now(),
            ]
        );

        try {
            $result = KycProviderResolver::resolve($provider)->startVerification($provider, $user, $kyc);
        } catch (\Throwable $e) {
            Log::error('KYC start failed', ['provider' => $provider->slug, 'error' => $e->getMessage()]);

            return redirect()->route('kyc.create')->with('error', 'Unable to start verification right now — please try again shortly.');
        }

        $kyc->update(['provider_reference' => $result['reference']]);

        return redirect()->away($result['redirect_url']);
    }

    /**
     * Landing page a hosted provider redirects back to once the user
     * finishes their flow. The real decision arrives later via webhook
     * (these providers review asynchronously) — this page just tells the
     * user that, it never itself changes $kyc->status.
     */
    public function returnFromHosted(): View
    {
        return view('kyc.return', ['kyc' => auth()->user()->kyc]);
    }

    /** Falls back to a synthetic 'manual' provider if none is marked active, so KYC never hard-breaks. */
    private function activeProvider(): KycProvider
    {
        return KycProvider::where('is_active', true)->first()
            ?? new KycProvider(['slug' => 'manual', 'display_name' => 'Manual review']);
    }
}
