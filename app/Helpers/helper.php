<?php

use App\Models\Assets;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

if (!function_exists('get_assets')) {
    function get_assets()
    {
        $assets = Assets::all();
        return $assets;
    }
}

if (!function_exists('tableExists')) {
    function tableExists($tableName)
    {
        // Method 1: Using Schema facade
        return Schema::hasTable($tableName);
    }
}

if (!function_exists('_floatAmount')) {
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

if (!function_exists('create_user_wallet')) {
    /**
     * Create all user wallets balance
     * @param mixed $userId - optional
     * @return void
     */
    function create_user_wallet($userId = null)
    {
        $user = User::findOrFail($userId ?? auth()->id());
        if($user) {
            $_wallets = [
                [
                    "name" => "QT Real USD",
                    "symbol" => "qt_real_usd"
                ],
                [
                    "name" => "QT Demo USD",
                    "symbol" => "qt_demo_usd"
                ],
                [
                    "name" => "MT4 Demo USD",
                    "symbol" => "mt4_demo_usd"
                ],
                [
                    "name" => "MT4 Real USD",
                    "symbol" => "mt4_real_usd"
                ],
                [
                    "name" => "MT5 Demo USD",
                    "symbol" => "mt5_demo_usd"
                ],
                [
                    "name" => "MT5 Real USD",
                    "symbol" => "mt5_real_usd"
                ],
                [
                    "name" => "Shares Real USD",
                    "symbol" => "sh_real_usd"
                ],
                [
                    "name" => "Shares Demo USD",
                    "symbol" => "sh_demo_usd"
                ],
            ];
            
            foreach($_wallets as $k => $w) {
                if(!$user->hasWallet($w['symbol'])) {
                    $balance = 0;
                    $wallet = $user->createWallet([
                        'name' => $w['name'],
                        'slug' => $w['symbol'],
                        'balance' => $balance
                    ]);
                    if(str_contains($w['symbol'], 'demo')) {
                        $wallet->deposit(_floatAmount(10000));
                    }
                }
            }
        }
    }
}

if (!function_exists('checkMultipleTables')) {

    function checkMultipleTables(array $tableNames)
    {
        // Method 2: Using DB facade to check multiple tables
        $existingTables = DB::select("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = ?
            AND table_name IN ('" . implode("','", $tableNames) . "')",
            [env('DB_DATABASE')]
        );

        return collect($existingTables)->pluck('table_name')->toArray();
    }
}


if(!function_exists('fetchPreChartData')) {
    function fetchPreChartData($cryptoPair, $isRate = false)
    {
        // External API URL
        $apiUrl = "https://plus.olymptrade.com/api/v1/assets/pair?locale=en_US&pair={$cryptoPair}";

        try {
            $response = Http::get($apiUrl);
            // if ($response->successful()) {
                $resp = $response->json();
                if(isset($resp['charts'][0]['candles'])) {
                    if($isRate) return $resp['rate'];
                    $data = $resp['charts'][0]['candles'];
                    return response()->json($data);
                }
            // }

            // Handle failed response
            return response()->json(['error' => $resp]);
        } catch (\Exception $e) {
            // Catch any exceptions and return an error
            Log::error('Error fetching data from API: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
