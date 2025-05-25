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
        // Add instructor_id to lectures table
        Schema::table('lectures', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('course_id')->constrained('users')->onDelete('set null');
            $table->index(['instructor_id', 'course_id']);
        });

        // Add instructor_id to assignments table
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('course_id')->constrained('users')->onDelete('set null');
            $table->index(['instructor_id', 'course_id']);
        });

        // Add instructor_id to quizzes table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('course_id')->constrained('users')->onDelete('set null');
            $table->index(['instructor_id', 'course_id']);
        });

        // Add instructor_id to labs table
        Schema::table('labs', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('course_id')->constrained('users')->onDelete('set null');
            $table->index(['instructor_id', 'course_id']);
        });

        // Add instructor_id to materials table
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('course_id')->constrained('users')->onDelete('set null');
            $table->index(['instructor_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropIndex(['instructor_id', 'course_id']);
            $table->dropColumn('instructor_id');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropIndex(['instructor_id', 'course_id']);
            $table->dropColumn('instructor_id');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropIndex(['instructor_id', 'course_id']);
            $table->dropColumn('instructor_id');
        });

        Schema::table('labs', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropIndex(['instructor_id', 'course_id']);
            $table->dropColumn('instructor_id');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropIndex(['instructor_id', 'course_id']);
            $table->dropColumn('instructor_id');
        });
    }
};
