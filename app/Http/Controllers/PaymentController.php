<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PrevailExcel\Nowpayments\Facades\Nowpayments;

class PaymentController extends Controller
{
    public function nowpayments($depositId = null)
    {
        try{
            $data = [
                'price_amount' => $this->deposit->amount ?? 100,
                'price_currency' => $this->deposit->currency ?? 'usd',
                'order_id' => $this->deposit->id ?? uniqid(),
                'pay_currency' => $this->deposit->pay_currency ?? 'btc',
                'payout_currency' => $this->deposit->payout_currency ?? 'btc',
            ];

           $paymentDetails = Nowpayments::createPayment($data);
            
            dd($paymentDetails);
            // Now you have the payment details,
            // you can then redirect or do whatever you want

            return Redirect::back()->with(['msg'=>"Payment created successfully", 'type'=>'success'], $paymentDetails);
        }catch(\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>"There's an error in the data", 'type'=>'error']);
        }   
    }

    public function paymentStatus($depositId)
    {
        try {
            $deposit = nowpayments()->getPaymentStatus();
            dd($deposit);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
