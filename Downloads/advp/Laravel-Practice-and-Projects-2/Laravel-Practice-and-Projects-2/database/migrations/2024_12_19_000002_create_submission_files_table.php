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
        Schema::create('submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade');
            $table->string('original_name'); // Original filename
            $table->string('stored_name'); // Stored filename (with timestamp/hash)
            $table->string('file_path'); // Full path to file
            $table->string('mime_type'); // File MIME type
            $table->bigInteger('file_size'); // File size in bytes
            $table->string('file_extension', 10); // File extension
            $table->boolean('is_primary')->default(false); // Main submission file
            $table->integer('order')->default(0); // File order for multiple files
            $table->timestamps();

            // Index for performance
            $table->index(['submission_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_files');
    }
};
