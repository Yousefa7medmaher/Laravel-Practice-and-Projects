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
        // User Activity Logs - Track all user interactions
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // login, logout, page_visit, content_create, grade_submit, etc.
            $table->string('entity_type')->nullable(); // course, lecture, assignment, quiz, etc.
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('details')->nullable(); // Additional action details
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at');

            $table->index(['user_id', 'action']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
        });

        // Course Assignments - Track instructor-course relationships
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'instructor_id', 'is_active']);
            $table->index(['instructor_id', 'is_active']);
        });

        // Student Enrollments - Enhanced enrollment tracking
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrolled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('enrolled_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('dropped_at')->nullable();
            $table->enum('status', ['enrolled', 'completed', 'dropped', 'suspended'])->default('enrolled');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['student_id', 'course_id']);
            $table->index(['course_id', 'status']);
            $table->index('enrolled_at');
        });

        // Content Progress Tracking
        Schema::create('content_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('content_type'); // lecture, assignment, quiz, lab, material
            $table->unsignedBigInteger('content_id');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'overdue'])->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent_minutes')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['student_id', 'content_type', 'content_id']);
            $table->index(['course_id', 'status']);
            $table->index(['content_type', 'content_id']);
        });

        // Grade History - Complete grading audit trail
        Schema::create('grade_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->decimal('grade', 5, 2);
            $table->text('feedback')->nullable();
            $table->enum('action', ['graded', 'updated', 'deleted'])->default('graded');
            $table->timestamp('graded_at');

            $table->index(['student_id', 'course_id']);
            $table->index(['instructor_id', 'graded_at']);
            $table->index('submission_id');
        });

        // Learning Analytics
        Schema::create('learning_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('total_time_minutes')->default(0);
            $table->integer('lectures_viewed')->default(0);
            $table->integer('assignments_submitted')->default(0);
            $table->integer('quizzes_taken')->default(0);
            $table->integer('labs_completed')->default(0);
            $table->integer('materials_accessed')->default(0);
            $table->decimal('daily_progress', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'date']);
            $table->index(['course_id', 'date']);
        });

        // Course Curriculum
        Schema::create('course_curriculum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->text('syllabus')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('weekly_schedule')->nullable();
            $table->json('assessment_criteria')->nullable();
            $table->json('required_materials')->nullable();
            $table->integer('total_weeks')->default(16);
            $table->integer('total_credit_hours')->default(3);
            $table->timestamps();

            $table->unique('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_curriculum');
        Schema::dropIfExists('learning_analytics');
        Schema::dropIfExists('grade_history');
        Schema::dropIfExists('content_progress');
        Schema::dropIfExists('student_enrollments');
        Schema::dropIfExists('course_assignments');
        Schema::dropIfExists('user_activity_logs');
    }
};
