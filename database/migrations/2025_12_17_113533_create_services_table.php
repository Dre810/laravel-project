<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // e.g., "Haircut", "Massage"
            $table->text('description')->nullable();  // Optional description
            $table->integer('duration');  // Duration in minutes
            $table->decimal('price', 8, 2);  // Price with 2 decimal places
            $table->string('category')->nullable();  // e.g., "Hair", "Spa", "Medical"
            $table->boolean('is_active')->default(true);  // Active or inactive service
            $table->timestamps();  // Creates created_at and updated_at columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};