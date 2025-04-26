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
        Schema::dropIfExists('assets');
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('name');
            $table->string('display_symbol');
            $table->string('asset_group');
            $table->string('yahoo_ticker')->comment('Used to generate inital chart and the acutal trade data');
            $table->string('olymptrade_ticker')->comment('To generate the live chart data');
            $table->enum('exchange_float_type', ['fixed', 'float', 'combine']);
            $table->string('exchange_float');
            $table->string('asset_profit_margin')->default(0.9);
            $table->json('extra_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.saaaaaa
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
