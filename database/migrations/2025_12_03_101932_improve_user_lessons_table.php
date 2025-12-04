<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_lessons', function (Blueprint $table) {

            $table->foreignId('module_id')->nullable()->after('lesson_id')->constrained()->nullOnDelete();
            $table->integer('week_number')->nullable()->after('module_id');

            $table->json('lesson_stats')->nullable()->after('time_spent'); // {"video_watched": true, "exercises_completed": 5}
            $table->decimal('completion_percentage', 5, 2)->default(0.00)->after('score');
            $table->timestamp('started_at')->nullable()->after('completed_at');
            $table->timestamp('last_accessed_at')->nullable()->after('started_at');


            $table->index(['user_id', 'module_id']);
            $table->index(['user_id', 'week_number']);
            $table->index(['user_id', 'completion_percentage']);
        });
    }

    public function down()
    {
        Schema::table('user_lessons', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn([
                'module_id',
                'week_number',
                'lesson_stats',
                'completion_percentage',
                'started_at',
                'last_accessed_at'
            ]);
        });
    }
};
