<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p2p_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('p2p_offers')->cascadeOnDelete();
            $table->foreignId('maker_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('taker_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 20, 2);
            $table->decimal('fiat_amount', 20, 2);
            $table->string('wallet_slug');
            $table->string('payment_proof_path')->nullable();
            $table->string('status')->default('pending_payment')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('dispute_reason')->nullable();
            $table->foreignId('disputed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disputed_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('payment_deadline');
            $table->timestamps();

            $table->index('maker_id');
            $table->index('taker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p2p_trades');
    }
};
