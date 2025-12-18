<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();  // Each staff has unique email
            $table->string('phone')->nullable();
            $table->string('specialty')->nullable();  // e.g., "Senior Barber", "Head Therapist"
            $table->text('bio')->nullable();  // Short description about staff
            $table->string('photo_url')->nullable();  // Profile picture URL
            $table->boolean('is_active')->default(true);  // Active or inactive staff
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};