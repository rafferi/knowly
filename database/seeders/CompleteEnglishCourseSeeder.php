<?php
// database/seeders/CompleteEnglishCourseSeeder.php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompleteEnglishCourseSeeder extends Seeder
{
    private $course;
    private $lessonCounter = 1;

    public function run()
    {
        $this->command->info('🎓 Создаем полный курс английского языка на 80 недель...');

        // 1. Находим или создаем курс English Starter
        $this->course = Course::firstOrCreate(
            ['slug' => 'english-starter'],
            [
                'title' => 'English Starter - от A1 до C1',
                'slug' => 'english-starter',
                'description' => 'Комплексный 80-недельный курс английского языка от начального до продвинутого уровня. Полный путь от A1 до C1.',
                'short_description' => '80 недель, 400+ уроков, путь от A1 до C1',
                'level' => 'A1-C1',
                'price' => 8900.00,
                'duration' => 80,
                'lessons_count' => 400,
                'group_size' => '4-6',
                'is_active' => true,
                'popular' => true,
                'course_type' => 'beginner',
                'features' => json_encode([
                    '80 недель обучения',
                    '400+ уроков',
                    '3 фазы: базовый, средний, продвинутый',
                    'Грамматика от простого к сложному',
                    'Разговорная практика с каждым уроком',
                    'Домашние задания и тесты',
                    'Персональный трекер прогресса',
                    'Поддержка преподавателя',
                    'Сертификат по окончании'
                ])
            ]
        );

        $this->command->info("✅ Курс: {$this->course->title}");

        // 2. Очищаем старые данные
        $this->cleanOldData();

        // 3. Создаем модули по фазам
        $modules = $this->createModules();

        // 4. Создаем уроки для всех 80 недель
        $this->createAllLessons($modules);

        // 5. Создаем тесты и экзамены
        $this->createTestsAndExams($modules);

        // 6. Обновляем информацию о курсе
        $this->updateCourseInfo();

        $this->command->info('🎉 Полный курс успешно создан!');
        $this->command->info("📊 Итоги:");
        $this->command->info("   - Модулей: " . Module::where('course_id', $this->course->id)->count());
        $this->command->info("   - Уроков: " . Lesson::where('course_id', $this->course->id)->count());
        $this->command->info("   - Недель: 80");
        $this->command->info("   - Фаз: 3 (базовый, средний, продвинутый)");
    }

    private function cleanOldData()
    {
        Lesson::where('course_id', $this->course->id)->delete();
        Module::where('course_id', $this->course->id)->delete();
        $this->command->info("🧹 Очищены старые данные курса");
    }

    private function createModules()
    {
        $modules = [];

        // Фаза A: Базовый уровень (20 недель)
        $modules['basic'] = Module::create([
            'course_id' => $this->course->id,
            'title' => 'Фаза A: Базовый уровень (A1-A2)',
            'slug' => Str::slug($this->course->slug . '-basic-level'),
            'description' => '20 недель: основы английского для начинающих. Алфавит, базовые времена, повседневная лексика.',
            'order_index' => 1,
            'duration_weeks' => 20,
            'phase' => 'basic',
            'start_week' => 1,
            'end_week' => 20,
            'learning_objectives' => json_encode([
                'Освоить английский алфавит и произношение',
                'Изучить базовые времена: Present Simple, Past Simple, Future Simple',
                'Накопить активный словарный запас 1000+ слов',
                'Научиться строить простые предложения и вопросы',
                'Понимать базовые диалоги и тексты',
                'Вести простые разговоры на повседневные темы'
            ]),
            'is_active' => true
        ]);

        // Фаза B: Средний уровень (40 недель)
        $modules['intermediate'] = Module::create([
            'course_id' => $this->course->id,
            'title' => 'Фаза B: Средний уровень (B1-B2)',
            'slug' => Str::slug($this->course->slug . '-intermediate-level'),
            'description' => '40 недель: углубленная грамматика, расширенная лексика, сложные конструкции.',
            'order_index' => 2,
            'duration_weeks' => 40,
            'phase' => 'intermediate',
            'start_week' => 21,
            'end_week' => 60,
            'learning_objectives' => json_encode([
                'Освоить все основные времена английского языка',
                'Изучить пассивный залог, условные предложения, косвенную речь',
                'Расширить словарный запас до 3000+ слов',
                'Научиться понимать фильмы и сериалы в оригинале',
                'Вести дискуссии на различные темы',
                'Писать связные тексты и эссе'
            ]),
            'is_active' => true
        ]);

        // Фаза C: Продвинутый уровень (20 недель)
        $modules['advanced'] = Module::create([
            'course_id' => $this->course->id,
            'title' => 'Фаза C: Продвинутый уровень (C1)',
            'slug' => Str::slug($this->course->slug . '-advanced-level'),
            'description' => '20 недель: продвинутая грамматика, идиомы, деловой английский.',
            'order_index' => 3,
            'duration_weeks' => 20,
            'phase' => 'advanced',
            'start_week' => 61,
            'end_week' => 80,
            'learning_objectives' => json_encode([
                'Свободно владеть всеми грамматическими конструкциями',
                'Использовать идиомы и фразовые глаголы',
                'Вести деловые переговоры и презентации',
                'Писать академические работы на английском',
                'Понимать сложные тексты любой тематики',
                'Достичь уровня C1 по международной шкале'
            ]),
            'is_active' => true
        ]);

        $this->command->info("✅ Созданы модули:");
        $this->command->info("   - Фаза A: недели 1-20");
        $this->command->info("   - Фаза B: недели 21-60");
        $this->command->info("   - Фаза C: недели 61-80");

        return $modules;
    }

    private function createAllLessons($modules)
    {
        $this->lessonCounter = 1;

        // НЕДЕЛИ 1-20: ФАЗА A - БАЗОВЫЙ УРОВЕНЬ
        $this->command->info("📝 Создаем уроки для фазы A (недели 1-20)...");

        // Недели 1-4: Знакомство с языком
        for ($week = 1; $week <= 4; $week++) {
            $this->createWeekLessons($this->course, $modules['basic'], $week, 'basic', 1);
        }

        // Недели 5-8: Present Simple
        for ($week = 5; $week <= 8; $week++) {
            $this->createWeekLessons($this->course, $modules['basic'], $week, 'basic', 2);
        }

        // Недели 9-12: Past Simple
        for ($week = 9; $week <= 12; $week++) {
            $this->createWeekLessons($this->course, $modules['basic'], $week, 'basic', 3);
        }

        // Недели 13-16: Future и модальные глаголы
        for ($week = 13; $week <= 16; $week++) {
            $this->createWeekLessons($this->course, $modules['basic'], $week, 'basic', 4);
        }

        // Недели 17-20: Сравнение и повседневные ситуации
        for ($week = 17; $week <= 20; $week++) {
            $this->createWeekLessons($this->course, $modules['basic'], $week, 'basic', 5);
        }

        // НЕДЕЛИ 21-60: ФАЗА B - СРЕДНИЙ УРОВЕНЬ
        $this->command->info("📝 Создаем уроки для фазы B (недели 21-60)...");

        // Недели 21-28: Present Perfect
        for ($week = 21; $week <= 28; $week++) {
            $this->createWeekLessons($this->course, $modules['intermediate'], $week, 'intermediate', 1);
        }

        // Недели 29-36: Пассивный залог и условные предложения
        for ($week = 29; $week <= 36; $week++) {
            $this->createWeekLessons($this->course, $modules['intermediate'], $week, 'intermediate', 2);
        }

        // Недели 37-44: Модальные глаголы в прошлом
        for ($week = 37; $week <= 44; $week++) {
            $this->createWeekLessons($this->course, $modules['intermediate'], $week, 'intermediate', 3);
        }

        // Недели 45-52: Косвенная речь
        for ($week = 45; $week <= 52; $week++) {
            $this->createWeekLessons($this->course, $modules['intermediate'], $week, 'intermediate', 4);
        }

        // Недели 53-60: Артикли и идиомы
        for ($week = 53; $week <= 60; $week++) {
            $this->createWeekLessons($this->course, $modules['intermediate'], $week, 'intermediate', 5);
        }

        // НЕДЕЛИ 61-80: ФАЗА C - ПРОДВИНУТЫЙ УРОВЕНЬ
        $this->command->info("📝 Создаем уроки для фазы C (недели 61-80)...");

        // Недели 61-68: Нюансы времен
        for ($week = 61; $week <= 68; $week++) {
            $this->createWeekLessons($this->course, $modules['advanced'], $week, 'advanced', 1);
        }

        // Недели 69-76: Стилистика
        for ($week = 69; $week <= 76; $week++) {
            $this->createWeekLessons($this->course, $modules['advanced'], $week, 'advanced', 2);
        }

        // Недели 77-80: Идиомы и культура
        for ($week = 77; $week <= 80; $week++) {
            $this->createWeekLessons($this->course, $modules['advanced'], $week, 'advanced', 3);
        }
    }

    private function createWeekLessons($course, $module, $weekNumber, $phase, $weekType)
    {
        $lessonsData = $this->getWeekLessonsData($phase, $weekType, $weekNumber);
        $order = 1;

        foreach ($lessonsData as $lessonData) {
            Lesson::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'title' => $lessonData['title'],
                'content' => $lessonData['content'],
                'order' => $order++,
                'week_number' => $weekNumber,
                'lesson_type' => $lessonData['lesson_type'],
                'duration' => $lessonData['estimated_time'],
                'estimated_time' => $lessonData['estimated_time'],
                'has_homework' => $lessonData['has_homework'] ?? false,
                'homework_instructions' => isset($lessonData['homework_instructions'])
                    ? json_encode($lessonData['homework_instructions'])
                    : null,
                'is_free' => ($weekNumber <= 2), // Первые 2 недели бесплатные
                'learning_materials' => $this->generateLearningMaterials($lessonData['lesson_type'], $phase),
                'additional_resources' => $this->generateAdditionalResources($phase, $weekNumber),
                'video_url' => $this->generateVideoUrl($lessonData['lesson_type'], $phase)
            ]);

            $this->lessonCounter++;
        }

        // Каждую 4-ю неделю добавляем тест
        if ($weekNumber % 4 == 0 && $weekNumber != 20 && $weekNumber != 60 && $weekNumber != 80) {
            $this->createTestLesson($course, $module, $weekNumber);
        }
    }

    private function getWeekLessonsData($phase, $weekType, $weekNumber)
    {
        $lessons = [];

        switch ($phase) {
            case 'basic':
                $lessons = $this->getBasicWeekLessons($weekType, $weekNumber);
                break;
            case 'intermediate':
                $lessons = $this->getIntermediateWeekLessons($weekType, $weekNumber);
                break;
            case 'advanced':
                $lessons = $this->getAdvancedWeekLessons($weekType, $weekNumber);
                break;
        }

        return $lessons;
    }

    private function getBasicWeekLessons($weekType, $weekNumber)
    {
        switch ($weekType) {
            case 1: // Недели 1-4: Знакомство с языком
                return [
                    [
                        'title' => 'Английский алфавит и произношение звуков',
                        'content' => $this->getLessonContent('alphabet_pronunciation'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 45,
                        'has_homework' => true,
                        'homework_instructions' => [
                            'type' => 'practice',
                            'tasks' => ['Прописать алфавит 3 раза', 'Произнести все звуки', 'Записать свое произношение'],
                            'deadline_days' => 3
                        ]
                    ],
                    [
                        'title' => 'Базовые фразы приветствия и знакомства',
                        'content' => $this->getLessonContent('greetings_introductions'),
                        'lesson_type' => 'vocabulary',
                        'estimated_time' => 40,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Числа от 1 до 100 и даты',
                        'content' => $this->getLessonContent('numbers_dates'),
                        'lesson_type' => 'vocabulary',
                        'estimated_time' => 50,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Глагол TO BE: формы и использование',
                        'content' => $this->getLessonContent('verb_to_be'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 55,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Аудирование: базовые диалоги в аэропорту',
                        'content' => $this->getLessonContent('basic_airport_dialogues'),
                        'lesson_type' => 'listening',
                        'estimated_time' => 35,
                        'has_homework' => false
                    ]
                ];

            case 2: // Недели 5-8: Present Simple
                return [
                    [
                        'title' => 'Present Simple: утвердительные предложения',
                        'content' => $this->getLessonContent('present_simple_affirmative'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 50,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Present Simple: вопросы и отрицания',
                        'content' => $this->getLessonContent('present_simple_questions_negatives'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 55,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Лексика: профессии и рабочие места',
                        'content' => $this->getLessonContent('professions_workplaces'),
                        'lesson_type' => 'vocabulary',
                        'estimated_time' => 45,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Наречия частоты: always, usually, sometimes',
                        'content' => $this->getLessonContent('frequency_adverbs'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 40,
                        'has_homework' => false
                    ],
                    [
                        'title' => 'Разговорная практика: "Мой рабочий день"',
                        'content' => $this->getLessonContent('daily_routine_conversation'),
                        'lesson_type' => 'speaking',
                        'estimated_time' => 50,
                        'has_homework' => true
                    ]
                ];

            // Добавьте остальные case для basic...
        }

        // По умолчанию возвращаем базовые уроки
        return $this->getDefaultLessons($weekNumber);
    }

    private function getIntermediateWeekLessons($weekType, $weekNumber)
    {
        switch ($weekType) {
            case 1: // Недели 21-28: Present Perfect
                return [
                    [
                        'title' => 'Present Perfect: образование и использование',
                        'content' => $this->getLessonContent('present_perfect_formation'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 60,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Present Perfect vs Past Simple: различия',
                        'content' => $this->getLessonContent('present_perfect_vs_past_simple'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 65,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Лексика: достижения и опыт',
                        'content' => $this->getLessonContent('achievements_experience'),
                        'lesson_type' => 'vocabulary',
                        'estimated_time' => 50,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Чтение: истории успеха известных людей',
                        'content' => $this->getLessonContent('success_stories_reading'),
                        'lesson_type' => 'reading',
                        'estimated_time' => 55,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Обсуждение жизненного опыта',
                        'content' => $this->getLessonContent('life_experience_discussion'),
                        'lesson_type' => 'speaking',
                        'estimated_time' => 60,
                        'has_homework' => true
                    ]
                ];

            // Добавьте остальные case для intermediate...
        }

        return $this->getDefaultLessons($weekNumber);
    }

    private function getAdvancedWeekLessons($weekType, $weekNumber)
    {
        switch ($weekType) {
            case 1: // Недели 61-68: Нюансы времен
                return [
                    [
                        'title' => 'Нюансы использования английских времен',
                        'content' => $this->getLessonContent('tenses_nuances'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 70,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Сложные синтаксические конструкции',
                        'content' => $this->getLessonContent('complex_syntax'),
                        'lesson_type' => 'grammar',
                        'estimated_time' => 75,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Лексика: политика и право',
                        'content' => $this->getLessonContent('politics_law_vocabulary'),
                        'lesson_type' => 'vocabulary',
                        'estimated_time' => 60,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Чтение: политические статьи',
                        'content' => $this->getLessonContent('political_articles'),
                        'lesson_type' => 'reading',
                        'estimated_time' => 65,
                        'has_homework' => true
                    ],
                    [
                        'title' => 'Дебаты и аргументация',
                        'content' => $this->getLessonContent('debates_argumentation'),
                        'lesson_type' => 'speaking',
                        'estimated_time' => 80,
                        'has_homework' => true
                    ]
                ];

            // Добавьте остальные case для advanced...
        }

        return $this->getDefaultLessons($weekNumber);
    }

    private function getDefaultLessons($weekNumber)
    {
        return [
            [
                'title' => "Грамматика недели {$weekNumber}",
                'content' => "<h2>Грамматический урок</h2><p>Изучаем грамматические правила недели.</p>",
                'lesson_type' => 'grammar',
                'estimated_time' => 45,
                'has_homework' => true
            ],
            [
                'title' => "Лексика недели {$weekNumber}",
                'content' => "<h2>Лексический урок</h2><p>Расширяем словарный запас.</p>",
                'lesson_type' => 'vocabulary',
                'estimated_time' => 40,
                'has_homework' => true
            ],
            [
                'title' => "Аудирование недели {$weekNumber}",
                'content' => "<h2>Аудирование</h2><p>Тренируем понимание на слух.</p>",
                'lesson_type' => 'listening',
                'estimated_time' => 35,
                'has_homework' => false
            ],
            [
                'title' => "Чтение недели {$weekNumber}",
                'content' => "<h2>Чтение</h2><p>Работаем с текстами.</p>",
                'lesson_type' => 'reading',
                'estimated_time' => 50,
                'has_homework' => true
            ],
            [
                'title' => "Разговорная практика недели {$weekNumber}",
                'content' => "<h2>Разговорная практика</h2><p>Тренируем речь.</p>",
                'lesson_type' => 'speaking',
                'estimated_time' => 55,
                'has_homework' => true
            ]
        ];
    }

    private function createTestLesson($course, $module, $weekNumber)
    {
        Lesson::create([
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => "Тест: недели " . ($weekNumber - 3) . "-{$weekNumber}",
            'content' => "<h2>Проверка знаний</h2><p>Тест для проверки усвоения материала последних 4 недель.</p>",
            'order' => 6,
            'week_number' => $weekNumber,
            'lesson_type' => 'test',
            'duration' => 60,
            'estimated_time' => 60,
            'has_homework' => false,
            'is_free' => true
        ]);

        $this->lessonCounter++;
    }

    private function createTestsAndExams($modules)
    {
        $this->command->info("📊 Создаем тесты и экзамены...");

        // Экзамен после фазы A (20 неделя)
        Lesson::create([
            'course_id' => $this->course->id,
            'module_id' => $modules['basic']->id,
            'title' => 'Экзамен: Фаза A (недели 1-20)',
            'content' => "<h2>Экзамен по базовому уровню</h2><p>Комплексный экзамен для подтверждения уровня A2.</p>",
            'order' => 7,
            'week_number' => 20,
            'lesson_type' => 'exam',
            'duration' => 90,
            'estimated_time' => 90,
            'has_homework' => false,
            'is_free' => false
        ]);

        // Экзамен после фазы B (60 неделя)
        Lesson::create([
            'course_id' => $this->course->id,
            'module_id' => $modules['intermediate']->id,
            'title' => 'Экзамен: Фаза B (недели 21-60)',
            'content' => "<h2>Экзамен по среднему уровню</h2><p>Комплексный экзамен для подтверждения уровня B2.</p>",
            'order' => 7,
            'week_number' => 60,
            'lesson_type' => 'exam',
            'duration' => 120,
            'estimated_time' => 120,
            'has_homework' => false,
            'is_free' => false
        ]);

        // Финальный экзамен (80 неделя)
        Lesson::create([
            'course_id' => $this->course->id,
            'module_id' => $modules['advanced']->id,
            'title' => 'Финальный экзамен: весь курс (недели 1-80)',
            'content' => "<h2>Финальный экзамен</h2><p>Комплексный экзамен для подтверждения уровня C1.</p>",
            'order' => 7,
            'week_number' => 80,
            'lesson_type' => 'exam',
            'duration' => 150,
            'estimated_time' => 150,
            'has_homework' => false,
            'is_free' => false
        ]);

        $this->lessonCounter += 3;
    }

    private function getLessonContent($type)
    {
        $contents = [
            'alphabet_pronunciation' => "<h2>Английский алфавит и произношение</h2>
                <p>Английский алфавит состоит из 26 букв. Каждая буква имеет название и звук.</p>
                <h3>Задания:</h3>
                <ul>
                    <li>Выучите алфавит</li>
                    <li>Практикуйте произношение звуков</li>
                    <li>Напишите алфавит 3 раза</li>
                </ul>",

            'greetings_introductions' => "<h2>Приветствия и знакомства</h2>
                <p>Основные фразы для общения: Hello, Hi, Good morning, How are you?</p>",

            'verb_to_be' => "<h2>Глагол TO BE</h2>
                <p>Формы am, is, are и их использование с местоимениями.</p>",

            'present_simple_affirmative' => "<h2>Present Simple: утверждения</h2>
                <p>Утвердительные предложения в настоящем простом времени.</p>",

            // Добавьте остальные контенты...
        ];

        return $contents[$type] ?? "<h2>Урок английского языка</h2><p>Этот урок поможет вам улучшить ваш английский.</p>";
    }

    private function generateLearningMaterials($lessonType, $phase)
    {
        $materials = [];

        switch ($lessonType) {
            case 'grammar':
                $materials = [
                    ['type' => 'pdf', 'title' => 'Грамматические таблицы', 'url' => '/materials/grammar.pdf'],
                    ['type' => 'video', 'title' => 'Видеообъяснение', 'url' => 'https://youtube.com/embed/grammar']
                ];
                break;
            case 'vocabulary':
                $materials = [
                    ['type' => 'pdf', 'title' => 'Список слов', 'url' => '/materials/vocabulary.pdf'],
                    ['type' => 'audio', 'title' => 'Произношение', 'url' => '/audio/words.mp3']
                ];
                break;
            // ... другие типы
        }

        return json_encode($materials);
    }

    private function generateAdditionalResources($phase, $weekNumber)
    {
        return json_encode([
            'links' => [
                ['title' => 'Дополнительные упражнения', 'url' => 'https://learnenglish.britishcouncil.org/'],
                ['title' => 'Практика произношения', 'url' => 'https://forvo.com/']
            ],
            'recommendations' => [
                'Повторите материал предыдущей недели',
                'Практикуйте ежедневно по 15 минут'
            ]
        ]);
    }

    private function generateVideoUrl($lessonType, $phase)
    {
        $videos = [
            'grammar' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'listening' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'speaking' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
        ];

        return $videos[$lessonType] ?? null;
    }

    private function updateCourseInfo()
    {
        $lessonCount = Lesson::where('course_id', $this->course->id)->count();

        $this->course->update([
            'lessons_count' => $lessonCount,
            'duration' => 80,
            'short_description' => "Полный курс на 80 недель. {$lessonCount} уроков. Путь от A1 до C1."
        ]);

        $this->command->info("✅ Информация о курсе обновлена");
        $this->command->info("   Всего уроков: {$lessonCount}");
    }
}
