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
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Utility;

/**
 * Razorpay — deposits use Payment Links (a genuine hosted checkout URL,
 * unlike the Orders API + Checkout.js widget flow, which needs a JS modal
 * rather than a redirect). Payouts use RazorpayX's Payouts API, a
 * *separate product* from the standard Payment Gateway that must be
 * enabled on the account — the same API key pair reaches it, but the call
 * fails outright if RazorpayX itself isn't provisioned. sendPayout()
 * attempts the real call and surfaces whatever Razorpay returns rather
 * than pretending success.
 */
class RazorpayGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    private const BASE_URL = 'https://api.razorpay.com/v1';

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $api = $this->api($provider);

        $link = $api->paymentLink->create([
            'amount' => (int) round(((float) $amount) * 100),
            'currency' => $provider->config['currency'] ?? 'INR',
            'description' => "Wallet deposit #{$deposit->id}",
            'customer' => ['email' => $user->email],
            'notify' => ['sms' => false, 'email' => false],
            'reminder_enable' => false,
            'callback_url' => route('gateway.return', ['deposit' => $deposit->id]),
            'callback_method' => 'get',
            'reference_id' => (string) $deposit->id,
        ])->toArray();

        return ['redirect_url' => $link['short_url'], 'reference' => $link['id']];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $secret = $provider->credential('webhook_secret');
        if (! $secret) {
            throw new PaymentGatewayException('Razorpay webhook_secret is not configured.');
        }

        try {
            (new Utility())->verifyWebhookSignature(
                $request->getContent(),
                $request->header('X-Razorpay-Signature', ''),
                $secret
            );
        } catch (SignatureVerificationError $e) {
            throw new PaymentGatewayException('Razorpay webhook signature verification failed: '.$e->getMessage());
        }

        $payload = $request->all();
        if (($payload['event'] ?? null) !== 'payment_link.paid') {
            return;
        }

        $reference = $payload['payload']['payment_link']['entity']['id'] ?? null;
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
        $fundAccountId = $extra['razorpayx_fund_account_id'] ?? null;
        $accountNumber = $provider->config['razorpayx_account_number'] ?? null;

        if (! $fundAccountId || ! $accountNumber) {
            throw new PaymentGatewayException(
                'Razorpay payouts need RazorpayX enabled on this account, plus a fund account id for '.
                'the recipient — this is a separate product/setup from the standard payment gateway. '.
                'See SETUP_GUIDE.md / admin help text.'
            );
        }

        $keyId = $provider->credential('key_id');
        $keySecret = $provider->credential('key_secret');

        $response = Http::baseUrl(self::BASE_URL)
            ->withBasicAuth($keyId, $keySecret)
            ->post('/payouts', [
                'account_number' => $accountNumber,
                'fund_account_id' => $fundAccountId,
                'amount' => (int) round(((float) $payout->payout_amount) * 100),
                'currency' => $provider->config['currency'] ?? 'INR',
                'mode' => $extra['payout_mode'] ?? 'IMPS',
                'purpose' => 'payout',
                'queue_if_low_balance' => true,
                'reference_id' => 'payout_'.$payout->id,
                'narration' => "Payout #{$payout->id}",
            ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('RazorpayX payout failed: '.$response->body());
        }

        $body = $response->json();

        return ['reference' => (string) $body['id'], 'status' => $body['status']];
    }

    private function api(PaymentProvider $provider): Api
    {
        $keyId = $provider->credential('key_id');
        $keySecret = $provider->credential('key_secret');

        if (! $keyId || ! $keySecret) {
            throw new PaymentGatewayException('Razorpay key_id/key_secret are not configured.');
        }

        return new Api($keyId, $keySecret);
    }
}
