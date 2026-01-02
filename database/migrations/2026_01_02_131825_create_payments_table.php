<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('stripe_payment_id')->unique();
            $table->decimal('amount', 10, 2); // In dollars
            $table->string('currency')->default('usd');
            $table->string('status'); // pending, succeeded, failed, refunded
            $table->json('stripe_response')->nullable(); // Full Stripe response
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['user_id', 'status']);
            $table->index(['appointment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};