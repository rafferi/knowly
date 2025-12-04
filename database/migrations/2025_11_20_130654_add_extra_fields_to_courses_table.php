<?php
// database/migrations/2025_11_18_130000_add_fields_to_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('course_type')->default('general')->after('category_id');
            $table->string('group_size')->nullable()->after('lessons_count');
            $table->boolean('popular')->default(false)->after('is_active');
            $table->text('short_description')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['course_type', 'group_size', 'popular', 'short_description']);
        });
    }
};
