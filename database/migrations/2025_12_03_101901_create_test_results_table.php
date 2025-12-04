<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');


            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('attempt_number')->default(1);
            $table->boolean('passed')->default(false);


            $table->integer('time_spent')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();


            $table->json('answers')->nullable();
            $table->json('correct_answers_list')->nullable();
            $table->json('question_details')->nullable();

            $table->timestamps();


            $table->unique(['user_id', 'test_id', 'attempt_number']);
            $table->index(['user_id', 'course_id', 'passed']);
            $table->index(['completed_at', 'score']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};
