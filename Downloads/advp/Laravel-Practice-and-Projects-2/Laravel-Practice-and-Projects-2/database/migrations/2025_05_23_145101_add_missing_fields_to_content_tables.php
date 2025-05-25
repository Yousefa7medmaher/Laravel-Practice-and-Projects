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
        // Add missing fields to lectures table
        Schema::table('lectures', function (Blueprint $table) {
            if (!Schema::hasColumn('lectures', 'objectives')) {
                $table->text('objectives')->nullable()->after('content');
            }
            if (!Schema::hasColumn('lectures', 'scheduled_date')) {
                $table->datetime('scheduled_date')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('lectures', 'is_visible')) {
                $table->boolean('is_visible')->default(true)->after('is_published');
            }
            if (!Schema::hasColumn('lectures', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('is_visible');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('lectures', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Add missing fields to quizzes table
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'instructions')) {
                $table->text('instructions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('quizzes', 'max_attempts')) {
                $table->string('max_attempts')->default('3')->after('max_score');
            }
            if (!Schema::hasColumn('quizzes', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('is_published');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('quizzes', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Add missing fields to labs table
        Schema::table('labs', function (Blueprint $table) {
            if (!Schema::hasColumn('labs', 'equipment')) {
                $table->string('equipment')->nullable()->after('max_score');
            }
            if (!Schema::hasColumn('labs', 'duration')) {
                $table->integer('duration')->default(120)->after('equipment'); // minutes
            }
            if (!Schema::hasColumn('labs', 'allow_late_submission')) {
                $table->boolean('allow_late_submission')->default(false)->after('duration');
            }
            if (!Schema::hasColumn('labs', 'is_visible')) {
                $table->boolean('is_visible')->default(true)->after('allow_late_submission');
            }
            if (!Schema::hasColumn('labs', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('is_visible');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('labs', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Add missing fields to materials table
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'material_type')) {
                $table->string('material_type')->default('other')->after('description');
            }
            if (!Schema::hasColumn('materials', 'is_visible')) {
                $table->boolean('is_visible')->default(true)->after('is_downloadable');
            }
            if (!Schema::hasColumn('materials', 'uploaded_by')) {
                $table->unsignedBigInteger('uploaded_by')->nullable()->after('order');
                $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('materials', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('uploaded_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added fields from lectures table
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['objectives', 'scheduled_date', 'is_visible', 'created_by', 'updated_by']);
        });

        // Remove added fields from quizzes table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['instructions', 'max_attempts', 'created_by', 'updated_by']);
        });

        // Remove added fields from labs table
        Schema::table('labs', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['equipment', 'duration', 'allow_late_submission', 'is_visible', 'created_by', 'updated_by']);
        });

        // Remove added fields from materials table
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['material_type', 'is_visible', 'uploaded_by', 'updated_by']);
        });
    }
};
