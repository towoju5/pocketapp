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
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('display_name');
            $table->string('logo_url')->nullable();
            $table->string('type'); // fiat | crypto
            $table->boolean('is_active')->default(false);
            $table->boolean('can_deposit')->default(true);
            $table->boolean('can_payout')->default(false);
            $table->string('min_deposit')->nullable();
            $table->string('max_deposit')->nullable();
            $table->string('min_payout')->nullable();
            $table->string('max_payout')->nullable();
            // Encrypted at rest via Eloquent's encrypted:array cast (APP_KEY) —
            // admin-managed credentials never touch .env for these gateways.
            $table->text('credentials')->nullable();
            $table->json('config')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_providers');
    }
};
