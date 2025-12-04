<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // database/seeders/DatabaseSeeder.php
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            PlacementQuestionsSeeder::class,
            CourseSeeder::class,
            AchievementSeeder::class,
            CompleteEnglishCourseSeeder::class,
            TestsSeeder::class, // Добавьте эту строку
            LearningPlanTestSeeder::class,
        ]);
    }
}
