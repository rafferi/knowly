<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();

            // Информация о материале
            $table->string('title');
            $table->string('type')->default('document');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('duration')->nullable();


            $table->integer('order_index')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_downloadable')->default(true);
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();


            $table->integer('downloads_count')->default(0);
            $table->integer('views_count')->default(0);

            $table->timestamps();


            $table->index(['course_id', 'type']);
            $table->index(['module_id', 'order_index']);
            $table->index(['lesson_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('learning_materials');
    }
};
