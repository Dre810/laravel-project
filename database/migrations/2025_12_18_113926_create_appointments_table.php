<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Appointment details
            $table->date('date');  // Appointment date
            $table->time('start_time');  // Start time (e.g., 14:30:00)
            $table->time('end_time');  // End time (calculated from service duration)
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();  // Client notes
            $table->boolean('reminder_sent')->default(false);  // Email reminder sent?
            
            $table->timestamps();
            $table->softDeletes();  // Allows us to "soft delete" appointments
            
            // Important: Prevent double-booking for same staff at same time
            $table->unique(['staff_id', 'date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};