<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayContract;
use App\Exceptions\PaymentGatewayException;
use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Mollie\Api\MollieApiClient;

/**
 * Mollie — deposits via the Payments API (hosted checkout). Mollie doesn't
 * sign webhooks at all by design: the webhook body only ever carries an
 * `id`, and the integration is expected to re-fetch that payment from
 * Mollie's API to learn its real status — which is what handleWebhook()
 * does below, rather than trusting anything in the request body itself.
 *
 * Payout is NOT implemented: Mollie's standard API only handles merchant
 * settlement (paying the merchant, not an arbitrary customer). Sending
 * money to a customer needs Mollie's separate "Outbound Payments" product,
 * whose API this integration doesn't have confirmed access to document —
 * sendPayout() throws rather than guessing at an unverified endpoint on a
 * money-movement path.
 */
class MollieGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $client = $this->client($provider);

        $payment = $client->payments->create([
            'amount' => [
                'currency' => $provider->config['currency'] ?? 'EUR',
                'value' => number_format((float) $amount, 2, '.', ''),
            ],
            'description' => "Wallet deposit #{$deposit->id}",
            'redirectUrl' => route('gateway.return', ['deposit' => $deposit->id]),
            'webhookUrl' => route('webhooks.payments', ['slug' => $provider->slug]),
            'metadata' => ['deposit_id' => (string) $deposit->id],
        ]);

        return ['redirect_url' => $payment->getCheckoutUrl(), 'reference' => $payment->id];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $paymentId = $request->input('id');
        if (! $paymentId) {
            throw new PaymentGatewayException('Mollie webhook missing payment id.');
        }

        // No signature to check — Mollie's model is "the webhook is just a
        // ping, go ask the API what's actually true."
        $payment = $this->client($provider)->payments->get($paymentId);

        if (! $payment->isPaid()) {
            return;
        }

        $deposit = $this->findDepositByReference($provider, $paymentId);
        if ($deposit) {
            $this->creditDepositOnce($deposit, $provider);
        }
    }

    public function sendPayout(PaymentProvider $provider, Payout $payout): array
    {
        throw new PaymentGatewayException(
            'Mollie payouts require the separate "Outbound Payments" product, which is not enabled/'.
            'implemented for this integration. See SETUP_GUIDE.md / admin help text.'
        );
    }

    private function client(PaymentProvider $provider): MollieApiClient
    {
        $apiKey = $provider->credential('api_key');
        if (! $apiKey) {
            throw new PaymentGatewayException('Mollie api_key is not configured.');
        }

        $client = new MollieApiClient();
        $client->setApiKey($apiKey);

        return $client;
    }
}
