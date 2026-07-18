<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->decimal('stake_amount', 20, 2);
            $table->decimal('roi_percentage', 8, 2);
            $table->decimal('expected_payout', 20, 2);
            $table->string('wallet_slug');
            $table->timestamp('starts_at');
            $table->timestamp('matures_at');
            $table->string('status')->default('active');
            $table->unsignedInteger('reinvest_count')->default(0);
            $table->foreignId('parent_subscription_id')->nullable()->constrained('plan_subscriptions')->nullOnDelete();
            $table->timestamp('payout_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'matures_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_subscriptions');
    }
};
