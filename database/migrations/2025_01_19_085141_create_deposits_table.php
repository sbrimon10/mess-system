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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assumes each deposit is linked to a user
            $table->decimal('amount', 10, 2); // Amount deposited
            $table->timestamp('deposited_at')->nullable(); // Date and time of deposit
            $table->string('payment_method')->nullable(); // Payment method (e.g., credit card, bank transfer)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the deposit
            $table->timestamp('approved_at')->nullable(); // Timestamp for when the deposit is approved
            $table->foreignId('admin_approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who approved the deposit
            $table->text('rejection_comment')->nullable(); // Comment when rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
