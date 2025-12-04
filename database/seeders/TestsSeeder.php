<?php
// database/seeders/TestsSeeder.php

namespace Database\Seeders;

use App\Models\Test;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Создаем тесты для курса...');

        $course = Course::where('slug', 'english-starter')->first();

        if (!$course) {
            $this->command->error('Курс English Starter не найден!');
            return;
        }

        // Очищаем старые тесты
        Test::where('course_id', $course->id)->delete();

        // Получаем модули
        $modules = Module::where('course_id', $course->id)->get();

        // Placement test (начальный тест на определение уровня)
        $this->createPlacementTest($course);

        // Module tests (тесты после каждого модуля)
        $this->createModuleTests($course, $modules);

        // Weekly tests (тесты по неделям)
        $this->createWeeklyTests($course);

        // Midterm tests (промежуточные тесты)
        $this->createMidtermTests($course);

        // Final test (итоговый тест)
        $this->createFinalTest($course);

        // Practice tests (практические тесты)
        $this->createPracticeTests($course, $modules);

        $this->command->info('Все тесты успешно созданы!');
    }

    private function createPlacementTest($course)
    {
        Test::create([
            'course_id' => $course->id,
            'module_id' => null,
            'lesson_id' => null,
            'title' => 'Placement Test: Определение уровня',
            'description' => 'Тест для определения вашего текущего уровня английского языка. Поможет подобрать оптимальную точку старта.',
            'type' => 'placement',
            'week_number' => null,
            'questions' => json_encode($this->generatePlacementQuestions()),
            'time_limit' => 45,
            'passing_score' => 60,
            'max_attempts' => 1,
            'is_active' => true
        ]);

        $this->command->info('Создан placement test');
    }

    private function createModuleTests($course, $modules)
    {
        foreach ($modules as $module) {
            Test::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'lesson_id' => null,
                'title' => "Module Test: {$module->title}",
                'description' => "Тестирование по завершении модуля '{$module->title}'. Проверка усвоения материала.",
                'type' => 'module_test',
                'week_number' => $module->end_week,
                'questions' => json_encode($this->generateModuleQuestions($module->phase)),
                'time_limit' => 60,
                'passing_score' => 70,
                'max_attempts' => 3,
                'is_active' => true
            ]);
        }

        $this->command->info('Созданы модульные тесты');
    }

    private function createWeeklyTests($course)
    {
        // Создаем тесты каждые 4 недели
        for ($week = 4; $week <= 80; $week += 4) {
            // Пропускаем недели с финальными экзаменами
            if ($week == 20 || $week == 60 || $week == 80) {
                continue;
            }

            Test::create([
                'course_id' => $course->id,
                'module_id' => null,
                'lesson_id' => null,
                'title' => "Weekly Test: Недели " . ($week - 3) . "-{$week}",
                'description' => "Тестирование по материалу недель " . ($week - 3) . "-{$week}.",
                'type' => 'weekly_test',
                'week_number' => $week,
                'questions' => json_encode($this->generateWeeklyQuestions($week)),
                'time_limit' => 45,
                'passing_score' => 65,
                'max_attempts' => 2,
                'is_active' => true
            ]);
        }

        $this->command->info('Созданы недельные тесты');
    }

    private function createMidtermTests($course)
    {
        // Промежуточные тесты на 40-й и 70-й неделях
        $midtermWeeks = [40, 70];

        foreach ($midtermWeeks as $week) {
            Test::create([
                'course_id' => $course->id,
                'module_id' => null,
                'lesson_id' => null,
                'title' => "Midterm Test: Недели 1-{$week}",
                'description' => "Промежуточный тест по материалу первых {$week} недель.",
                'type' => 'midterm',
                'week_number' => $week,
                'questions' => json_encode($this->generateMidtermQuestions($week)),
                'time_limit' => 90,
                'passing_score' => 70,
                'max_attempts' => 2,
                'is_active' => true
            ]);
        }

        $this->command->info('Созданы промежуточные тесты');
    }

    private function createFinalTest($course)
    {
        Test::create([
            'course_id' => $course->id,
            'module_id' => null,
            'lesson_id' => null,
            'title' => 'Final Test: Полный курс',
            'description' => 'Итоговый тест по всему курсу English Starter (недели 1-80).',
            'type' => 'final',
            'week_number' => 80,
            'questions' => json_encode($this->generateFinalQuestions()),
            'time_limit' => 120,
            'passing_score' => 75,
            'max_attempts' => 2,
            'is_active' => true
        ]);

        $this->command->info('Создан финальный тест');
    }

    private function createPracticeTests($course, $modules)
    {
        foreach ($modules as $module) {
            Test::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'lesson_id' => null,
                'title' => "Practice Test: {$module->title}",
                'description' => "Практический тест для подготовки к модульному тесту.",
                'type' => 'practice',
                'week_number' => $module->start_week,
                'questions' => json_encode($this->generatePracticeQuestions($module->phase)),
                'time_limit' => 30,
                'passing_score' => 50,
                'max_attempts' => 5,
                'is_active' => true
            ]);
        }

        $this->command->info('Созданы практические тесты');
    }

    private function generatePlacementQuestions()
    {
        return [
            [
                'id' => 1,
                'question' => 'What ___ your name?',
                'type' => 'multiple_choice',
                'options' => ['is', 'are', 'am', 'be'],
                'correct_answer' => 0,
                'points' => 5,
                'explanation' => 'Правильный ответ: "is" - используется с he/she/it'
            ],
            [
                'id' => 2,
                'question' => 'I ___ from London.',
                'type' => 'multiple_choice',
                'options' => ['am', 'is', 'are', 'be'],
                'correct_answer' => 0,
                'points' => 5,
                'explanation' => 'С "I" используется "am"'
            ],
            [
                'id' => 3,
                'question' => 'They ___ students.',
                'type' => 'multiple_choice',
                'options' => ['am', 'is', 'are', 'be'],
                'correct_answer' => 2,
                'points' => 5,
                'explanation' => 'С "they" используется "are"'
            ],
            [
                'id' => 4,
                'question' => 'She ___ a teacher.',
                'type' => 'multiple_choice',
                'options' => ['am', 'is', 'are', 'be'],
                'correct_answer' => 1,
                'points' => 5,
                'explanation' => 'С "she" используется "is"'
            ],
            [
                'id' => 5,
                'question' => 'We ___ happy.',
                'type' => 'multiple_choice',
                'options' => ['am', 'is', 'are', 'be'],
                'correct_answer' => 2,
                'points' => 5,
                'explanation' => 'С "we" используется "are"'
            ]
        ];
    }

    private function generateModuleQuestions($phase)
    {
        $questions = [];

        if ($phase === 'basic') {
            $questions = [
                [
                    'id' => 1,
                    'question' => 'Choose the correct form: I ___ breakfast at 7 am.',
                    'type' => 'multiple_choice',
                    'options' => ['have', 'has', 'haves', 'having'],
                    'correct_answer' => 0,
                    'points' => 10
                ],
                [
                    'id' => 2,
                    'question' => 'She ___ to school every day.',
                    'type' => 'multiple_choice',
                    'options' => ['go', 'goes', 'going', 'went'],
                    'correct_answer' => 1,
                    'points' => 10
                ]
            ];
        } elseif ($phase === 'intermediate') {
            $questions = [
                [
                    'id' => 1,
                    'question' => 'If I ___ you, I would study more.',
                    'type' => 'multiple_choice',
                    'options' => ['am', 'was', 'were', 'would be'],
                    'correct_answer' => 2,
                    'points' => 10
                ]
            ];
        } elseif ($phase === 'advanced') {
            $questions = [
                [
                    'id' => 1,
                    'question' => '___ should you behave like that.',
                    'type' => 'multiple_choice',
                    'options' => ['Under no circumstances', 'Never', 'Rarely', 'Only then'],
                    'correct_answer' => 0,
                    'points' => 10
                ]
            ];
        }

        return $questions;
    }

    private function generateWeeklyQuestions($week)
    {
        return [
            [
                'id' => 1,
                'question' => "What did you learn in week {$week}?",
                'type' => 'multiple_choice',
                'options' => ['Grammar', 'Vocabulary', 'Listening', 'All of the above'],
                'correct_answer' => 3,
                'points' => 10
            ]
        ];
    }

    private function generateMidtermQuestions($week)
    {
        return [
            [
                'id' => 1,
                'question' => "Midterm test covering weeks 1-{$week}",
                'type' => 'multiple_choice',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
                'correct_answer' => 1,
                'points' => 20
            ]
        ];
    }

    private function generateFinalQuestions()
    {
        return [
            [
                'id' => 1,
                'question' => 'What is the past participle of "go"?',
                'type' => 'multiple_choice',
                'options' => ['went', 'gone', 'goed', 'going'],
                'correct_answer' => 1,
                'points' => 20
            ],
            [
                'id' => 2,
                'question' => 'Choose the correct conditional: If I had known, I ___ helped.',
                'type' => 'multiple_choice',
                'options' => ['will', 'would have', 'would', 'had'],
                'correct_answer' => 1,
                'points' => 20
            ],
            [
                'id' => 3,
                'question' => 'Which sentence is in the passive voice?',
                'type' => 'multiple_choice',
                'options' => [
                    'The cat chased the mouse.',
                    'The mouse was chased by the cat.',
                    'The cat is chasing the mouse.',
                    'The mouse chased the cat.'
                ],
                'correct_answer' => 1,
                'points' => 20
            ]
        ];
    }

    private function generatePracticeQuestions($phase)
    {
        $questions = [
            [
                'id' => 1,
                'question' => "Practice question for {$phase} level",
                'type' => 'multiple_choice',
                'options' => ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
                'correct_answer' => rand(0, 3),
                'points' => 5
            ]
        ];

        return $questions;
    }
}
