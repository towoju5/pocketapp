<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayContract;
use App\Exceptions\PaymentGatewayException;
use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * NOWPayments — crypto payment gateway. Talks to the REST API directly
 * rather than via the installed prevailexcel/laravel-nowpayments package:
 * that package's service class reads its API key/IPN secret from
 * config('nowpayments.*') (i.e. .env) at construction time, which can't
 * respect this app's admin-panel-managed, per-PaymentProvider encrypted
 * credentials. Replaces the old unrouted, buggy PaymentController.
 */
class NowPaymentsGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    private const LIVE_URL = 'https://api.nowpayments.io/v1';
    private const SANDBOX_URL = 'https://api-sandbox.nowpayments.io/v1';

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $reference = 'deposit_'.$deposit->id;

        $response = $this->client($provider)->post('/invoice', array_filter([
            'price_amount' => (float) $amount,
            'price_currency' => $provider->config['fiat_currency'] ?? 'usd',
            'order_id' => $reference,
            'order_description' => "Wallet deposit #{$deposit->id}",
            'ipn_callback_url' => route('webhooks.payments', ['slug' => $provider->slug]),
            'success_url' => route('gateway.return', ['deposit' => $deposit->id]),
            'cancel_url' => route('gateway.return', ['deposit' => $deposit->id, 'cancelled' => 1]),
        ]));

        if (! $response->successful()) {
            throw new PaymentGatewayException('NOWPayments invoice creation failed: '.$response->body());
        }

        $body = $response->json();

        return [
            'redirect_url' => $body['invoice_url'],
            'reference' => $reference,
        ];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $secret = $provider->credential('ipn_secret');
        if (! $secret) {
            throw new PaymentGatewayException('NOWPayments ipn_secret is not configured.');
        }

        $receivedHmac = $request->header('x-nowpayments-sig');
        if (! $receivedHmac) {
            throw new PaymentGatewayException('NOWPayments webhook missing x-nowpayments-sig header.');
        }

        // Same algorithm as prevailexcel/laravel-nowpayments's verifyIPN():
        // sort the payload by key, then HMAC-SHA512 the JSON-encoded result.
        $sorted = collect($request->all())->sortKeys();
        $expected = hash_hmac('sha512', json_encode($sorted, JSON_UNESCAPED_SLASHES), trim($secret));

        if (! hash_equals($expected, (string) $receivedHmac)) {
            throw new PaymentGatewayException('NOWPayments webhook signature mismatch.');
        }

        $payload = $request->all();
        $status = $payload['payment_status'] ?? null;

        if (! in_array($status, ['finished', 'confirmed'], true)) {
            return; // waiting/confirming/partially_paid/failed/expired — not a completed deposit yet
        }

        $reference = $payload['order_id'] ?? null;
        if (! $reference) {
            return;
        }

        $deposit = $this->findDepositByReference($provider, $reference);
        if ($deposit) {
            $this->creditDepositOnce($deposit, $provider);
        }
    }

    public function sendPayout(PaymentProvider $provider, Payout $payout): array
    {
        $extra = $payout->payout_extra_info ?? [];
        $address = $extra['address'] ?? null;
        if (! $address) {
            throw new PaymentGatewayException('Payout has no destination address.');
        }

        $jwt = $this->jwt($provider);

        $response = Http::baseUrl($this->baseUrl($provider))
            ->withToken($jwt)
            ->withHeaders(['x-api-key' => $provider->credential('api_key')])
            ->post('/payout', [
                'ipn_callback_url' => route('webhooks.payments', ['slug' => $provider->slug]),
                'withdrawals' => [[
                    'address' => $address,
                    'currency' => $extra['currency'] ?? ($provider->config['payout_currency'] ?? 'usdttrc20'),
                    'amount' => (float) $payout->payout_amount,
                ]],
            ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('NOWPayments payout failed: '.$response->body());
        }

        $body = $response->json();
        $withdrawal = $body['withdrawals'][0] ?? $body;

        // NOWPayments typically requires an emailed verification code before
        // a payout batch actually broadcasts — this call queues it, it
        // doesn't guarantee funds have moved yet.
        return [
            'reference' => (string) ($withdrawal['id'] ?? $withdrawal['batch_withdrawal_id'] ?? ''),
            'status' => $withdrawal['status'] ?? 'pending_email_verification',
        ];
    }

    private function jwt(PaymentProvider $provider): string
    {
        $email = $provider->credential('email');
        $password = $provider->credential('password');

        if (! $email || ! $password) {
            throw new PaymentGatewayException('NOWPayments email/password credentials are required to authorize payouts.');
        }

        $response = Http::baseUrl($this->baseUrl($provider))->post('/auth', [
            'email' => $email,
            'password' => $password,
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('NOWPayments authentication failed: '.$response->body());
        }

        return $response->json()['token'];
    }

    private function baseUrl(PaymentProvider $provider): string
    {
        return ($provider->config['mode'] ?? 'test') === 'test' ? self::SANDBOX_URL : self::LIVE_URL;
    }

    private function client(PaymentProvider $provider)
    {
        $apiKey = $provider->credential('api_key');
        if (! $apiKey) {
            throw new PaymentGatewayException('NOWPayments api_key is not configured.');
        }

        return Http::baseUrl($this->baseUrl($provider))->withHeaders(['x-api-key' => $apiKey]);
    }
}
