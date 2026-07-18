<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('level');
            $table->string('activity_type');
            $table->nullableMorphs('commissionable');
            $table->decimal('base_amount', 20, 2);
            $table->decimal('percentage', 5, 2);
            $table->decimal('commission_amount', 20, 2);
            $table->string('wallet_slug');
            $table->timestamps();

            $table->index('beneficiary_id');
            $table->index('referred_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
