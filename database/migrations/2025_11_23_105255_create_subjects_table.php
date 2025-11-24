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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            // Name of the course (e.g., "Data Structures")
            $table->string('name');

            // Course code or class identifier (e.g., "ICT 301")
            $table->string('class');

            // Institution Link (Assuming an 'institutions' table exists)
            $table->unsignedSmallInteger('institution_id')->nullable();

            // Specific year for the course/exam
            $table->unsignedSmallInteger('year');

            // Exam Date and Time
            $table->dateTime('exam_at')->nullable()->comment('Scheduled date and time of the exam');

            // Optional detailed description or notes
            $table->text('description')->nullable();

            // Adding indexes for common lookups
            $table->index('institution_id');
            $table->index(['class', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};