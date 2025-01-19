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
            $table->string('wallet_id');
            $table->string('address');
            $table->string('coin_label');
            $table->string('address_id');
            $table->string('wallet_network');
            $table->string('coin_ticker');
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique('address');
            $table->unique('address_id');
            $table->index('coin_ticker');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitgo_wallets');
    }
};
