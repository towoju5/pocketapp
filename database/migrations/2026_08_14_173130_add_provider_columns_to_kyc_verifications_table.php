<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            // Which kyc_providers row this submission went through — kept
            // even if the admin later switches the active provider, so
            // historical submissions still show how they were verified.
            $table->string('provider')->default('manual')->after('user_id');
            // External applicant/session/inquiry id from a hosted provider
            // (Didit/Sumsub/Persona) — how its webhook finds this row back.
            // Null for manual submissions.
            $table->string('provider_reference')->nullable()->after('provider');
            $table->json('custom_field_values')->nullable()->after('selfie_path');
        });

        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->index(['provider', 'provider_reference']);
        });

        // document_type stays NOT NULL — hosted providers (Didit/Sumsub/
        // Persona) don't collect a document_type the way the manual form
        // does, so KycController::startHosted() writes the provider's slug
        // in there instead of leaving it empty. Simpler than making the
        // column nullable, which would need doctrine/dbal (not installed —
        // see fix_users_avatar_default_column's docblock for the same
        // constraint) to ALTER COLUMN on MySQL, and SQLite can't ALTER
        // COLUMN at all without a full table rebuild.
    }

    public function down(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->dropIndex(['provider', 'provider_reference']);
            $table->dropColumn(['provider', 'provider_reference', 'custom_field_values']);
        });
    }
};
