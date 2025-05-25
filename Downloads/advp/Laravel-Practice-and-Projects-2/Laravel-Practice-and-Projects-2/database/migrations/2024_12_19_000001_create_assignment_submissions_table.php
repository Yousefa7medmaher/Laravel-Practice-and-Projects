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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('submission_text')->nullable(); // For text submissions
            $table->json('submission_data')->nullable(); // For additional data (links, etc.)
            $table->enum('status', ['draft', 'submitted', 'graded', 'returned'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('grade', 5, 2)->nullable(); // Grade out of max_score
            $table->text('feedback')->nullable(); // Instructor feedback
            $table->foreignId('graded_by')->nullable()->constrained('users'); // Instructor who graded
            $table->timestamp('graded_at')->nullable();
            $table->boolean('is_late')->default(false);
            $table->integer('attempt_number')->default(1); // For resubmissions
            $table->timestamps();

            // Ensure one submission per assignment per user per attempt
            $table->unique(['assignment_id', 'user_id', 'attempt_number'], 'assignment_submission_unique');

            // Index for performance
            $table->index(['user_id', 'status']);
            $table->index(['assignment_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
