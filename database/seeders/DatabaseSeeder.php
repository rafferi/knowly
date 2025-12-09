<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            CategorySeeder::class,
            PlacementQuestionsSeeder::class,
            CourseSeeder::class,
            AchievementSeeder::class,
            CompleteEnglishCourseSeeder::class,
            TestsSeeder::class,
            LearningPlanTestSeeder::class,
        ]);
    }
}
