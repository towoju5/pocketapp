<?php

namespace App\Services\Kyc;

use App\Contracts\KycProviderContract;
use App\Exceptions\KycVerificationException;
use App\Models\KycProvider;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Persona — hosted Inquiry flow. No server-side API call is needed to
 * "start" a verification: Persona's hosted flow accepts a reference-id
 * directly in the redirect URL and creates the Inquiry client-side, which
 * is what startVerification() builds below. referenceId is set to
 * "kyc-{$kyc->id}" so handleWebhook() can match the eventual result back to
 * this row without needing Persona's own inquiry-id.
 *
 * Webhook signature per Persona's documented scheme: header
 * `Persona-Signature: t=<unix_ts>,v1=<hex_hmac>`, where v1 =
 * HMAC-SHA256(webhook_secret, "{t}.{raw_body}"). Verify against current
 * Persona docs before going live — same caveat as SumsubProvider.
 */
class PersonaProvider implements KycProviderContract
{
    use SettlesKycVerificationOnce;

    public function startVerification(KycProvider $provider, User $user, KycVerification $kyc): array
    {
        $templateId = $provider->credential('inquiry_template_id') ?? $provider->config['inquiry_template_id'] ?? null;
        $environmentId = $provider->credential('environment_id') ?? $provider->config['environment_id'] ?? null;

        if (! $templateId || ! $environmentId) {
            throw new KycVerificationException('Persona inquiry_template_id/environment_id is not configured.');
        }

        $referenceId = "kyc-{$kyc->id}";

        $url = 'https://withpersona.com/verify?'.http_build_query([
            'inquiry-template-id' => $templateId,
            'environment-id' => $environmentId,
            'reference-id' => $referenceId,
        ]);

        return ['redirect_url' => $url, 'reference' => $referenceId];
    }

    public function handleWebhook(KycProvider $provider, Request $request): void
    {
        $secret = $provider->credential('webhook_secret');
        if (! $secret) {
            throw new KycVerificationException('Persona webhook_secret is not configured.');
        }

        $header = $request->header('Persona-Signature', '');
        parse_str(str_replace(',', '&', $header), $parts);
        $timestamp = $parts['t'] ?? null;
        $signature = $parts['v1'] ?? null;

        if (! $timestamp || ! $signature) {
            throw new KycVerificationException('Persona webhook missing signature header.');
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$request->getContent(), $secret);
        if (! hash_equals($expected, $signature)) {
            throw new KycVerificationException('Persona webhook signature mismatch.');
        }

        $payload = $request->json()->all();
        $eventName = $payload['data']['attributes']['name'] ?? null;
        $inquiry = $payload['data']['attributes']['payload']['data'] ?? null;
        $referenceId = $inquiry['attributes']['referenceId'] ?? null;

        if (! $referenceId || ! $eventName) {
            throw new KycVerificationException('Persona webhook missing reference id or event name.');
        }

        // completed/created/expired etc. carry no final decision — only
        // approved/declined do.
        if (! in_array($eventName, ['inquiry.approved', 'inquiry.declined'], true)) {
            return;
        }

        $kyc = $this->findKycByReference($provider, $referenceId);
        if (! $kyc) {
            Log::warning('Persona webhook for unknown referenceId', ['referenceId' => $referenceId]);

            return;
        }

        if ($eventName === 'inquiry.approved') {
            $this->settleKycOnce($kyc, 'verified');
        } else {
            $reason = $inquiry['attributes']['declineReasons'][0] ?? 'Verification declined by Persona.';
            $this->settleKycOnce($kyc, 'rejected', is_string($reason) ? $reason : 'Verification declined by Persona.');
        }
    }
}
