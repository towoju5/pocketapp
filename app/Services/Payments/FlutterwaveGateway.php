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
 * Flutterwave — REST v3 API. Deposits via Standard Payments (hosted link);
 * payouts via Transfers (genuine arbitrary-recipient bank/mobile-money API).
 */
class FlutterwaveGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    private const BASE_URL = 'https://api.flutterwave.com/v3';

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $reference = 'deposit_'.$deposit->id.'_'.time();

        $response = $this->client($provider)->post('/payments', [
            'tx_ref' => $reference,
            'amount' => number_format((float) $amount, 2, '.', ''),
            'currency' => $provider->config['currency'] ?? 'NGN',
            'redirect_url' => route('gateway.return', ['deposit' => $deposit->id]),
            'customer' => ['email' => $user->email],
            'customizations' => ['title' => 'Wallet deposit'],
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('Flutterwave payment initialization failed: '.$response->body());
        }

        return [
            'redirect_url' => $response->json()['data']['link'],
            'reference' => $reference,
        ];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $secretHash = $provider->credential('webhook_secret_hash');
        if (! $secretHash) {
            throw new PaymentGatewayException('Flutterwave webhook_secret_hash is not configured.');
        }

        if (! hash_equals($secretHash, (string) $request->header('verif-hash', ''))) {
            throw new PaymentGatewayException('Flutterwave webhook hash mismatch.');
        }

        $payload = $request->all();
        if (($payload['event'] ?? null) !== 'charge.completed') {
            return;
        }

        $data = $payload['data'] ?? [];
        $reference = $data['tx_ref'] ?? null;
        $transactionId = $data['id'] ?? null;

        if (! $reference || ! $transactionId || ($data['status'] ?? null) !== 'successful') {
            return;
        }

        // Re-verify server-side rather than trusting the webhook body alone.
        $verify = $this->client($provider)->get("/transactions/{$transactionId}/verify");
        $verified = $verify->json()['data'] ?? [];
        if (! $verify->successful() || ($verified['status'] ?? null) !== 'successful' || ($verified['tx_ref'] ?? null) !== $reference) {
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
            throw new PaymentGatewayException('Payout is missing account_number/bank_code required for a Flutterwave transfer.');
        }

        $response = $this->client($provider)->post('/transfers', [
            'account_bank' => $bankCode,
            'account_number' => $accountNumber,
            'amount' => (float) $payout->payout_amount,
            'currency' => $provider->config['currency'] ?? 'NGN',
            'reference' => 'payout_'.$payout->id,
            'narration' => "Payout #{$payout->id}",
        ]);

        if (! $response->successful()) {
            throw new PaymentGatewayException('Flutterwave transfer failed: '.$response->body());
        }

        $data = $response->json()['data'];

        return ['reference' => (string) $data['id'], 'status' => $data['status']];
    }

    private function client(PaymentProvider $provider)
    {
        $secretKey = $provider->credential('secret_key');
        if (! $secretKey) {
            throw new PaymentGatewayException('Flutterwave secret_key is not configured.');
        }

        return Http::baseUrl(self::BASE_URL)->withToken($secretKey);
    }
}
