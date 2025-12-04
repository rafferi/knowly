<?php
// database/migrations/2025_12_03_100002_create_user_learning_progress_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_learning_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');


            $table->integer('current_week')->default(1);
            $table->integer('current_module_id')->nullable();
            $table->integer('total_weeks')->default(80);


            $table->date('start_date')->nullable();
            $table->date('estimated_completion')->nullable();
            $table->date('actual_completion')->nullable();


            $table->json('completed_weeks')->nullable(); // [1, 2, 3...]
            $table->json('completed_module_ids')->nullable(); // [1, 2, 3...]


            $table->decimal('overall_progress', 5, 2)->default(0.00); // в процентах
            $table->json('weekly_progress')->nullable(); // {"week_1": 80, "week_2": 100, ...}
            $table->integer('total_study_time')->default(0); // в минутах
            $table->integer('lessons_completed')->default(0);
            $table->integer('homeworks_submitted')->default(0);
            $table->timestamp('last_study_date')->nullable();


            $table->integer('weekly_goal_hours')->default(5);
            $table->boolean('notifications_enabled')->default(true);
            $table->enum('learning_pace', ['slow', 'normal', 'fast'])->default('normal');

            $table->timestamps();


            $table->unique(['user_id', 'course_id']);
            $table->index(['user_id', 'last_study_date']);
            $table->index(['overall_progress', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_learning_progress');
    }
};
