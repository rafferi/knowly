<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');

            $table->enum('status', [
                'pending',     // ожидает оплаты/подтверждения
                'active',      // активная запись
                'completed',   // курс завершен
                'cancelled',   // отменена
                'expired'      // срок записи истек
            ])->default('pending');

            $table->decimal('price', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index(['status', 'enrolled_at']);
            $table->index(['user_id', 'status']);
            $table->index(['course_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
