<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('bitgos');
        Schema::create('bitgos', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_id')->unique()->nullable();
            $table->string('wallet_name')->nullable();
            $table->string('wallet_ticker')->nullable();
            $table->string('type')->nullable();
            $table->string('network')->nullable();
            $table->boolean('require_memo')->default(false);
            $table->boolean('can_deposit')->default(false);
            $table->boolean('can_payout')->default(false);
            $table->string('coin_logo')->nullable();
            $table->string('min_deposit')->default(30);
            $table->string('min_payout')->default(30);
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitgos');
    }
};
