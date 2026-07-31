<?php

namespace App\Http\Controllers;

use App\Exceptions\PaymentGatewayException;
use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Services\Payments\PaymentGatewayResolver;
use Carbon\Carbon;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GatewayCheckoutController extends Controller
{
    /**
     * Starts a hosted-checkout deposit for one of the payment_providers
     * gateways (Stripe, PayPal, Paystack, 2Checkout, Flutterwave, Razorpay,
     * Mollie, Puffin, NOWPayments) — a separate, self-contained path from
     * DepositController's existing BitGo crypto-address wizard.
     */
    public function redirect(Request $request, PaymentProvider $provider)
    {
        if (! $provider->is_active || ! $provider->can_deposit) {
            return back()->with('error', 'This payment method is not currently available.');
        }

        $rules = ['amount' => 'required|numeric|min:0.01'];
        if ($provider->min_deposit) {
            $rules['amount'] .= '|min:'.$provider->min_deposit;
        }
        if ($provider->max_deposit) {
            $rules['amount'] .= '|max:'.$provider->max_deposit;
        }
        $validated = $request->validate($rules);

        $user = Auth::user();
        create_user_wallet($user->id);
        $walletModel = $user->getWallet('qt_real_usd');

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'wallet_id' => $walletModel->id,
            'deposit_amount' => $validated['amount'],
            'deposit_date_time' => Carbon::now(),
            'deposit_status' => 'pending',
            'deposit_method' => $provider->slug,
            'deposit_extra_info' => json_encode(['provider_id' => $provider->id]),
        ]);

        try {
            $result = PaymentGatewayResolver::resolve($provider)
                ->createDeposit($provider, $user, $deposit, (string) $validated['amount']);
        } catch (PaymentGatewayException $e) {
            $deposit->update(['deposit_status' => 'failed']);
            report($e);

            return back()->with('error', 'Unable to start payment: '.$e->getMessage());
        }

        $deposit->update([
            'deposit_extra_info' => json_encode([
                'provider_id' => $provider->id,
                'reference' => $result['reference'],
            ]),
        ]);

        return redirect()->away($result['redirect_url']);
    }

    /**
     * Landing page after a hosted checkout redirect (success or cancel).
     * Never credits anything itself — only the provider's webhook does
     * that, since a browser redirect is not proof a payment settled.
     */
    public function return(Request $request)
    {
        $deposit = $request->filled('deposit') ? Deposit::find($request->query('deposit')) : null;
        $cancelled = $request->boolean('cancelled');

        if ($cancelled && $deposit && $deposit->user_id === Auth::id() && $deposit->deposit_status === 'pending') {
            $deposit->update(['deposit_status' => 'cancelled']);
        }

        if ($request->wantsJson()) {
            return response()->json(['deposit_id' => $deposit?->id, 'cancelled' => $cancelled]);
        }

        Flasher::addSuccess($cancelled ? 'Payment cancelled.' : 'Payment received — confirming with the provider now.');

        return redirect()->route('deposits.index');
    }
}
