<?php

namespace App\Contracts;

use App\Models\KycProvider;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;

interface KycProviderContract
{
    /**
     * Start a hosted identity-verification session for $user. Returns
     * ['redirect_url' => string, 'reference' => string] — the caller
     * stores `reference` on the KycVerification row (as
     * provider_reference) and redirects the user to `redirect_url`.
     * Must never itself mark the verification decided; only
     * handleWebhook() does that, once the provider has an actual result.
     */
    public function startVerification(KycProvider $provider, User $user, KycVerification $kyc): array;

    /**
     * Verify and process an incoming webhook for this provider: check the
     * signature, resolve the KycVerification by its stored
     * provider_reference, and set status to 'verified' or 'rejected'
     * exactly once (idempotent — safe to receive the same event more than
     * once). Throws KycVerificationException on verification failure; the
     * caller decides the HTTP response.
     */
    public function handleWebhook(KycProvider $provider, Request $request): void;
}
