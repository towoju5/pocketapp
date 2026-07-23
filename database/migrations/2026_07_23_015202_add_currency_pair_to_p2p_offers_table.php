<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Offers used to expose a single free-text `currency` field (the
     * counter-currency, e.g. "NGN") plus a `wallet_slug` the maker typed in
     * by hand to pick which of their own app wallets got escrowed — a raw
     * "wallet_slug" text input was literally shown to customers on the
     * create-offer form. This replaces both with an explicit sell/buy
     * currency pair (USD, USDT, USDC, ...) chosen from a fixed list; escrow
     * still settles from the maker's real-money wallet under the hood
     * (wallet_slug stays, just no longer user-facing — see
     * P2pOfferController::store()). The old `currency` column is kept
     * as-is (not dropped) since existing rows/history still reference it.
     */
    public function up(): void
    {
        Schema::table('p2p_offers', function (Blueprint $table) {
            $table->string('sell_currency')->nullable()->after('currency');
            $table->string('buy_currency')->nullable()->after('sell_currency');
        });

        // Every existing offer's escrowed asset was always a USD wallet
        // (qt_real_usd/qt_demo_usd); the old `currency` column was what the
        // maker accepted/paid in return.
        DB::table('p2p_offers')->update([
            'sell_currency' => 'USD',
            'buy_currency' => DB::raw('currency'),
        ]);
    }

    public function down(): void
    {
        Schema::table('p2p_offers', function (Blueprint $table) {
            $table->dropColumn(['sell_currency', 'buy_currency']);
        });
    }
};
