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
        Schema::dropIfExists('express_trades');
        Schema::create('express_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('trade_group_id');
            $table->enum('trade_direction', ['up', 'down'])->default('up')->comment('up means buy and down means sell');
            $table->string('trade_type')->default('express_trade');
            $table->string('trade_amount');
            $table->string('trade_close_time');
            $table->string('trade_currency')->nullable();
            $table->string('end_price')->nullable();
            $table->string('trade_wallet')->nullable();
            $table->string('start_price');
            $table->string('trade_profit')->nullable();
            $table->json('trade_extra_info')->nullable();
            $table->string('trade_status')->default('open');
            $table->string('trade_copied_count')->default(0);
            $table->string('trade_percentage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('express_trades');
    }
};
