<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['payment_status', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['enrolled_at']);
        });
    }

    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'status']);
            $table->dropIndex(['start_date', 'end_date']);
            $table->dropIndex(['enrolled_at']);
        });
    }
};
