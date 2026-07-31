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
 * PayPal — REST v2 Orders API for deposits, Payouts API for payouts (both
 * are genuine "arbitrary recipient" APIs, unlike Stripe/Razorpay/Mollie).
 * Implemented over raw HTTP rather than a third-party SDK wrapper.
 */
class PaypalGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $currency = $provider->config['currency'] ?? 'USD';

        $response = $this->client($provider)->post('/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => (string) $deposit->id,
                'amount' => [
                    'currency_code' => $currency,
                    'value' => number_format((float) $amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => route('gateway.return', ['deposit' => $deposit->id]),
                'cancel_url' => route('gateway.return', ['deposit' => $deposit->id, 'cancelled' => 1]),
                'user_action' => 'PAY_NOW',
            ],
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('PayPal order creation failed: '.$response->body());
        }

        $body = $response->json();
        $approveUrl = collect($body['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        if (! $approveUrl) {
            throw new PaymentGatewayException('PayPal order response had no approve link.');
        }

        return ['redirect_url' => $approveUrl, 'reference' => $body['id']];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $webhookId = $provider->credential('webhook_id');
        if (! $webhookId) {
            throw new PaymentGatewayException('PayPal webhook_id is not configured.');
        }

        $payload = $request->json()->all();

        $verify = $this->client($provider)->post('/v1/notifications/verify-webhook-signature', [
            'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
            'cert_url' => $request->header('PAYPAL-CERT-URL'),
            'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
            'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
            'webhook_id' => $webhookId,
            'webhook_event' => $payload,
        ]);

        if (! $verify->successful() || ($verify->json()['verification_status'] ?? null) !== 'SUCCESS') {
            throw new PaymentGatewayException('PayPal webhook signature verification failed.');
        }

        $eventType = $payload['event_type'] ?? null;
        if (! in_array($eventType, ['CHECKOUT.ORDER.APPROVED', 'PAYMENT.CAPTURE.COMPLETED'], true)) {
            return;
        }

        $orderId = $payload['resource']['id'] ?? $payload['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        if (! $orderId) {
            return;
        }

        $deposit = $this->findDepositByReference($provider, $orderId);
        if (! $deposit || $deposit->deposit_status === 'completed') {
            return;
        }

        // Approval alone doesn't move money — capture the order (idempotent:
        // capturing an already-captured order returns an error we can ignore).
        if ($eventType === 'CHECKOUT.ORDER.APPROVED') {
            $capture = $this->client($provider)->post("/v2/checkout/orders/{$orderId}/capture");
            if (! $capture->successful() && ! str_contains($capture->body(), 'ORDER_ALREADY_CAPTURED')) {
                throw new PaymentGatewayException('PayPal order capture failed: '.$capture->body());
            }
        }

        $this->creditDepositOnce($deposit, $provider);
    }

    public function sendPayout(PaymentProvider $provider, Payout $payout): array
    {
        $extra = $payout->payout_extra_info ?? [];
        $recipientEmail = $extra['address'] ?? null;
        if (! $recipientEmail) {
            throw new PaymentGatewayException('Payout has no destination PayPal email.');
        }

        $response = $this->client($provider)->post('/v1/payments/payouts', [
            'sender_batch_header' => [
                'sender_batch_id' => 'payout_'.$payout->id,
                'email_subject' => 'You have a payout',
            ],
            'items' => [[
                'recipient_type' => 'EMAIL',
                'amount' => [
                    'value' => number_format((float) $payout->payout_amount, 2, '.', ''),
                    'currency' => $provider->config['currency'] ?? 'USD',
                ],
                'receiver' => $recipientEmail,
                'sender_item_id' => (string) $payout->id,
            ]],
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('PayPal payout failed: '.$response->body());
        }

        $body = $response->json();

        return [
            'reference' => $body['batch_header']['payout_batch_id'] ?? '',
            'status' => $body['batch_header']['batch_status'] ?? 'PENDING',
        ];
    }

    private function client(PaymentProvider $provider)
    {
        $baseUrl = ($provider->config['mode'] ?? 'test') === 'test'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        return Http::baseUrl($baseUrl)->withToken($this->accessToken($provider, $baseUrl));
    }

    private function accessToken(PaymentProvider $provider, string $baseUrl): string
    {
        $clientId = $provider->credential('client_id');
        $secret = $provider->credential('client_secret');

        if (! $clientId || ! $secret) {
            throw new PaymentGatewayException('PayPal client_id/client_secret are not configured.');
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post("{$baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        if (! $response->successful()) {
            throw new PaymentGatewayException('PayPal OAuth token request failed: '.$response->body());
        }

        return $response->json()['access_token'];
    }
}
