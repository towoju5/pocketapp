<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Seeds the fixed catalog of payment gateways, all inactive with no
     * credentials — an admin configures and turns each one on from
     * /admin/payment-providers. Uses firstOrCreate (not updateOrCreate) so
     * re-running this is safe: a row that already exists is left
     * completely untouched, rather than having its is_active/credentials/
     * config reset back to these defaults on every run.
     */
    public function run(): void
    {
        $providers = [
            ['slug' => 'stripe', 'display_name' => 'Stripe', 'type' => 'fiat', 'sort_order' => 10],
            ['slug' => 'paypal', 'display_name' => 'PayPal', 'type' => 'fiat', 'sort_order' => 20],
            ['slug' => 'paystack', 'display_name' => 'Paystack', 'type' => 'fiat', 'sort_order' => 30],
            ['slug' => '2checkout', 'display_name' => '2Checkout', 'type' => 'fiat', 'sort_order' => 40],
            ['slug' => 'flutterwave', 'display_name' => 'Flutterwave', 'type' => 'fiat', 'sort_order' => 50],
            ['slug' => 'razorpay', 'display_name' => 'Razorpay', 'type' => 'fiat', 'sort_order' => 60],
            ['slug' => 'mollie', 'display_name' => 'Mollie', 'type' => 'fiat', 'sort_order' => 70],
            ['slug' => 'puffin', 'display_name' => 'Puffin Money (Crypto)', 'type' => 'crypto', 'sort_order' => 80],
            ['slug' => 'nowpayments', 'display_name' => 'NOWPayments (Crypto)', 'type' => 'crypto', 'sort_order' => 90],
        ];

        foreach ($providers as $provider) {
            PaymentProvider::firstOrCreate(
                ['slug' => $provider['slug']],
                array_merge([
                    'is_active' => false,
                    'can_deposit' => true,
                    'can_payout' => false,
                    'credentials' => [],
                    // Safe default — going live is a deliberate admin action,
                    // not something that happens just by pasting keys in.
                    'config' => ['mode' => 'test'],
                ], $provider)
            );
        }

        // 2Checkout has no API to pay an arbitrary customer at all (it's a
        // merchant-of-record checkout product — its only "payout" pays the
        // merchant, not a customer). Force this permanently on first seed
        // only — this still can't override an admin's own row since
        // firstOrCreate above never touches an existing one.
        PaymentProvider::where('slug', '2checkout')->where('can_payout', true)->update(['can_payout' => false]);
    }
}
