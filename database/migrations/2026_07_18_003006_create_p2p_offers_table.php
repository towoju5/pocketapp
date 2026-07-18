<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p2p_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // buy|sell
            $table->string('wallet_slug');
            $table->string('currency');
            $table->decimal('price_per_unit', 20, 2);
            $table->decimal('min_limit', 20, 2);
            $table->decimal('max_limit', 20, 2);
            $table->decimal('available_amount', 20, 2);
            $table->json('payment_methods')->nullable();
            $table->text('terms')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status', 'currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p2p_offers');
    }
};
