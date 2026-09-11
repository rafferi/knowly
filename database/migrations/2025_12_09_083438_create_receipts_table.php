<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('status')->default('paid');
            $table->timestamp('paid_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('receipt_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};
