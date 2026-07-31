<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProvider;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
{
    /**
     * Per-provider credential field names -> admin-facing labels. Drives
     * the dynamic form in admin/payment-providers/edit.blade.php.
     */
    private const CREDENTIAL_FIELDS = [
        'stripe' => [
            'secret_key' => 'Secret key',
            'publishable_key' => 'Publishable key',
            'webhook_secret' => 'Webhook signing secret',
        ],
        'paypal' => [
            'client_id' => 'Client ID',
            'client_secret' => 'Client secret',
            'webhook_id' => 'Webhook ID',
        ],
        'paystack' => [
            'secret_key' => 'Secret key',
        ],
        '2checkout' => [
            'merchant_code' => 'Merchant code',
            'secret_word' => 'Buy Link secret word',
        ],
        'flutterwave' => [
            'secret_key' => 'Secret key',
            'webhook_secret_hash' => 'Webhook secret hash',
        ],
        'razorpay' => [
            'key_id' => 'Key ID',
            'key_secret' => 'Key secret',
            'webhook_secret' => 'Webhook secret',
        ],
        'mollie' => [
            'api_key' => 'API key',
        ],
        'puffin' => [
            'api_key' => 'API key',
            'webhook_secret' => 'Webhook signing secret (whsec_...)',
        ],
        'nowpayments' => [
            'api_key' => 'API key',
            'ipn_secret' => 'IPN secret',
            'email' => 'Account email (payouts only)',
            'password' => 'Account password (payouts only)',
        ],
    ];

    /**
     * Per-provider config field names -> admin-facing labels. Non-secret
     * settings (currency, chain, mode) stored alongside credentials.
     */
    private const CONFIG_FIELDS = [
        'stripe' => ['currency' => 'Currency (e.g. usd)'],
        'paypal' => ['currency' => 'Currency (e.g. USD)'],
        'paystack' => ['currency' => 'Currency (e.g. NGN)'],
        '2checkout' => ['currency' => 'Currency (e.g. USD)'],
        'flutterwave' => ['currency' => 'Currency (e.g. NGN)'],
        'razorpay' => [
            'currency' => 'Currency (e.g. INR)',
            'razorpayx_account_number' => 'RazorpayX virtual account number (payouts only)',
        ],
        'mollie' => ['currency' => 'Currency (e.g. EUR)'],
        'puffin' => [
            'deposit_token' => 'Deposit token (e.g. USDC_BASE)',
            'deposit_chain' => 'Deposit chain (e.g. BASE)',
            'payout_wallet_id' => 'Payout wallet ID (from Puffin dashboard)',
        ],
        'nowpayments' => [
            'fiat_currency' => 'Fiat currency (e.g. usd)',
            'payout_currency' => 'Payout currency (e.g. usdttrc20)',
        ],
    ];

    /**
     * Shown next to the payout toggle for gateways where paying an
     * arbitrary customer needs more than just API keys — see
     * SETUP_GUIDE.md for the full explanation.
     */
    private const PAYOUT_WARNING = [
        'stripe' => 'Requires the recipient to complete Stripe Connect onboarding (their own external account) before a payout can succeed — enabling this alone does not make payouts work.',
        'razorpay' => 'Requires RazorpayX to be separately enabled/provisioned on this Razorpay account — the standard Payment Gateway keys alone are not enough.',
        'mollie' => 'Requires Mollie\'s separate "Outbound Payments" product. Not implemented by this integration — payouts will fail until that changes.',
    ];

    public function index()
    {
        $providers = PaymentProvider::orderBy('sort_order')->get();

        return view('admin.payment-providers.index', compact('providers'));
    }

    /**
     * Gateways with a genuinely separate sandbox API host — for these, the
     * mode toggle changes which URL is called. For everything else, test
     * vs. live is purely a matter of which key (test_.../live_...) is
     * pasted below; the toggle is a reminder/record of that, not a
     * behavior switch.
     */
    private const MODE_CHANGES_ENDPOINT = ['paypal', 'nowpayments'];

    public function edit(PaymentProvider $paymentProvider)
    {
        $credentialFields = self::CREDENTIAL_FIELDS[$paymentProvider->slug] ?? [];
        $configFields = self::CONFIG_FIELDS[$paymentProvider->slug] ?? [];
        $payoutWarning = self::PAYOUT_WARNING[$paymentProvider->slug] ?? null;
        $modeChangesEndpoint = in_array($paymentProvider->slug, self::MODE_CHANGES_ENDPOINT, true);
        $webhookUrl = route('webhooks.payments', ['slug' => $paymentProvider->slug]);

        return view('admin.payment-providers.edit', compact('paymentProvider', 'credentialFields', 'configFields', 'payoutWarning', 'modeChangesEndpoint', 'webhookUrl'));
    }

    public function update(Request $request, PaymentProvider $paymentProvider)
    {
        $validated = $request->validate([
            'mode' => 'required|in:test,live',
            'min_deposit' => 'nullable|numeric|min:0',
            'max_deposit' => 'nullable|numeric|min:0',
            'min_payout' => 'nullable|numeric|min:0',
            'max_payout' => 'nullable|numeric|min:0',
            'credentials' => 'array',
            'config' => 'array',
        ]);

        // 'mode' (test|live) is universal across every gateway — stored
        // alongside the rest of config, but always present regardless of
        // that provider's own per-slug CONFIG_FIELDS list. Actually changes
        // behavior for gateways with a distinct sandbox API (PayPal,
        // NOWPayments); for the rest it's the difference between which key
        // (test_.../live_...) the admin pastes below.
        $config = array_merge(
            ['mode' => $validated['mode']],
            array_filter($validated['config'] ?? [])
        );

        $paymentProvider->update([
            'is_active' => $request->boolean('is_active'),
            'can_deposit' => $request->boolean('can_deposit'),
            // 2Checkout has no API to pay a customer at all — never let
            // this be turned on regardless of what's posted.
            'can_payout' => $paymentProvider->slug === '2checkout' ? false : $request->boolean('can_payout'),
            'min_deposit' => $validated['min_deposit'] ?? null,
            'max_deposit' => $validated['max_deposit'] ?? null,
            'min_payout' => $validated['min_payout'] ?? null,
            'max_payout' => $validated['max_payout'] ?? null,
            'credentials' => array_filter($validated['credentials'] ?? []),
            'config' => $config,
        ]);

        return redirect()->route('admin.payment-providers.index')
            ->with('success', "{$paymentProvider->display_name} updated.");
    }
}
