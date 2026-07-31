<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayContract;
use App\Exceptions\PaymentGatewayException;
use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * 2Checkout / Verifone — deposit only. There is no API to pay an arbitrary
 * customer at all (it's a merchant-of-record checkout product; its only
 * "payout" pays the merchant, not a customer) — sendPayout() always throws,
 * and the admin UI never shows a payout toggle for this provider.
 *
 * Uses the ConvertPlus hosted-checkout link (query-string redirect, no
 * separate "create session" API call) with a dynamic, non-catalog line
 * item, since a wallet deposit isn't a pre-configured product SKU.
 *
 * NOTE: 2Checkout/Verifone's public docs site was unreachable while this
 * was written, so the signature scheme below (confirmed) is combined with
 * the long-standing, documented ConvertPlus dynamic-line-item convention
 * for the parameter set (not independently re-confirmed here). Verify the
 * parameter names against your actual account/docs before going live —
 * see SETUP_GUIDE.md.
 */
class TwoCheckoutGateway implements PaymentGatewayContract
{
    use CreditsDepositOnce;

    private const CHECKOUT_BASE_URL = 'https://secure.2checkout.com/checkout/buy';

    public function createDeposit(PaymentProvider $provider, User $user, Deposit $deposit, string $amount): array
    {
        $merchant = $provider->credential('merchant_code');
        $secret = $provider->credential('secret_word');

        if (! $merchant || ! $secret) {
            throw new PaymentGatewayException('2Checkout merchant_code/secret_word are not configured.');
        }

        $reference = 'deposit_'.$deposit->id;

        $params = [
            'merchant' => $merchant,
            'currency' => $provider->config['currency'] ?? 'USD',
            'li_0_type' => 'product',
            'li_0_name' => 'Wallet deposit',
            'li_0_price' => number_format((float) $amount, 2, '.', ''),
            'li_0_quantity' => '1',
            'li_0_tangible' => 'N',
            'merchant_order_id' => $reference,
            'return-url' => route('gateway.return', ['deposit' => $deposit->id]),
        ];

        $params['signature'] = $this->sign($params, $secret);

        return [
            'redirect_url' => self::CHECKOUT_BASE_URL.'?'.http_build_query($params),
            'reference' => $reference,
        ];
    }

    public function handleWebhook(PaymentProvider $provider, Request $request): void
    {
        $secret = $provider->credential('secret_word');
        if (! $secret) {
            throw new PaymentGatewayException('2Checkout secret_word is not configured.');
        }

        $payload = $request->all();
        $hashField = (string) ($payload['hash'] ?? '');

        if (! str_contains($hashField, ':')) {
            throw new PaymentGatewayException('2Checkout INS payload missing hash.');
        }

        [$algo, $expectedHex] = explode(':', $hashField, 2);
        $algo = strtolower($algo) === 'sha256' ? 'sha256' : 'md5';

        $saleId = $payload['sale_id'] ?? '';
        $merchantId = $payload['vendor_id'] ?? $provider->credential('merchant_code');
        $invoiceId = $payload['invoice_id'] ?? '';

        $computed = strtoupper(hash($algo, $saleId.$merchantId.$invoiceId.$secret));

        if (! hash_equals($computed, strtoupper($expectedHex))) {
            throw new PaymentGatewayException('2Checkout INS signature mismatch.');
        }

        $status = $payload['invoice_status'] ?? $payload['message_type'] ?? null;
        if (! in_array($status, ['deposited', 'INVOICE_STATUS_CHANGED'], true)) {
            return;
        }

        $reference = $payload['merchant_order_id'] ?? $payload['vendor_order_id'] ?? null;
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
        throw new PaymentGatewayException(
            '2Checkout has no API to pay an arbitrary customer — it is a merchant-of-record checkout '.
            'product; its only payout pays the merchant, not a customer.'
        );
    }

    /**
     * length_in_chars + value for each param, sorted alphabetically by
     * name, concatenated with no separator, then HMAC-SHA256 with the Buy
     * Link Secret Word — per 2Checkout's documented ConvertPlus signature
     * scheme.
     */
    private function sign(array $params, string $secret): string
    {
        ksort($params);

        $serialized = '';
        foreach ($params as $value) {
            $value = (string) $value;
            $serialized .= strlen($value).$value;
        }

        return hash_hmac('sha256', $serialized, $secret);
    }
}
