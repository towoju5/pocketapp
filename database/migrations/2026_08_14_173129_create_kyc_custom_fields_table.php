<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-defined extra fields shown on the manual KYC submission form,
 * alongside the fixed document_type/front/back/selfie fields — e.g. full
 * legal name, date of birth, address, a second document upload. Answers are
 * stored in kyc_verifications.custom_field_values (JSON, keyed by
 * field_key), not as their own columns, so adding/removing a field never
 * needs a migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            // Storage key inside kyc_verifications.custom_field_values —
            // stable even if the admin later edits the label.
            $table->string('field_key')->unique();
            $table->string('field_type'); // text|textarea|date|select|file|checkbox|number
            // Newline/JSON option list for field_type=select; unused otherwise.
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_custom_fields');
    }
};
