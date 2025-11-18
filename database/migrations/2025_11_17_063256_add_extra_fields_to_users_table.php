<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('phone')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('phone');
            $table->string('level')->default('beginner')->after('bio');
            $table->integer('xp')->default(0)->after('level');
            $table->integer('streak_days')->default(0)->after('xp');
            $table->timestamp('last_activity_at')->nullable()->after('streak_days');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'bio', 'level', 'xp', 'streak_days', 'last_activity_at']);
        });
    }
};
