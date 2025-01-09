<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('bitgo_wallets');
        Schema::create('bitgo_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('bitgos')->onDelete('cascade');
            $table->string('address');
            $table->string('coin_ticker');
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique('address');
            $table->index('coin_ticker');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitgo_wallets');
    }
};
