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
        Schema::table('deposits', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the deposit
    $table->timestamp('approved_at')->nullable(); // Timestamp for when the deposit is approved
    $table->foreignId('admin_approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who approved the deposit
    $table->text('rejection_comment')->nullable(); // Comment when rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the deposit
            $table->timestamp('approved_at')->nullable(); // Timestamp for when the deposit is approved
            $table->foreignId('admin_approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who approved the deposit
            $table->text('rejection_comment')->nullable(); // Comment when rejected
        });
    }
};
