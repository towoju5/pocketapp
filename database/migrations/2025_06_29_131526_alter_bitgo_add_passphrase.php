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
        Schema::table('bitgos', function (Blueprint $table) {
            $table->string('passphrase')->default('1234567890')->comment('wallet passphrase on bitgo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitgos', function (Blueprint $table) {
            //
        });
    }
};
