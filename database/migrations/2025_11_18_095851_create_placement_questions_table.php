<?php
// [file name]: 2025_11_18_000000_create_placement_questions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('placement_questions', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->text('question');
            $table->json('options');
            $table->integer('correct_index');
            $table->string('type')->default('multiple_choice');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('placement_questions');
    }
};
