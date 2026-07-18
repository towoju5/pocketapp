<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('proof_url');
            $table->string('status')->default('pending');
            $table->date('submitted_date');
            $table->decimal('reward_amount', 20, 2);
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('credited_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'task_id', 'submitted_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
