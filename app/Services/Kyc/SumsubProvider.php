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
 * Sumsub — hosted WebSDK verification flow. Every request to Sumsub's REST
 * API (not just webhooks) is HMAC-signed per their documented scheme:
 * X-App-Access-Sig = HMAC-SHA256(secretKey, ts + METHOD + path + rawBody),
 * sent alongside X-App-Token and X-App-Access-Ts. See client() below.
 *
 * externalUserId is what ties a Sumsub applicant back to our own
 * KycVerification row — set to "kyc-{$kyc->id}" at startVerification() and
 * matched back to it in handleWebhook(), so provider_reference on our side
 * stores that same string rather than Sumsub's own applicantId (which we
 * never actually need).
 *
 * Endpoint paths/payload shapes reflect Sumsub's WebSDK + webhook docs at
 * time of writing — verify against current Sumsub API docs before going
 * live, the same caveat this codebase already gives Mollie/Razorpay payout
 * integrations for less-common API surfaces.
 */
class SumsubProvider implements KycProviderContract
{
    use SettlesKycVerificationOnce;

    public function startVerification(KycProvider $provider, User $user, KycVerification $kyc): array
    {
        $levelName = $provider->config['level_name'] ?? null;
        if (! $levelName) {
            throw new KycVerificationException('Sumsub level_name is not configured.');
        }

        $externalUserId = "kyc-{$kyc->id}";
        $ttl = (int) ($provider->config['link_ttl_seconds'] ?? 1800);

        $path = '/resources/sdkIntegrations/levels/'.rawurlencode($levelName).'/websdkLink'
            .'?externalUserId='.rawurlencode($externalUserId)
            .'&ttlInSecs='.$ttl;

        $response = $this->client($provider, 'GET', $path)->get($this->baseUrl($provider).$path);

        if (! $response->successful() || ! $response->json('url')) {
            throw new KycVerificationException('Sumsub link generation failed: '.$response->body());
        }

        return ['redirect_url' => $response->json('url'), 'reference' => $externalUserId];
    }

    public function handleWebhook(KycProvider $provider, Request $request): void
    {
        $secret = $provider->credential('webhook_secret');
        if (! $secret) {
            throw new KycVerificationException('Sumsub webhook_secret is not configured.');
        }

        $digestHeader = $request->header('x-payload-digest');
        $expected = hash_hmac('sha256', $request->getContent(), $secret);

        if (! $digestHeader || ! hash_equals($expected, $digestHeader)) {
            throw new KycVerificationException('Sumsub webhook signature mismatch.');
        }

        $payload = $request->json()->all();
        $externalUserId = $payload['externalUserId'] ?? null;
        $type = $payload['type'] ?? null;

        if (! $externalUserId) {
            throw new KycVerificationException('Sumsub webhook missing externalUserId.');
        }

        // Only applicantReviewed carries a real decision — applicantCreated/
        // applicantPending etc. are progress pings with nothing to settle.
        if ($type !== 'applicantReviewed') {
            return;
        }

        $kyc = $this->findKycByReference($provider, $externalUserId);
        if (! $kyc) {
            Log::warning('Sumsub webhook for unknown externalUserId', ['externalUserId' => $externalUserId]);

            return;
        }

        $answer = $payload['reviewResult']['reviewAnswer'] ?? null;
        $rejectLabels = $payload['reviewResult']['rejectLabels'] ?? [];

        if ($answer === 'GREEN') {
            $this->settleKycOnce($kyc, 'verified');
        } elseif ($answer === 'RED') {
            $this->settleKycOnce($kyc, 'rejected', $rejectLabels ? implode(', ', $rejectLabels) : 'Verification declined by Sumsub.');
        }
    }

    private function baseUrl(KycProvider $provider): string
    {
        return rtrim($provider->config['base_url'] ?? 'https://api.sumsub.com', '/');
    }

    private function client(KycProvider $provider, string $method, string $path, string $body = '')
    {
        $appToken = $provider->credential('app_token');
        $secretKey = $provider->credential('secret_key');

        if (! $appToken || ! $secretKey) {
            throw new KycVerificationException('Sumsub app_token/secret_key is not configured.');
        }

        $ts = time();
        $sig = hash_hmac('sha256', $ts.strtoupper($method).$path.$body, $secretKey);

        return Http::withHeaders([
            'X-App-Token' => $appToken,
            'X-App-Access-Sig' => $sig,
            'X-App-Access-Ts' => $ts,
        ]);
    }
}
