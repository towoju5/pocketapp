<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `assets.symbol` never had a unique constraint, which silently defeats
 * every insertOrIgnore() this app does against it (BrokeretFeedService's
 * per-symbol auto-registration, the Gold migration, the new Brokeret asset
 * seeder) — with nothing to conflict against, "insert or ignore" just
 * inserts every time. Harmless for a one-shot migration, but
 * BrokeretFeedService::ensureAssetRegistered() runs on every process start
 * (its in-memory dedupe guard resets on restart) and StreamBrokeretFeed
 * restarts routinely, so left alone this would accumulate duplicate rows
 * per symbol over the collector's lifetime.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->unique('symbol');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropUnique(['symbol']);
        });
    }
};
