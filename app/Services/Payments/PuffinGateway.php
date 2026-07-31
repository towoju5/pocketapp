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
 * Puffin Money — crypto payment gateway (docs.puffinmoney.com). Deposits are
 * hosted-checkout payment intents (like the fiat gateways below); payouts
 * withdraw from a merchant wallet the admin creates once via createWallet()
 * and records in config['payout_wallet_id'].
 */
class PuffinGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    private const BASE_URL = 'https://api.puffinmoney.com/v1';

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $token = $provider->config['deposit_token'] ?? 'USDC_BASE';
        $chain = $provider->config['deposit_chain'] ?? 'BASE';

        $response = $this->client($provider)->post('/gateway/api/payment-intents', [
            'amount' => number_format((float) $amount, 2, '.', ''),
            'token' => $token,
            'chain' => $chain,
            'customerEmail' => $user->email,
            'successUrl' => route('gateway.return', ['deposit' => $deposit->id]),
            'cancelUrl' => route('gateway.return', ['deposit' => $deposit->id, 'cancelled' => 1]),
            'metadata' => ['depositId' => (string) $deposit->id],
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('Puffin payment-intent creation failed: '.$response->body());
        }

        $body = $response->json();

        return [
            'redirect_url' => $body['checkoutUrl'],
            'reference' => $body['intent']['id'],
        ];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $signature = $request->header('X-Puffin-Signature', '');
        $secret = $provider->credential('webhook_secret');

        if (! $secret) {
            throw new PaymentGatewayException('Puffin webhook_secret is not configured.');
        }

        $expected = hash_hmac('sha256', $request->getContent(), $secret);

        if (! hash_equals($expected, (string) $signature)) {
            throw new PaymentGatewayException('Puffin webhook signature mismatch.');
        }

        $payload = $request->json()->all();
        $event = $payload['event'] ?? null;

        if (! in_array($event, ['payment.confirmed', 'wallet.deposit.confirmed'], true)) {
            return; // other event types (disputes, late payments, bridge) aren't deposit confirmations
        }

        $reference = $payload['data']['paymentIntentId'] ?? null;
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
        $walletId = $provider->config['payout_wallet_id'] ?? null;
        if (! $walletId) {
            throw new PaymentGatewayException('No Puffin payout wallet configured — create one from the admin screen first.');
        }

        $extra = $payout->payout_extra_info ?? [];
        $toAddress = $extra['address'] ?? null;
        if (! $toAddress) {
            throw new PaymentGatewayException('Payout has no destination address.');
        }

        $response = $this->client($provider)
            ->withHeaders(['Idempotency-Key' => 'payout_'.$payout->id])
            ->post("/gateway/api/wallets/{$walletId}/withdrawals", [
                'toAddress' => $toAddress,
                'amount' => number_format((float) $payout->payout_amount, 2, '.', ''),
                'note' => "Payout #{$payout->id}",
            ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('Puffin withdrawal failed: '.$response->body());
        }

        $tx = $response->json()['transaction'];

        return ['reference' => $tx['id'], 'status' => $tx['status']];
    }

    /**
     * Admin-only helper (not part of the shared contract) — provisions a
     * merchant wallet, e.g. for use as the payout source. The returned id
     * is what an admin pastes into config['payout_wallet_id'].
     */
    public function createWallet(PaymentProvider $provider, string $chain, string $token, ?string $label = null): array
    {
        $response = $this->client($provider)->post('/gateway/api/wallets', array_filter([
            'chain' => $chain,
            'token' => $token,
            'label' => $label,
        ]));

        if (! $response->successful()) {
            throw new PaymentGatewayException('Puffin wallet creation failed: '.$response->body());
        }

        return $response->json()['wallet'];
    }

    private function client(PaymentProvider $provider)
    {
        $apiKey = $provider->credential('api_key');
        if (! $apiKey) {
            throw new PaymentGatewayException('Puffin api_key is not configured.');
        }

        return Http::baseUrl(self::BASE_URL)->withHeaders(['X-API-Key' => $apiKey]);
    }
}
