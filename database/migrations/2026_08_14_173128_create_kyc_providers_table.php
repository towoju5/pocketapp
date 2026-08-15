<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mirrors payment_providers: one row per identity-verification method
 * (manual, didit, sumsub, persona), admin-managed credentials encrypted at
 * rest via Eloquent's encrypted:array cast. Unlike payment providers,
 * exactly one row is ever is_active at a time — Admin\KycProviderController
 * enforces that on update() by deactivating the rest, since a platform
 * verifies identity against a single standard at once, not per-transaction
 * like a payment gateway. KycController::create() reads whichever row is
 * active to decide what to show the user.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_providers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('display_name');
            $table->boolean('is_active')->default(false);
            $table->text('credentials')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_providers');
    }
};
