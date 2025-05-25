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
        Schema::table('lecture_progress', function (Blueprint $table) {
            $table->boolean('attended')->default(false)->after('completed');
            $table->timestamp('first_accessed_at')->nullable()->after('attended');
            $table->timestamp('completed_at')->nullable()->after('last_accessed_at');
            $table->integer('total_duration')->default(0)->after('time_spent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecture_progress', function (Blueprint $table) {
            $table->dropColumn(['attended', 'first_accessed_at', 'completed_at', 'total_duration']);
        });
    }
};
