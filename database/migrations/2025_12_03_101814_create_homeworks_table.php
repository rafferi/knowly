<?php
// database/migrations/2025_12_03_100003_create_homeworks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();

            // Контент домашнего задания
            $table->text('content')->nullable();
            $table->json('attachments')->nullable(); // ['file1.pdf', 'audio.mp3', ...]
            $table->json('answers')->nullable(); // Для тестовых заданий

            // Статус и оценка
            $table->enum('status', [
                'not_started',
                'in_progress',
                'submitted',
                'under_review',
                'graded',
                'returned_for_revision'
            ])->default('not_started');

            $table->integer('score')->nullable();
            $table->integer('max_score')->default(100);
            $table->text('teacher_feedback')->nullable();
            $table->json('corrections')->nullable();

            // Даты
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('deadline_at')->nullable();

            // Время выполнения
            $table->integer('time_spent')->default(0); // в минутах

            $table->timestamps();

            // Индексы
            $table->index(['user_id', 'lesson_id']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'course_id', 'module_id']);
            $table->index(['deadline_at', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('homeworks');
    }
};
