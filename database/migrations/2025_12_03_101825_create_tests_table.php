<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();


            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();


            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'placement',
                'module_test',
                'weekly_test',
                'midterm',
                'final',
                'practice'
            ])->default('module_test');

            $table->integer('week_number')->nullable();
            $table->json('questions')->nullable();
            $table->integer('time_limit')->nullable();
            $table->integer('passing_score')->default(70);
            $table->integer('max_attempts')->default(3);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Индексы
            $table->index(['course_id', 'type']);
            $table->index(['module_id', 'week_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
};
