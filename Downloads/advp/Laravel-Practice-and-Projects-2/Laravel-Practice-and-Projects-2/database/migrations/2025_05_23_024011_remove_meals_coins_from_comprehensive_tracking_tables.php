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
        // Remove coins and meals from student_enrollments table
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropColumn(['total_meals_earned', 'total_coins_earned']);
        });

        // Remove coins and meals from grade_history table
        Schema::table('grade_history', function (Blueprint $table) {
            $table->dropColumn(['meals', 'coins']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore coins and meals to student_enrollments table
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->integer('total_meals_earned')->default(0)->after('progress_percentage');
            $table->integer('total_coins_earned')->default(0)->after('total_meals_earned');
        });

        // Restore coins and meals to grade_history table
        Schema::table('grade_history', function (Blueprint $table) {
            $table->integer('meals')->default(0)->after('grade');
            $table->integer('coins')->default(0)->after('meals');
        });
    }
};
