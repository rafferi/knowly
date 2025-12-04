<?php
// database/seeders/LearningPlanTestSeeder.php

namespace Database\Seeders;

use App\Models\{User, Course, Module, Lesson, UserLearningProgress};
use Illuminate\Database\Seeder;

class LearningPlanTestSeeder extends Seeder
{
    public function run()
    {
        // Находим тестового пользователя
        $user = User::first();
        $course = Course::where('slug', 'english-starter')->first();

        if (!$user || !$course) {
            $this->command->error('❌ Нет пользователя или курса для теста');
            return;
        }

        // Создаем прогресс обучения для пользователя
        $progress = UserLearningProgress::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'current_week' => 1,
            'total_weeks' => 80,
            'start_date' => now(),
            'estimated_completion' => now()->addWeeks(80),
            'weekly_goal_hours' => 5,
            'learning_pace' => 'normal'
        ]);

        $this->command->info("✅ Создан тестовый прогресс для пользователя: {$user->name}");
        $this->command->info("   Курс: {$course->title}");
        $this->command->info("   Начало: " . $progress->start_date->format('d.m.Y'));
        $this->command->info("   Планируемое завершение: " . $progress->estimated_completion->format('d.m.Y'));

        // Выводим информацию о созданном плане
        $modules = Module::where('course_id', $course->id)->get();

        $this->command->info("\n📚 Созданные модули:");
        foreach ($modules as $module) {
            $lessonCount = Lesson::where('module_id', $module->id)->count();
            $this->command->info("   - {$module->title} (недели {$module->start_week}-{$module->end_week}) - {$lessonCount} уроков");
        }

        $totalLessons = Lesson::where('course_id', $course->id)->count();
        $this->command->info("\n📊 Итого: {$totalLessons} уроков на 80 недель");

        // Показываем первую неделю
        $week1Lessons = Lesson::where('course_id', $course->id)
            ->where('week_number', 1)
            ->orderBy('order')
            ->get();

        $this->command->info("\n📅 Неделя 1 (первые уроки):");
        foreach ($week1Lessons as $lesson) {
            $this->command->info("   - {$lesson->title} ({$lesson->lesson_type}, {$lesson->estimated_time} мин)");
        }
    }
}
