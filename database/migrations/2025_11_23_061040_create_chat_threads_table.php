<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_threads', function (Blueprint $table) {
            $table->ulid('id')->primary();     // Replaces auto-increment ID
            $table->foreignId('user_id')->index();

            $table->string('title')->nullable();
            $table->boolean('is_pending')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_threads');
    }
};