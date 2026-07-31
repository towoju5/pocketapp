<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayContract;
use App\Exceptions\PaymentGatewayException;
use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

/**
 * Stripe — deposits are fully supported (hosted Checkout Session). Payouts
 * are NOT: paying an arbitrary end user requires that user to complete
 * Stripe Connect onboarding (their own external account) first — there is
 * no "send $X to this bank account" call without it. sendPayout() only
 * succeeds once a connected account id has actually been recorded for the
 * payout's recipient; otherwise it throws, so this can never silently
 * pretend to have paid someone.
 */
class StripeGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $client = $this->client($provider);
        $currency = $provider->config['currency'] ?? 'usd';

        $session = $client->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer_email' => $user->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => ['name' => 'Wallet deposit'],
                    'unit_amount' => (int) round(((float) $amount) * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('gateway.return', ['deposit' => $deposit->id]).'&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('gateway.return', ['deposit' => $deposit->id, 'cancelled' => 1]),
            'metadata' => ['deposit_id' => (string) $deposit->id],
        ]);

        return ['redirect_url' => $session->url, 'reference' => $session->id];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $secret = $provider->credential('webhook_secret');
        if (! $secret) {
            throw new PaymentGatewayException('Stripe webhook_secret is not configured.');
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature', ''),
                $secret
            );
        } catch (SignatureVerificationException|\UnexpectedValueException $e) {
            throw new PaymentGatewayException('Stripe webhook signature verification failed: '.$e->getMessage());
        }

        if ($event->type !== 'checkout.session.completed') {
            return;
        }

        $session = $event->data->object;
        if (($session->payment_status ?? null) !== 'paid') {
            return;
        }

        $deposit = $this->findDepositByReference($provider, $session->id);
        if ($deposit) {
            $this->creditDepositOnce($deposit, $provider);
        }
    }

    public function sendPayout(PaymentProvider $provider, Payout $payout): array
    {
        $connectedAccountId = ($payout->payout_extra_info ?? [])['stripe_connected_account_id'] ?? null;

        if (! $connectedAccountId) {
            throw new PaymentGatewayException(
                'Stripe payouts require the recipient to complete Stripe Connect onboarding first — '.
                'no connected account id is on file for this payout. See SETUP_GUIDE.md / admin help text.'
            );
        }

        $client = $this->client($provider);

        $transfer = $client->transfers->create([
            'amount' => (int) round(((float) $payout->payout_amount) * 100),
            'currency' => $provider->config['currency'] ?? 'usd',
            'destination' => $connectedAccountId,
            'description' => "Payout #{$payout->id}",
        ]);

        return ['reference' => $transfer->id, 'status' => $transfer->reversed ? 'reversed' : 'paid'];
    }

    private function client(PaymentProvider $provider): StripeClient
    {
        $secretKey = $provider->credential('secret_key');
        if (! $secretKey) {
            throw new PaymentGatewayException('Stripe secret_key is not configured.');
        }

        return new StripeClient($secretKey);
    }
}
