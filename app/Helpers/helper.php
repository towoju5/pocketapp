<?php

use App\Models\Assets;
use App\Models\Bitgo;
use App\Models\BitgoWallets;
use App\Models\Option;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use neto737\BitGoSDK\BitGoSDK;

if (! function_exists('get_assets')) {
    function get_assets()
    {
        $assets = Assets::all();
        return $assets;
    }
}

if (! function_exists('get_option')) {
    function get_option($key, $default = null)
    {
        $option = Option::where('option_name', $key)->first();
        if ($option) {
            return $option->option_value;
        }
        return $default;
    }
}

if (! function_exists('tableExists')) {
    function tableExists($tableName)
    {
        // Method 1: Using Schema facade
        return Schema::hasTable($tableName);
    }
}

if (! function_exists('_floatAmount')) {
    /**
     * convert amount to float in multiple of 100s
     * @param mixed $amount
     * @return
     */
    function _floatAmount($amount)
    {
        return floatval($amount * 100);
    }
}

if (! function_exists('formatPrice')) {
    /**
     * convert amount to float in multiple of 100s
     * @param mixed $amount
     * @return
     */
    function formatPrice($amount)
    {
        return "$" . number_format($amount, 2);
    }
}

if (! function_exists('create_user_wallet')) {
    /**
     * Create all user wallets balance
     * @param mixed $userId - optional
     * @return void
     */
    function create_user_wallet($userId = null)
    {
        $user = User::findOrFail($userId ?? auth()->id());
        if ($user) {
            $_wallets = allowed_wallets();

            foreach ($_wallets as $k => $w) {
                if (! $user->hasWallet($w['symbol'])) {
                    $balance = 0;
                    $wallet  = $user->createWallet([
                        'name'    => $w['name'],
                        'slug'    => $w['symbol'],
                        'balance' => $balance,
                    ]);
                    if (str_contains($w['symbol'], 'demo')) {
                        $wallet->deposit(_floatAmount(10000));
                    }
                }
            }
        }
    }
}

if (! function_exists('allowed_wallets')) {
    function allowed_wallets()
    {
        return [
            [
                "name"   => "QT Real USD",
                "symbol" => "qt_real_usd",
            ],
            [
                "name"   => "QT Demo USD",
                "symbol" => "qt_demo_usd",
            ],
            [
                "name"   => "MT4 Demo USD",
                "symbol" => "mt4_demo_usd",
            ],
            [
                "name"   => "MT4 Real USD",
                "symbol" => "mt4_real_usd",
            ],
            [
                "name"   => "MT5 Demo USD",
                "symbol" => "mt5_demo_usd",
            ],
            [
                "name"   => "MT5 Real USD",
                "symbol" => "mt5_real_usd",
            ],
            [
                "name"   => "Shares Real USD",
                "symbol" => "sh_real_usd",
            ],
            [
                "name"   => "Shares Demo USD",
                "symbol" => "sh_demo_usd",
            ],
        ];
    }
}

if (! function_exists('createSupportedWalletsForUser')) {
    function createSupportedWalletsForUser(\App\Models\User $user)
    {
        foreach (allowed_wallets() as $walletData) {
            if (! $user->hasWallet($walletData['symbol'])) {
                $user->createWallet([
                    'name' => $walletData['name'],
                    'slug' => $walletData['symbol'],
                ]);
            }
        }
    }
}

if (! function_exists('checkMultipleTables')) {

    function checkMultipleTables(array $tableNames)
    {
        // Method 2: Using DB facade to check multiple tables
        $existingTables = DB::select(
            "
            SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = ?
            AND table_name IN ('" . implode("','", $tableNames) . "')",
            [env('DB_DATABASE')]
        );

        return collect($existingTables)->pluck('table_name')->toArray();
    }
}

if (! function_exists("getAssetData")) {
    function getAssetData($asset, $rateOnly = false)
    {
        try {
            // 1️⃣ Calculate time range
            $nowMs          = round(microtime(true) * 1000);
            $oneMinuteAgoMs = $nowMs - (60 * 1000);

            // 2️⃣ Build query
            $query = http_build_query([
                'symbol' => $asset
            ]);

            Log::info("Query params are: ", [$query]);

            // 3️⃣ Build URLs
            $innerUrl   = "https://iqcent.com/trade-api/api/ticks?{$query}&autoparse=true";
            $encodedUrl = urlencode($innerUrl);
            $zenrowsKey = env('ZENROWS_API_KEY');
            $zenrowsUrl = "https://api.zenrows.com/v1/?apikey={$zenrowsKey}&url={$encodedUrl}";

            // 4️⃣ Log the URL being used
            Log::info('getAssetData using Zenrows URL: ', [$zenrowsUrl]);

            // 5️⃣ Make HTTP request using Laravel's HTTP client
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'Accept'     => 'application/json',
            ])->get($zenrowsUrl);

            // 6️⃣ Check for HTTP errors
            if ($response->failed()) {
                throw new \Exception('HTTP request failed: ' . $response->status());
            }

            // 7️⃣ Log raw response
            Log::info('getAssetData raw response: ' . $response->body());

            // 8️⃣ Decode response
            $arrResponse = $response->json();

            // 9️⃣ Log decoded response
            Log::info('getAssetData decoded response: ', [$arrResponse]);

            // 10️⃣ Basic validation
            if (! is_array($arrResponse) || ! isset($arrResponse['success']) || $arrResponse['success'] !== true) {
                return "Error: Unexpected response format or failed API call.";
            }

            if (empty($arrResponse['data']) || ! isset($arrResponse['data'][0])) {
                return "No data available.";
            }

            // 11️⃣ Return data
            $tick = $arrResponse['data'][0];

            if ($rateOnly) {
                return $tick['strike'] ?? null;
            }

            return $tick;
        } catch (\Exception $e) {
            Log::error('getAssetData exception: ' . $e->getMessage());
            return "Error fetching data: " . $e->getMessage();
        }
    }
}

if (! function_exists('getWalletIdByCoinTicker')) {
    function getWalletIdByCoinTicker($coin)
    {
        $wallet = Bitgo::where('wallet_ticker', $coin)->first();
        if (! $wallet) {
            return (object) ['wallet_id' => null];
        }
        return $wallet->wallet_id;
    }
}

if (! function_exists('getTransferDetails')) {
    function getTransferDetails($coin, $transferId)
    {
        $accessToken = getenv('BITGO_API_KEY');
        $walletId    = getWalletIdByCoinTicker($coin);
        $url         = "https://www.bitgo.com/api/v2/{$coin}/wallet/{$walletId}/transfer/{$transferId}";

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
}

if (! function_exists('bitgoDepositAddress')) {
    function bitgoDepositAddress($coin)
    {
        try {
            $walletId        = getWalletIdByCoinTicker($coin);
            $coin            = strtolower($coin);
            $testNet         = env('BITGO_ENV', 'true');
            $bitgo           = new BitGoSDK(env('BITGO_API_KEY'), $coin, $testNet);
            $bitgo->walletId = $walletId;

            $createAddress = $bitgo->createWalletAddress();
            $createdWallet = [];
            Log::info("Bitgo wallet address generation response", ['response' => $createAddress]);

            $user = auth()->user();
            if (isset($createAddress['address'])) {
                $correctWalletAddress = explode('?', $createAddress['address'])[0] ?? $createAddress['address'];

                $walletData = [
                    "user_id"        => $user->id,
                    "wallet_id"      => $createAddress['wallet'],
                    "address"        => $correctWalletAddress,
                    "coin_ticker"    => $coin,
                    "meta_data"      => $createAddress, // Ensuring meta_data is stored as a JSON string
                    "coin_label"     => "{$coin} Address",
                    "address_id"     => $createAddress['id'],
                    "wallet_network" => $createAddress['chain'],
                ];

                $createdWallet = BitgoWallets::create($walletData);

                if ($createdWallet) {
                    return $createdWallet;
                }
            }
            return $createdWallet;
        } catch (\Exception $e) {
            Log::error("Error generating wallet address", ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
}

if (! function_exists('debit_user')) {
    function debit_user(string $wallet_slug, int | string $amount, string $description): bool
    {
        try {
            $user   = auth()->user();
            $wallet = $user->getWallet($wallet_slug);

            if ($wallet) { // Ensure wallet exists before attempting withdrawal
                if ($wallet->withdraw($amount, ["description" => $description])) {
                    return true;
                }
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

if (! function_exists('credit_user')) {
    function credit_user(string $wallet_slug, int | string $amount, string $description): bool
    {
        $user   = auth()->user();
        $wallet = $user->getWallet($wallet_slug ?? $user->trade_wallet);

        if ($wallet) { // Ensure wallet exists before attempting deposit
            if ($wallet->deposit($amount, ["description" => $description])) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('social_trades')) {
    function social_trades()
    {
        $users = User::select('users.id', 'users.last_name')
            ->withCount('trades')                               // Count total trades per user
            ->withSum('trades as total_spent', 'trade_amount')  // Sum of trade amounts per user
            ->withSum('trades as total_profit', 'trade_profit') // Sum of profit per user
            ->get()
            ->map(function ($user) {
                // Calculate profit margin percentage
                $total_spent   = (float) $user->total_spent;
                $total_profit  = (float) $user->total_profit;
                $profit_margin = $total_spent > 0 ? ($total_profit / $total_spent) * 100 : 0;

                return [
                    'user_id'       => $user->id,
                    'user_name'     => $user->name,
                    'total_trades'  => $user->trades_count,
                    'total_spent'   => $total_spent,
                    'total_profit'  => $total_profit,
                    'profit_margin' => round($profit_margin, 2) . '%',
                ];
            });

        return response()->json($users);
    }
}

if (! function_exists('generate_uuid')) {
    function generate_uuid()
    {
        return \Str::uuid()->toString();
    }
}
