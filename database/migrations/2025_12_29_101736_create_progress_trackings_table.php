<?php
// database/migrations/xxxx_create_progress_trackings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent')->default(0); // dalam detik
            $table->timestamps();
            
            // Satu user hanya bisa memiliki satu progress per material
            $table->unique(['user_id', 'course_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_trackings');
    }
};