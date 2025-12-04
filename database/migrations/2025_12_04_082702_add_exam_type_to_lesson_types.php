<?php
// database/migrations/2025_12_04_xxxxxx_add_exam_type_to_lesson_types.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Для MySQL используем прямой SQL для изменения ENUM
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE lessons MODIFY COLUMN lesson_type ENUM('grammar', 'vocabulary', 'listening', 'reading', 'writing', 'speaking', 'test', 'review', 'homework', 'practice', 'exam') DEFAULT 'grammar'");
        }

        // Для PostgreSQL используем другой синтаксис
        elseif (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE lessons ALTER COLUMN lesson_type TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE lessons ADD CONSTRAINT check_lesson_type CHECK (lesson_type IN ('grammar', 'vocabulary', 'listening', 'reading', 'writing', 'speaking', 'test', 'review', 'homework', 'practice', 'exam'))");
        }

        // Для SQLite просто добавим колонку
        else {
            // Для SQLite проще создать новую таблицу и скопировать данные
            // Но для простоты покажем сообщение
            \Log::info('Для SQLite требуется ручное изменение типа урока');
        }
    }

    public function down()
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE lessons MODIFY COLUMN lesson_type ENUM('grammar', 'vocabulary', 'listening', 'reading', 'writing', 'speaking', 'test', 'review', 'homework', 'practice') DEFAULT 'grammar'");
        }

        elseif (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE lessons DROP CONSTRAINT check_lesson_type");
            DB::statement("ALTER TABLE lessons ALTER COLUMN lesson_type TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE lessons ADD CONSTRAINT check_lesson_type CHECK (lesson_type IN ('grammar', 'vocabulary', 'listening', 'reading', 'writing', 'speaking', 'test', 'review', 'homework', 'practice'))");
        }
    }
};
