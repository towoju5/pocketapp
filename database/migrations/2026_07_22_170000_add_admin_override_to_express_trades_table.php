<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('express_trades', function (Blueprint $table) {
            $table->string('admin_forced_outcome')->nullable()->after('trade_status');
            $table->foreignId('admin_action_by')->nullable()->after('admin_forced_outcome')->constrained('users')->nullOnDelete();
            $table->timestamp('admin_action_at')->nullable()->after('admin_action_by');
            $table->text('admin_action_notes')->nullable()->after('admin_action_at');
        });
    }

    public function down(): void
    {
        Schema::table('express_trades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_action_by');
            $table->dropColumn(['admin_forced_outcome', 'admin_action_at', 'admin_action_notes']);
        });
    }
};
