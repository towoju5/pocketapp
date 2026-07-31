<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayContract;
use App\Exceptions\PaymentGatewayException;
use App\Models\PaymentProvider;

class PaymentGatewayResolver
{
    private const MAP = [
        'stripe' => StripeGateway::class,
        'paypal' => PaypalGateway::class,
        'paystack' => PaystackGateway::class,
        '2checkout' => TwoCheckoutGateway::class,
        'flutterwave' => FlutterwaveGateway::class,
        'razorpay' => RazorpayGateway::class,
        'mollie' => MollieGateway::class,
        'puffin' => PuffinGateway::class,
        'nowpayments' => NowPaymentsGateway::class,
    ];

    public static function resolve(PaymentProvider $provider): PaymentGatewayContract
    {
        $class = self::MAP[$provider->slug] ?? null;

        if (! $class) {
            throw new PaymentGatewayException("No gateway implementation registered for '{$provider->slug}'.");
        }

        return app($class);
    }
}
