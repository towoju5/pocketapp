<?php

namespace App\Services\Kyc;

use App\Contracts\KycProviderContract;
use App\Exceptions\KycVerificationException;
use App\Models\KycProvider;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Didit — hosted verification-session flow. vendor_data carries
 * "kyc-{$kyc->id}" through to the webhook so handleWebhook() can match the
 * result back to our row without needing Didit's own session_id for that
 * (session_id is still stored as provider_reference, since it's what Didit
 * actually keys its own webhook payload on).
 *
 * Endpoint paths/payload shapes reflect Didit's session-creation + webhook
 * docs at time of writing — verify against current Didit API docs before
 * going live, same caveat as SumsubProvider/PersonaProvider.
 */
class DiditProvider implements KycProviderContract
{
    use SettlesKycVerificationOnce;

    public function startVerification(KycProvider $provider, User $user, KycVerification $kyc): array
    {
        $apiKey = $provider->credential('api_key');
        $workflowId = $provider->config['workflow_id'] ?? null;

        if (! $apiKey || ! $workflowId) {
            throw new KycVerificationException('Didit api_key/workflow_id is not configured.');
        }

        $vendorData = "kyc-{$kyc->id}";

        $response = Http::withHeaders(['x-api-key' => $apiKey])
            ->post($this->baseUrl($provider).'/v1/session/', [
                'workflow_id' => $workflowId,
                'vendor_data' => $vendorData,
                'callback' => route('kyc.return'),
            ]);

        if (! $response->successful() || ! $response->json('url') || ! $response->json('session_id')) {
            throw new KycVerificationException('Didit session creation failed: '.$response->body());
        }

        return ['redirect_url' => $response->json('url'), 'reference' => $response->json('session_id')];
    }

    public function handleWebhook(KycProvider $provider, Request $request): void
    {
        $secret = $provider->credential('webhook_secret');
        if (! $secret) {
            throw new KycVerificationException('Didit webhook_secret is not configured.');
        }

        $signature = $request->header('x-signature');
        $expected = hash_hmac('sha256', $request->getContent(), $secret);

        if (! $signature || ! hash_equals($expected, $signature)) {
            throw new KycVerificationException('Didit webhook signature mismatch.');
        }

        $payload = $request->json()->all();
        $sessionId = $payload['session_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (! $sessionId) {
            throw new KycVerificationException('Didit webhook missing session_id.');
        }

        // In Review / Pending etc. carry no final decision yet.
        if (! in_array($status, ['Approved', 'Declined'], true)) {
            return;
        }

        $kyc = $this->findKycByReference($provider, $sessionId);
        if (! $kyc) {
            Log::warning('Didit webhook for unknown session_id', ['session_id' => $sessionId]);

            return;
        }

        if ($status === 'Approved') {
            $this->settleKycOnce($kyc, 'verified');
        } else {
            $reason = $payload['decision']['reason'] ?? 'Verification declined by Didit.';
            $this->settleKycOnce($kyc, 'rejected', is_string($reason) ? $reason : 'Verification declined by Didit.');
        }
    }

    private function baseUrl(KycProvider $provider): string
    {
        return rtrim($provider->config['base_url'] ?? 'https://verification.didit.me', '/');
    }
}
