<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cashback_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashback_rule_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            // Volume payouts: "{Y-m}:{wallet_slug}", one row per user per
            // month per wallet — guards against the scheduled command
            // re-crediting the same month's volume on every run.
            $table->string('period_key')->nullable();
            // Promo payouts: tied to the specific redemption that earned it.
            $table->foreignId('promo_code_redemption_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('amount', 16, 2);
            $table->timestamps();

            $table->unique(['user_id', 'cashback_rule_id', 'period_key']);
            $table->unique(['user_id', 'promo_code_redemption_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashback_payouts');
    }
};
