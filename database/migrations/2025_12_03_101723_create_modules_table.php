<?php
// database/migrations/2025_12_03_100000_create_modules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order_index')->default(0);
            $table->integer('duration_weeks')->default(1);
            $table->enum('phase', ['basic', 'intermediate', 'advanced'])->default('basic');
            $table->integer('start_week')->default(1);
            $table->integer('end_week')->default(1);
            $table->json('learning_objectives')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['course_id', 'order_index']);
            $table->index(['course_id', 'phase']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
