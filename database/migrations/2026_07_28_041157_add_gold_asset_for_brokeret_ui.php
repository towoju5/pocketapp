<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds a single Assets row for Gold using Brokeret's own raw symbol
 * ("XAUUSD", no slash) rather than this catalog's usual "XAU/USD"-style
 * naming — base_url/ui's default asset, priced off the independent
 * Brokeret pipeline (see BrokeretFeedService / StreamBrokeretFeed), not
 * PriceFeedService/iqcent. TradeController::placeTrade falls back to
 * BrokeretFeedService when PriceFeedService has no price for a symbol, so
 * this exact string match is what makes that fallback resolve. Deliberately
 * a standalone migration (insertOrIgnore) rather than editing AssetSeeder's
 * $assets array: that seeder deletes and re-inserts the entire table on
 * every run, which would renumber existing rows' auto-increment ids and
 * break ExpressTrade.asset_id foreign keys on any existing express trades.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('assets')->insertOrIgnore([
            'symbol' => 'XAUUSD',
            'name' => 'Gold / US Dollar',
            'asset_group' => 'METAL',
            'exchange_float' => 0,
            'asset_profit_margin' => 0.85,
            'is_otc' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('assets')->where('symbol', 'XAUUSD')->delete();
    }
};
