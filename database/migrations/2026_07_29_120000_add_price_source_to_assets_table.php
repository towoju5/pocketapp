<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tags every asset with which live-price pipeline actually serves it —
 * 'iqcent' (PriceFeedService, the main dashboard's headless-Chrome
 * collector) or 'brokeret' (BrokeretFeedService, base_url/ui's direct WS
 * feed). These are two fully independent pipelines using different symbol
 * naming conventions ("EUR/USD.X" vs "EURUSD") that were previously told
 * apart implicitly by trying PriceFeedService first and falling back to
 * BrokeretFeedService on a miss (see TradeController::placeTrade) — this
 * makes that explicit instead of inferred, and lets the asset catalog
 * (admin list, etc) actually say where a price comes from.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('price_source')->default('iqcent')->after('is_otc');
        });

        // The one Brokeret-sourced row that predates this column (see
        // add_gold_asset_for_brokeret_ui) — every asset since is tagged at
        // insert time (AssetSeeder for iqcent, BrokeretFeedService for
        // anything it auto-registers).
        DB::table('assets')->where('symbol', 'XAUUSD')->update(['price_source' => 'brokeret']);
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('price_source');
        });
    }
};
