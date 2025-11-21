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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('article')->nullable();
            $table->string('a')->nullable();
            $table->string('b')->nullable();
            $table->string('c')->nullable();
            $table->string('d')->nullable();
            $table->string('answer')->nullable();
            $table->string('subject')->nullable();
            $table->string('topic')->nullable();
            $table->string('sub_topic')->nullable();
            $table->string('section')->nullable();
            $table->string('sub_section')->nullable();
            $table->string('category')->nullable();
            $table->string('board')->nullable();
            $table->string('year')->nullable();
            $table->string('class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
