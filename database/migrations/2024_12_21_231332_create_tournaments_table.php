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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('tournament_title');
            $table->string('tournament_participation_fee');
            $table->string('tournament_starting_balance');
            $table->string('tournament_start_date_time');
            $table->string('tournament_rebuy_fee');
            $table->string('tournament_reward');
            $table->string('tournament_rules');
            $table->json('tournament_extra_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
