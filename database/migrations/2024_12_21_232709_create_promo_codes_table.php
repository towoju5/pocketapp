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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('promo_title');
            $table->string('promo_code');
            $table->string('promo_discount');
            $table->foreignId('promo_created_by')->constrained('users')->onDelete('cascade');
            $table->enum('promo_discount_type', ['flat', 'percentage'])->default('flat');
            $table->string('promo_start_date_time');
            $table->string('promo_ends_date_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
