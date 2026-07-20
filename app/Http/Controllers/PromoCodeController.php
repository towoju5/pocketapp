<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\PromoCode;
use App\Models\PromoCodeRedemption;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::currentlyValid()->latest()->get();
        $redeemedIds = auth()->user()->promoCodeRedemptions()->pluck('promo_code_id');

        return view('finance.promo-codes', compact('promoCodes', 'redeemedIds'));
    }

    public function redeem(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $user = auth()->user();
        $promoCode = PromoCode::where('promo_code', $validated['code'])->first();

        if (!$promoCode) {
            return response()->json(['status' => false, 'message' => 'Invalid promo code.'], 422);
        }

        if (!$promoCode->isValidNow()) {
            return response()->json(['status' => false, 'message' => 'This promo code has expired or is not active yet.'], 422);
        }

        if (PromoCodeRedemption::where('user_id', $user->id)->where('promo_code_id', $promoCode->id)->exists()) {
            return response()->json(['status' => false, 'message' => 'You have already redeemed this code.'], 422);
        }

        $amount = (float) $promoCode->promo_discount;

        if ($promoCode->promo_discount_type === 'percentage') {
            $lastDeposit = Deposit::where('user_id', $user->id)->where('deposit_status', 'completed')->latest()->first();
            if (!$lastDeposit) {
                return response()->json(['status' => false, 'message' => 'Make a deposit first to redeem a percentage bonus.'], 422);
            }
            $amount = ((float) $promoCode->promo_discount / 100) * (float) $lastDeposit->deposit_amount;
        }

        if ($amount <= 0) {
            return response()->json(['status' => false, 'message' => 'This promo code has no redeemable value.'], 422);
        }

        create_user_wallet($user->id);
        $user->getWallet('qt_real_usd')->deposit($amount, [
            'description' => "Promo code bonus: {$promoCode->promo_code}",
        ]);

        PromoCodeRedemption::create([
            'user_id' => $user->id,
            'promo_code_id' => $promoCode->id,
            'amount_credited' => $amount,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Promo code redeemed! $' . number_format($amount, 2) . ' credited to your real wallet.',
        ]);
    }
}
