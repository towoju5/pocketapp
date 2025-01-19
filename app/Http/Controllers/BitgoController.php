<?php

namespace App\Http\Controllers;

use App\Models\BitgoWallets;
use Illuminate\Http\Request;
use BitGoSDK\BitGo;

class BitgoController extends Controller
{
    private $bitgo;

    public function __construct()
    {
        // $this->bitgo = new Bitgo([
        //     'accessToken' => config('bitgo.access_token'),
        //     'env' => config('bitgo.env', 'test'), // 'test' or 'prod'
        // ]);
    }

    // Generate a new wallet
    public function generateWallet(Request $request)
    {
        $walletName = $request->input('wallet_ticker');
        $passphrase = config('bitgo.passphrase');

        try {
            $wallet = $this->bitgo->wallet()->create([
                'label' => $walletName,
                'passphrase' => $passphrase,
            ]);

            \App\Models\Bitgo::create([
                'wallet_id' => $wallet['id'],
                'wallet_name' => $walletName,
                'wallet_ticker' => $wallet['coin'],
                'type' => $wallet['type'],
                'require_memo' => $wallet['coin'] === 'xrp' || $wallet['coin'] === 'xlm', 
            ]);

            return response()->json(['message' => 'Wallet generated successfully', 'data' => $wallet]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate wallet', 'details' => $e->getMessage()], 500);
        }
    }

    // Generate a new wallet address
    public function generateWalletAddress($coin)
    {
        try {
            return bitgoDepositAddress($coin);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate address', 'details' => $e->getMessage()], 500);
        }
    }

    // Handle deposit webhook
    public function depositWebhook(Request $request)
    {
        $payload = $request->all();

        if ($payload['event'] === 'deposit') {
            try {
                // Find the wallet address in the BitgoWallet model
                $bitgoWallet = BitgoWallets::where('address', $payload['address'])->first();

                if ($bitgoWallet) {
                    // Retrieve the user associated with the wallet
                    $user = $bitgoWallet->user;

                    if ($user) {
                        // Credit the user's wallet using Bavix Multi-Wallet system
                        $wallet = $user->wallet; // Assuming `wallet` is the default wallet
                        $amount = $payload['amount'] / 100000000; // Convert satoshis to coins, adjust decimals as needed

                        $wallet->deposit($amount, [
                            'description' => 'Deposit from BitGo wallet',
                            'transaction_id' => $payload['transactionId'],
                        ]);

                        // Optionally log the transaction in your database
                        $bitgoWallet->transactions()->create([
                            'user_id' => $user->id,
                            'amount' => $amount,
                            'type' => 'deposit',
                            'transaction_id' => $payload['transactionId'],
                        ]);

                        return response()->json(['message' => 'Deposit processed successfully']);
                    } else {
                        return response()->json(['error' => 'User not found for the wallet address'], 404);
                    }
                }

                return response()->json(['error' => 'Wallet address not found'], 404);
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred', 'details' => $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid webhook event'], 400);
    }


    // Payout
    public function payout(Request $request, $walletId)
    {
        $wallet = Bitgo::where('wallet_id', $walletId)->first();

        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found'], 404);
        }

        $address = $request->input('address');
        $amount = $request->input('amount');
        $passphrase = config('bitgo.passphrase');

        try {
            $response = $this->bitgo->wallet()->get($walletId)->sendCoins([
                'address' => $address,
                'amount' => $amount,
                'walletPassphrase' => $passphrase,
            ]);

            $wallet->balance -= $amount;
            $wallet->save();

            // Log transaction
            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'payout',
                'transaction_id' => $response['txid'],
            ]);

            return response()->json(['message' => 'Payout successful', 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to process payout', 'details' => $e->getMessage()], 500);
        }
    }
}
