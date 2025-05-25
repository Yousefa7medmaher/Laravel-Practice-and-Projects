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
        Schema::create('lecture_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('progress_percentage')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('time_spent')->default(0); // in minutes
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate progress records
            $table->unique(['lecture_id', 'user_id']);
            
            // Indexes
            $table->index(['user_id', 'completed']);
            $table->index('last_accessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_progress');
    }
};
