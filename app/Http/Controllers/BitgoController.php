<?php
namespace App\Http\Controllers;

use App\Models\BitgoWallets;
use App\Models\Payout;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WebhookLog;
use BitGoSDK\BitGo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use neto737\BitGoSDK\BitGoExpress;
use neto737\BitGoSDK\BitGoSDK;

class BitgoController extends Controller
{
    private $bitgo;

    public function __construct()
    {
        $this->bitgo = new BitGoExpress(hostname: 'localhost', port: 3080, coin: request()->coin ?? 'btc');
    }

    // Generate a new wallet
    public function generateWallet(Request $request)
    {
        $walletName = $request->input('wallet_ticker');
        $passphrase = config('bitgo.passphrase');

        try {
            $wallet = $this->bitgo->wallet()->create([
                'label'      => $walletName,
                'passphrase' => $passphrase,
            ]);

            \App\Models\Bitgo::create([
                'wallet_id'     => $wallet['id'],
                'wallet_name'   => $walletName,
                'wallet_ticker' => $wallet['coin'],
                'type'          => $wallet['type'],
                'require_memo'  => $wallet['coin'] === 'xrp' || $wallet['coin'] === 'xlm',
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
            if (! auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $wallet = BitgoWallets::where([
                'user_id'     => auth()->id(),
                'coin_ticker' => $coin,
            ])->first();

            // If wallet does not exist, create one
            if (! $wallet) {
                $wallet = bitgoDepositAddress($coin);

                // Optional: validate the result is a BitgoWallets model
                if (! $wallet || ! ($wallet instanceof BitgoWallets)) {
                    throw new \Exception('bitgoDepositAddress did not return a valid wallet model.');
                }
            }

            return $wallet->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get or generate Bitgo wallet', [
                'coin'    => $coin,
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => 'Failed to generate or fetch wallet address',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    // Handle deposit webhook
    // public function depositWebhook(Request $request)
    // {
    //     try {
    //         // Read raw payload
    //         $rawInput = file_get_contents('php://input');
    //         $payload  = json_decode($rawInput, true);

    //         Log::debug('📥 Bitgo Webhook Received', [
    //             'headers'   => $request->headers->all(),
    //             'raw_input' => $rawInput,
    //             'payload'   => $payload,
    //         ]);

    //         // Validate payload type and state
    //         if (
    //             ($payload['type'] ?? null) !== 'transfer' ||
    //             ($payload['transferType'] ?? null) !== 'receive' ||
    //             ($payload['state'] ?? null) !== 'confirmed'
    //         ) {
    //             Log::info('Webhook ignored — not a confirmed receive transfer');
    //             return response()->json(['message' => 'Ignored webhook'], 200);
    //         }

    //         // Extract values
    //         $bitgoWalletId   = $payload['wallet'];
    //         $transactionHash = $payload['hash'];
    //         $amountSatoshis  = $payload['value'] ?? 0;
    //         $amountBTC       = $amountSatoshis / 100_000_000;

    //         // Locate local Bitgo wallet
    //         $bitgoWallet = BitgoWallets::where('bitgo_id', $bitgoWalletId)->first();
    //         if (! $bitgoWallet) {
    //             Log::warning('Wallet not found for Bitgo ID', ['bitgo_id' => $bitgoWalletId]);
    //             return response()->json(['error' => 'Wallet not found'], 404);
    //         }

    //         $user = $bitgoWallet->user;
    //         if (! $user) {
    //             Log::warning('No user linked to wallet', ['wallet_id' => $bitgoWallet->id]);
    //             return response()->json(['error' => 'User not found'], 404);
    //         }

    //         $wallet = $user->wallet;

    //         // Idempotency check — prevent double credit
    //         $existing = $wallet->transactions()
    //             ->where('meta->transaction_hash', $transactionHash)
    //             ->where('type', 'deposit')
    //             ->first();

    //         if ($existing) {
    //             Log::info('Duplicate transaction skipped', ['hash' => $transactionHash]);
    //             return response()->json(['message' => 'Duplicate transaction ignored'], 200);
    //         }

    //         DB::transaction(function () use (
    //             $wallet,
    //             $amountBTC,
    //             $transactionHash,
    //             $user,
    //             $bitgoWallet,
    //             $payload
    //         ) {
    //             // Perform deposit and capture Bavix transaction
    //             $transaction = $wallet->deposit($amountBTC, [
    //                 'description'      => 'Bitgo deposit',
    //                 'transaction_hash' => $transactionHash,
    //             ]);

    //             // Log to transaction_histories table
    //             TransactionHistory::create([
    //                 'user_id'                => $user->id,
    //                 'transaction_type'       => 'deposit',
    //                 'transaction_type_id'    => $transactionHash,
    //                 'transaction_amount'     => (string) $amountBTC,
    //                 'transaction_extra_info' => [
    //                     'wallet_id'       => $wallet->id,
    //                     'bitgo_wallet_id' => $bitgoWallet->bitgo_id,
    //                     'coin'            => $payload['coin'] ?? null,
    //                     'tx_fee'          => $payload['feeString'] ?? null,
    //                     'simulation'      => $payload['simulation'] ?? false,
    //                     'raw_payload'     => $payload,
    //                 ],
    //             ]);

    //             Log::info('✅ Deposit and transaction history recorded', [
    //                 'user_id'          => $user->id,
    //                 'wallet_id'        => $wallet->id,
    //                 'tx_hash'          => $transactionHash,
    //                 'amount'           => $amountBTC,
    //                 'transaction_uuid' => $transaction->uuid,
    //             ]);
    //         });

    //         return response()->json(['message' => 'Deposit processed']);
    //     } catch (\Exception $e) {
    //         Log::error('🚨 Error handling Bitgo webhook', [
    //             'message' => $e->getMessage(),
    //             'trace'   => $e->getTrace(),
    //         ]);
    //         return response()->json(['error' => 'Internal server error'], 500);
    //     }
    // }

    // // Payout
    // public function payout(Request $request, $walletId)
    // {
    //     $wallet = ModelsBitgo::where('wallet_id', $walletId)->first();

    //     if (! $wallet) {
    //         return response()->json(['error' => 'Wallet not found'], 404);
    //     }

    //     $address    = $request->input('address');
    //     $amount     = $request->input('amount');
    //     $passphrase = config('bitgo.passphrase');

    //     try {
    //         $response = $this->bitgo->wallet()->get($walletId)->sendCoins([
    //             'address'          => $address,
    //             'amount'           => $amount,
    //             'walletPassphrase' => $passphrase,
    //         ]);

    //         $wallet->balance -= $amount;
    //         $wallet->save();

    //         // Log transaction
    //         $wallet->transactions()->create([
    //             'amount'         => $amount,
    //             'type'           => 'payout',
    //             'transaction_id' => $response['txid'],
    //         ]);

    //         return response()->json(['message' => 'Payout successful', 'data' => $response]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to process payout', 'details' => $e->getMessage()], 500);
    //     }
    // }

    public function withdrawalRequest(Request $request)
    {
        try {
            // Validate the request inputs
            $validate = $request->validate([
                'amount'         => 'required|numeric',
                'coin'           => 'required|string',
                'wallet_address' => 'required|string',
            ]);

            $minimum_payout = get_option('bitgo_minimum_payout');
            $maximum_payout = get_option('bitgo_maximum_payout');
            $fixedFee       = get_option('bitgo_fixed_withdrawal_charges');
            $floatFee       = get_option('bitgo_float_withdrawal_charges');

            // Calculate the percentage fee
            $percentageFee = ($request->amount * $floatFee) / 100;

            // Calculate the total fee
            $totalFee = $fixedFee + $percentageFee;

            $maxDailyWithdrawal     = get_option('bitgo_maximum_daily_payout');
            $totalAlreadyWithdrawed = TransactionHistory::whereUserId(auth()->id())->where('trx_type', 'Withdraw')->where('created_at', Carbon::today())->sum('amount');

            if ($totalAlreadyWithdrawed >= $maxDailyWithdrawal) {
                return back()->with('error', "Sorry you have reached your daily withdrawal limit");
            }

            if (($totalAlreadyWithdrawed + $request->amount) >= $maxDailyWithdrawal) {
                $todoBalance = floatval($maxDailyWithdrawal - $totalAlreadyWithdrawed);
                return back()->with('error', "This transaction is above your daily limit, please updated withdrawal amount to: {$todoBalance}");
            }

            if ($request->amount < $minimum_payout) {
                return back()->with('error', "Withdrawal amount can not be less than $minimum_payout");
            }

            if ($request->amount > $maximum_payout) {
                return back()->with('error', "Withdrawal amount can not be higher than $maximum_payout");
            }
            $user = $request->user();
            // Get the balance of balance
            $wallets = $user->getWallet($request->debit_wallet);
            // echo get_option('bitgo_sweep_coins_rate') * $request->amount; exit;
            $withdrawable = get_option('bitgo_sweep_coins_rate') * $request->amount;

            // Check if the requested amount exceeds the withdrawable amount
            if ($request->amount > $wallets->quantity) {
                return back()->with('error', 'Insufficient sweepcoin balance');
            }

            // Charge user sweep_coins wallet balance
            if (! debit_user(wallet_slug: $request->debit_wallet, amount: floatval($request->amount + $totalFee), type: 'debit', userId: auth()->id(), sweep_coin: $wallets->id)) {
                return back()->with('error', 'You do not have sufficient balance to complete this transaction.');
            }
            $coin     = $request->coin;
            $walletId = getWalletByCoin($coin);

            $btcAmount     = self::coinToUsd($withdrawable, strtoupper($coin));
            $satoshiAmount = BitGoSDK::toSatoshi($btcAmount);

            // Send coins using the session token
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . getenv('BITGO_API_KEY'),
            ])->post("http://localhost:3080/api/v2/{$coin}/wallet/{$walletId}/sendcoins", [
                'amount'           => $satoshiAmount,
                'address'          => $request->wallet_address,
                'walletPassphrase' => getWalletPhraseByCoin($coin),
            ]);

            $bitgoResult = (array) $response->json();
            Log::info(json_encode(['payout' => $bitgoResult]));

            // Check if the request was successful
            $transaction               = new TransactionHistory();
            $transaction->user_id      = auth()->id();
            $transaction->amount       = $request->amount;
            $transaction->charge       = $totalFee;
            $transaction->post_balance = $wallets->quantity - $request->amount;
            $transaction->trx_type     = 'Withdraw';
            $transaction->details      = 'Coins withdraw';
            $transaction->remark       = 'payout';
            $transaction->wallet_type  = 'sweep_coins';
            if ($response->successful()) {
                $transaction->trx    = $bitgoResult['txid'];
                $transaction->status = 0;
                $transaction->save();
                return back()->with('success', "Withdrawal successful please check your wallet.");
            } elseif ($response->failed()) {
                // refund user the withdrawed fund
                credit_user(wallet_slug: $request->debit_wallet, amount: floatval($request->amount + $totalFee), description: "Refund failed withdrawal request");
                $transaction->trx    = Str::random();
                $transaction->status = 0;
                $transaction->save();
                $resp = $response->json();
                if (isset($resp['name']) && $resp['name'] == "InsufficientBalance") {
                    return back()->with('error', "Sorry we're currently unable to complete your transaction, please contact support with Erorr: 400InsfBal0Bal.");
                }
                return back()->with('error', $response->json()['error'] ?? 'An error occurred');
            } else {
                return back()->with('error', 'Transaction failed, please try again.');
            }
        } catch (\Throwable $th) {
            // Handle any errors
            return back()->with('error', $th->getMessage());
        }
    }

    private function coinToUsd($amount, $coin)
    {
        $coin  = strtoupper($coin);
        $url   = 'https://min-api.cryptocompare.com/data/price';
        $query = [
            'fsym'  => 'USD',
            'tsyms' => $coin,
        ];

        $cacheKey   = 'crypto_price_' . $coin;
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            $data = $cachedData;
        } else {
            try {
                $response = Http::get($url, $query);
                $data     = $response->json();
                Cache::put($cacheKey, $data, 20);
            } catch (\Exception $e) {
                return null;
            }
        }

        if (isset($data[$coin])) {
            return floatval($data[$coin] * $amount);
        }
    }

    public function histories()
    {
        $coin        = request()->coin ?? 'BTC';
        $accessToken = getenv('BITGO_API_KEY');
        $walletId    = getWalletByCoin($coin);
        $url         = "https://www.bitgo.com/api/v2/{$coin}/wallet/{$walletId}/transfer";

        try {
            $response = Http::withToken($accessToken)->get($url);
            if (! $response->failed()) {
                $transferDetails = json_decode($response, true);
                return $transferDetails;
            }
            return false;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function depositWebhook(Request $request)
    {
        $webhook = $request->all();
        if (! isset($webhook['coin'])) {
            abort(403);
        }
        Log::info(json_encode($webhook));

        $payLog = getTransferDetails($webhook['coin'], $webhook['wallet'], $webhook['hash']);
        $baseValue       = $payLog['baseValue'];
        $matchingAddress = null;
        $coin = $webhook['coin'];

        // Search outputs
        foreach ($payLog['outputs'] as $output) {
            if (isset($output['value']) && $output['value'] === $baseValue) {
                $matchingAddress = $output['address'] ?? null;
                break;
            }
        }

        // If not found in outputs, search inputs
        if (! $matchingAddress) {
            foreach ($payLog['inputs'] as $input) {
                if (isset($input['value']) && $input['value'] === $baseValue) {
                    $matchingAddress = $input['address'] ?? null;
                    break;
                }
            }
        }

        $userWallet = BitgoWallets::where('coin_ticker', $webhook['coin'])->where('address', $matchingAddress)->first(); 

        if ($webhook['transferType'] === 'receive') {
            $amount = $payLog['usd'];
            $user   = User::whereId($userWallet->user_id)->first();
            $wallet = $user->getWallet($user->trade_wallet);

            if ($wallet) {
                if ($wallet->deposit($amount, ["description" => "{$coin} wallet deposit"])) {
                    return true;
                }
            }
        }

        // if ($webhook['transferType'] === 'send') {
            // $transaction = Payout::whereTrx($webhook['hash'])->first();
            // if ($transaction) {
            //     $transaction->status = 1;
            //     if ($transaction->save()) {
            //         return response()->json(['status' => 'success'], 200);
            //     }
            // }
        // }


        if ($userWallet) {
            $user = $userWallet->user;
            // Check if the webhook has already been processed
            $existingWebhook = WebhookLog::where('hash', $webhook['hash'])->first();

            if (! $existingWebhook) {
                // Log the webhook
                WebhookLog::create([
                    'user_id'      => $userWallet->user_id,
                    'hash'         => $webhook['hash'],
                    'status'       => $webhook['state'],
                    'coin'         => $webhook['coin'],
                    'receiver'     => $webhook['receiver'],
                    'transferType' => $webhook['transferType'],
                    'metadata'     => (array) $webhook,
                ]);

                Log::info("Processed a BitGo webhook for user ID: {$user->id}", $webhook);
            } else {
                Log::info("Webhook already processed for hash: {$webhook['hash']}");
            }
        } else {
            Log::warning("No user found for BitGo webhook receiver: {$webhook['receiver']}", $webhook);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
