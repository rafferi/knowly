<?php
// database/migrations/2025_12_03_100001_add_learning_plan_fields_to_lessons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Добавляем связь с модулем
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained()->nullOnDelete();

            // Поля для учебного плана
            $table->integer('week_number')->default(1)->after('order');
            $table->enum('lesson_type', [
                'grammar',
                'vocabulary',
                'listening',
                'reading',
                'writing',
                'speaking',
                'test',
                'review',
                'homework',
                'practice'
            ])->default('grammar')->after('content');

            $table->json('learning_materials')->nullable()->after('materials');
            $table->integer('estimated_time')->default(30)->after('duration'); // в минутах
            $table->boolean('has_homework')->default(false)->after('is_free');
            $table->json('homework_instructions')->nullable()->after('has_homework');
            $table->json('additional_resources')->nullable()->after('homework_instructions');

            // Индексы для быстрого поиска
            $table->index(['module_id', 'week_number']);
            $table->index(['course_id', 'week_number']);
            $table->index(['lesson_type', 'week_number']);
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn([
                'module_id',
                'week_number',
                'lesson_type',
                'learning_materials',
                'estimated_time',
                'has_homework',
                'homework_instructions',
                'additional_resources'
            ]);
        });
    }
};
