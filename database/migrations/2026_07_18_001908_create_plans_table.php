<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('amount_type')->default('range'); // fixed|range
            $table->decimal('fixed_amount', 20, 2)->nullable();
            $table->decimal('min_amount', 20, 2)->nullable();
            $table->decimal('max_amount', 20, 2)->nullable();
            $table->decimal('roi_percentage', 8, 2);
            $table->unsignedInteger('duration_days');
            $table->boolean('capital_lock')->default(true);
            $table->unsignedInteger('daily_task_limit')->nullable();
            $table->unsignedInteger('max_reinvest_count')->nullable();
            $table->decimal('fee_discount_percentage', 5, 2)->default(0);
            $table->string('badge_color')->nullable();
            $table->string('wallet_slug')->default('qt_real_usd');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
