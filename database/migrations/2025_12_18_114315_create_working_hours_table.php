<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to staff
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            
            // Day of week (0=Sunday, 1=Monday, etc. OR use string)
            $table->string('day_of_week');  // 'monday', 'tuesday', etc.
            
            // Working hours for that day
            $table->time('start_time')->nullable();  // NULL means not working that day
            $table->time('end_time')->nullable();
            
            // Break times (optional)
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            
            // Is working this day?
            $table->boolean('is_working')->default(true);
            
            $table->timestamps();
            
            // One staff can have only one entry per day
            $table->unique(['staff_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_hours');
    }
};