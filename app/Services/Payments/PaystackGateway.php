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
 * Paystack — REST API for deposits (Transaction Initialize) and payouts
 * (Transfers, via a Transfer Recipient). Amounts are in kobo (smallest
 * currency unit) everywhere in Paystack's API.
 */
class PaystackGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    private const BASE_URL = 'https://api.paystack.co';

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $response = $this->client($provider)->post('/transaction/initialize', [
            'email' => $user->email,
            'amount' => (int) round(((float) $amount) * 100),
            'currency' => $provider->config['currency'] ?? 'NGN',
            'callback_url' => route('gateway.return', ['deposit' => $deposit->id]),
            'metadata' => ['deposit_id' => $deposit->id],
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('Paystack transaction initialize failed: '.$response->body());
        }

        $data = $response->json()['data'];

        return ['redirect_url' => $data['authorization_url'], 'reference' => $data['reference']];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $secret = $provider->credential('secret_key');
        if (! $secret) {
            throw new PaymentGatewayException('Paystack secret_key is not configured.');
        }

        $expected = hash_hmac('sha512', $request->getContent(), $secret);
        if (! hash_equals($expected, (string) $request->header('x-paystack-signature', ''))) {
            throw new PaymentGatewayException('Paystack webhook signature mismatch.');
        }

        $payload = $request->all();
        if (($payload['event'] ?? null) !== 'charge.success') {
            return;
        }

        $reference = $payload['data']['reference'] ?? null;
        if (! $reference) {
            return;
        }

        // Re-verify server-side rather than trusting the webhook body alone
        // (Paystack's own recommendation).
        $verify = $this->client($provider)->get("/transaction/verify/{$reference}");
        if (! $verify->successful() || ($verify->json()['data']['status'] ?? null) !== 'success') {
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
        $accountNumber = $extra['account_number'] ?? null;
        $bankCode = $extra['bank_code'] ?? null;

        if (! $accountNumber || ! $bankCode) {
            throw new PaymentGatewayException('Payout is missing account_number/bank_code required for a Paystack transfer.');
        }

        $client = $this->client($provider);

        $recipient = $client->post('/transferrecipient', [
            'type' => 'nuban',
            'name' => $payout->user->name ?? $payout->user->first_name ?? 'Customer',
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
            'currency' => $provider->config['currency'] ?? 'NGN',
        ]);

        if (! $recipient->successful()) {
            throw new PaymentGatewayException('Paystack transfer recipient creation failed: '.$recipient->body());
        }

        $recipientCode = $recipient->json()['data']['recipient_code'];

        $transfer = $client->post('/transfer', [
            'source' => 'balance',
            'amount' => (int) round(((float) $payout->payout_amount) * 100),
            'recipient' => $recipientCode,
            'reason' => "Payout #{$payout->id}",
            'reference' => 'payout_'.$payout->id,
        ]);

        if (! $transfer->successful()) {
            throw new PaymentGatewayException('Paystack transfer failed: '.$transfer->body());
        }

        $data = $transfer->json()['data'];

        return ['reference' => (string) $data['transfer_code'], 'status' => $data['status']];
    }

    private function client(PaymentProvider $provider)
    {
        $secretKey = $provider->credential('secret_key');
        if (! $secretKey) {
            throw new PaymentGatewayException('Paystack secret_key is not configured.');
        }

        return Http::baseUrl(self::BASE_URL)->withToken($secretKey);
    }
}
