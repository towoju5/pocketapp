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
        Schema::create('cashback_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['loss', 'volume', 'promo']);
            $table->decimal('percentage', 5, 2)->default(0); // e.g., 10.00 for 10%
            $table->integer('volume_threshold')->nullable(); // for volume-based
            $table->string('promo_code')->nullable(); // for promo-based
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashback_rules');
    }
};
