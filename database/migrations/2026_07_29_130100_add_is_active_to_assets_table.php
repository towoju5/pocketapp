<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets an admin control which rows are actually live/tradable — needed now
 * that `assets` can hold two rows for the same real-world instrument (e.g.
 * iqcent's "EUR/USD.X" and Brokeret's "EURUSD", both tagged via
 * price_source), so there's a way to pick which source is the active one
 * for a given instrument without deleting the other. Every place that
 * decides what's tradable/listed (TradeController, HomeController's OTC
 * list, CollectTicks' ticker-collector batching) is expected to filter on
 * this.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('price_source');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
