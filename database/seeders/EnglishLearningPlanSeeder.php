<?php
// database/seeders/EnglishLearningPlanSeeder.php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\LearningMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnglishLearningPlanSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Начинаем создание учебного плана...');

        // 1. Находим курс English Starter
        $course = Course::where('slug', 'english-starter')->first();

        if (!$course) {
            $this->command->error('❌ Курс English Starter не найден! Сначала создайте его.');
            return;
        }

        $this->command->info("✅ Найден курс: {$course->title}");

        // 2. Удаляем старые данные (если есть)
        $this->cleanOldData($course);

        // 3. Создаем модули по фазам (согласно вашему документу)
        $modules = $this->createModules($course);

        // 4. Создаем уроки для каждой недели
        $this->createLessonsForPlan($course, $modules);

        // 5. Создаем тестовые уроки для проверки
        $this->createTestLessons($course, $modules);

        // 6. Обновляем информацию о курсе
        $this->updateCourseInfo($course);

        $this->command->info('🎉 Учебный план успешно создан!');
        $this->command->info("📊 Всего создано:");
        $this->command->info("   - Модулей: " . Module::where('course_id', $course->id)->count());
        $this->command->info("   - Уроков: " . Lesson::where('course_id', $course->id)->count());
    }

    /**
     * Очищаем старые данные
     */
    private function cleanOldData($course)
    {
        LearningMaterial::where('course_id', $course->id)->delete();
        Lesson::where('course_id', $course->id)->delete();
        Module::where('course_id', $course->id)->delete();

        $this->command->info("🧹 Очищены старые данные курса");
    }

    /**
     * Создаем модули по фазам
     */
    private function createModules($course)
    {
        $modules = [];

        // Фаза A: Базовый уровень (20 недель)
        $modules['basic'] = Module::create([
            'course_id' => $course->id,
            'title' => 'Фаза A: Базовый уровень',
            'slug' => Str::slug($course->slug . '-basic-level'),
            'description' => '20 недель: алфавит, базовые фразы, времена глаголов, повседневная лексика. Идеально для начинающих с нуля.',
            'order_index' => 1,
            'duration_weeks' => 20,
            'phase' => 'basic',
            'start_week' => 1,
            'end_week' => 20,
            'learning_objectives' => json_encode([
                'Освоить английский алфавит и правильное произношение',
                'Изучить базовые времена: Present Simple, Past Simple, Future Simple',
                'Накопить активный словарный запас 1000+ слов',
                'Научиться строить простые предложения и вопросы',
                'Понимать базовые диалоги и тексты',
                'Вести простые разговоры на повседневные темы'
            ]),
            'is_active' => true
        ]);

        $this->command->info("✅ Создан модуль: Фаза A (недели 1-20)");

        // Фаза B: Средний уровень (40 недель)
        $modules['intermediate'] = Module::create([
            'course_id' => $course->id,
            'title' => 'Фаза B: Средний уровень',
            'slug' => Str::slug($course->slug . '-intermediate-level'),
            'description' => '40 недель: углубленная грамматика, расширенная лексика, сложные конструкции. Для тех, кто хочет свободно общаться.',
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

        $this->command->info("✅ Создан модуль: Фаза B (недели 21-60)");

        // Фаза C: Продвинутый уровень (20 недель)
        $modules['advanced'] = Module::create([
            'course_id' => $course->id,
            'title' => 'Фаза C: Продвинутый уровень',
            'slug' => Str::slug($course->slug . '-advanced-level'),
            'description' => '20 недель: продвинутая грамматика, идиомы, деловой английский, академическое письмо. Для достижения уровня C1.',
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
                'Достичь уровня C1 (Advanced) по международной шкале'
            ]),
            'is_active' => true
        ]);

        $this->command->info("✅ Создан модуль: Фаза C (недели 61-80)");

        return $modules;
    }

    /**
     * Создаем уроки по учебному плану
     */
    private function createLessonsForPlan($course, $modules)
    {
        $lessonCounter = 1;

        // НЕДЕЛЯ 1-4: Знакомство с языком
        for ($week = 1; $week <= 4; $week++) {
            $this->createWeekLessons($course, $modules['basic'], $week, $lessonCounter, [
                [
                    'title' => 'Английский алфавит и произношение',
                    'content' => $this->getLessonContent('alphabet'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 45,
                    'has_homework' => true,
                    'homework_instructions' => [
                        'type' => 'practice',
                        'tasks' => ['Прописать алфавит 3 раза', 'Произнести все звуки'],
                        'deadline_days' => 3
                    ]
                ],
                [
                    'title' => 'Базовые фразы приветствия и прощания',
                    'content' => $this->getLessonContent('greetings'),
                    'lesson_type' => 'vocabulary',
                    'estimated_time' => 30,
                    'has_homework' => false
                ],
                [
                    'title' => 'Числа от 1 до 100',
                    'content' => $this->getLessonContent('numbers'),
                    'lesson_type' => 'vocabulary',
                    'estimated_time' => 40,
                    'has_homework' => true
                ],
                [
                    'title' => 'Глагол TO BE: формы и использование',
                    'content' => $this->getLessonContent('verb_to_be'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 50,
                    'has_homework' => true
                ],
                [
                    'title' => 'Аудирование: базовые диалоги',
                    'content' => $this->getLessonContent('basic_listening'),
                    'lesson_type' => 'listening',
                    'estimated_time' => 35,
                    'has_homework' => false
                ]
            ]);
        }

        // НЕДЕЛЯ 5-8: Present Simple
        for ($week = 5; $week <= 8; $week++) {
            $this->createWeekLessons($course, $modules['basic'], $week, $lessonCounter, [
                [
                    'title' => 'Present Simple: утвердительные предложения',
                    'content' => $this->getLessonContent('present_simple_positive'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 55,
                    'has_homework' => true
                ],
                [
                    'title' => 'Present Simple: вопросы и отрицания',
                    'content' => $this->getLessonContent('present_simple_questions'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 60,
                    'has_homework' => true
                ],
                [
                    'title' => 'Лексика: профессии и рабочие места',
                    'content' => $this->getLessonContent('professions'),
                    'lesson_type' => 'vocabulary',
                    'estimated_time' => 40,
                    'has_homework' => true
                ],
                [
                    'title' => 'Наречия частоты (always, usually, sometimes)',
                    'content' => $this->getLessonContent('frequency_adverbs'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 35,
                    'has_homework' => false
                ],
                [
                    'title' => 'Разговорная практика: "Мой рабочий день"',
                    'content' => $this->getLessonContent('daily_routine_conversation'),
                    'lesson_type' => 'speaking',
                    'estimated_time' => 45,
                    'has_homework' => true
                ]
            ]);
        }

        // НЕДЕЛЯ 9-12: Past Simple
        for ($week = 9; $week <= 12; $week++) {
            $this->createWeekLessons($course, $modules['basic'], $week, $lessonCounter, [
                [
                    'title' => 'Past Simple: правильные глаголы',
                    'content' => $this->getLessonContent('past_simple_regular'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 50,
                    'has_homework' => true
                ],
                [
                    'title' => 'Past Simple: неправильные глаголы (часть 1)',
                    'content' => $this->getLessonContent('irregular_verbs_1'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 60,
                    'has_homework' => true
                ],
                [
                    'title' => 'Предлоги времени (in, on, at, ago)',
                    'content' => $this->getLessonContent('time_prepositions'),
                    'lesson_type' => 'grammar',
                    'estimated_time' => 40,
                    'has_homework' => false
                ],
                [
                    'title' => 'Лексика: путешествия и транспорт',
                    'content' => $this->getLessonContent('travel_vocabulary'),
                    'lesson_type' => 'vocabulary',
                    'estimated_time' => 45,
                    'has_homework' => true
                ],
                [
                    'title' => 'Чтение: биография известной личности',
                    'content' => $this->getLessonContent('biography_reading'),
                    'lesson_type' => 'reading',
                    'estimated_time' => 50,
                    'has_homework' => true
                ]
            ]);
        }

        $this->command->info("✅ Созданы уроки для недель 1-12");
        $this->command->info("   Всего уроков создано: " . ($lessonCounter - 1));
    }

    /**
     * Создаем уроки для одной недели
     */
    private function createWeekLessons($course, $module, $weekNumber, &$lessonCounter, $lessonsData)
    {
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
                'learning_materials' => $this->generateLearningMaterials($lessonData['lesson_type']),
                'additional_resources' => $this->generateAdditionalResources($weekNumber, $lessonData['lesson_type'])
            ]);

            $lessonCounter++;
        }
    }

    /**
     * Создаем тестовые уроки для проверки
     */
    private function createTestLessons($course, $modules)
    {
        // Тест после 4-й недели
        Lesson::create([
            'course_id' => $course->id,
            'module_id' => $modules['basic']->id,
            'title' => 'Тест: Недели 1-4',
            'content' => 'Проверка знаний после первого месяца обучения',
            'order' => 6, // После обычных уроков
            'week_number' => 4,
            'lesson_type' => 'test',
            'duration' => 60,
            'estimated_time' => 60,
            'has_homework' => false,
            'is_free' => true
        ]);

        // Повторение после 8-й недели
        Lesson::create([
            'course_id' => $course->id,
            'module_id' => $modules['basic']->id,
            'title' => 'Повторение: Недели 1-8',
            'content' => 'Обобщение и закрепление материала первого модуля',
            'order' => 6,
            'week_number' => 8,
            'lesson_type' => 'review',
            'duration' => 50,
            'estimated_time' => 50,
            'has_homework' => true,
            'is_free' => true
        ]);

        $this->command->info("✅ Созданы тестовые уроки");
    }

    /**
     * Обновляем информацию о курсе
     */
    private function updateCourseInfo($course)
    {
        $lessonCount = Lesson::where('course_id', $course->id)->count();

        $course->update([
            'lessons_count' => $lessonCount,
            'duration' => 80, // 80 недель
            'short_description' => 'Комплексный 80-недельный курс от A1 до C1. 3 фазы обучения, ' . $lessonCount . ' уроков, гарантированный результат.'
        ]);

        $this->command->info("✅ Информация о курсе обновлена");
        $this->command->info("   Всего уроков в курсе: {$lessonCount}");
    }

    /**
     * Генерируем контент для уроков (заглушки)
     */
    private function getLessonContent($type)
    {
        $contents = [
            'alphabet' => "<h2>Английский алфавит</h2><p>Английский алфавит состоит из 26 букв: 6 гласных и 20 согласных. Каждая буква имеет название и звук. Важно научиться правильно произносить все звуки.</p>",
            'greetings' => "<h2>Приветствия и прощания</h2><p>Основные фразы для знакомства: Hello, Hi, Good morning/afternoon/evening. Как представиться: My name is..., I am...</p>",
            'numbers' => "<h2>Числа от 1 до 100</h2><p>Изучаем произношение и написание чисел. Особое внимание числам 13-19 и десяткам (20, 30, 40...).</p>",
            'verb_to_be' => "<h2>Глагол TO BE</h2><p>Формы am, is, are и их использование с местоимениями. Утверждения, вопросы и отрицания.</p>",
            'basic_listening' => "<h2>Базовое аудирование</h2><p>Простые диалоги для начинающих. Учимся понимать основные фразы на слух.</p>",
            'present_simple_positive' => "<h2>Present Simple: утверждения</h2><p>Утвердительные предложения в настоящем простом времени. Правила добавления -s/-es к глаголам в 3 лице единственного числа.</p>",
            'present_simple_questions' => "<h2>Present Simple: вопросы</h2><p>Как задавать вопросы с do/does. Порядок слов в вопросах. Короткие ответы.</p>",
            'professions' => "<h2>Профессии</h2><p>Названия профессий на английском: teacher, doctor, engineer, programmer. Как спросить о профессии.</p>",
            'frequency_adverbs' => "<h2>Наречия частоты</h2><p>Always, usually, often, sometimes, never. Место наречий в предложении.</p>",
            'daily_routine_conversation' => "<h2>Мой рабочий день</h2><p>Диалоги о повседневной рутине. Как рассказать о своем расписании.</p>",
            'past_simple_regular' => "<h2>Past Simple: правильные глаголы</h2><p>Правильные глаголы с окончанием -ed. Три варианта произношения -ed.</p>",
            'irregular_verbs_1' => "<h2>Неправильные глаголы</h2><p>Глаголы go-went-gone, see-saw-seen, eat-ate-eaten. Основные неправильные глаголы.</p>",
            'time_prepositions' => "<h2>Предлоги времени</h2><p>in the morning, on Monday, at 5 o'clock, ago. Различия в использовании.</p>",
            'travel_vocabulary' => "<h2>Путешествия</h2><p>Слова для аэропорта, отеля, транспорта. Полезные фразы для путешественников.</p>",
            'biography_reading' => "<h2>Биография</h2><p>Текст о жизни известного человека. Учимся понимать тексты в Past Simple.</p>"
        ];

        return $contents[$type] ?? "<h2>Урок английского языка</h2><p>Этот урок поможет вам улучшить ваш английский. Следуйте инструкциям и выполняйте все упражнения.</p>";
    }

    /**
     * Генерируем учебные материалы для урока
     */
    private function generateLearningMaterials($lessonType)
    {
        $materials = [];

        switch ($lessonType) {
            case 'grammar':
                $materials = [
                    ['type' => 'pdf', 'title' => 'Грамматические таблицы', 'url' => '/materials/grammar_tables.pdf'],
                    ['type' => 'video', 'title' => 'Объяснение темы', 'url' => 'https://youtube.com/embed/example'],
                    ['type' => 'exercise', 'title' => 'Интерактивные упражнения', 'url' => '/exercises/grammar']
                ];
                break;
            case 'vocabulary':
                $materials = [
                    ['type' => 'pdf', 'title' => 'Список слов с транскрипцией', 'url' => '/materials/vocabulary_list.pdf'],
                    ['type' => 'audio', 'title' => 'Аудиозапись произношения', 'url' => '/audio/words.mp3'],
                    ['type' => 'flashcards', 'title' => 'Флэш-карточки', 'url' => '/flashcards']
                ];
                break;
            case 'listening':
                $materials = [
                    ['type' => 'audio', 'title' => 'Аудиофайл для урока', 'url' => '/audio/listening.mp3'],
                    ['type' => 'pdf', 'title' => 'Транскрипция аудио', 'url' => '/materials/transcript.pdf']
                ];
                break;
            case 'reading':
                $materials = [
                    ['type' => 'pdf', 'title' => 'Текст для чтения', 'url' => '/materials/reading_text.pdf'],
                    ['type' => 'pdf', 'title' => 'Вопросы к тексту', 'url' => '/materials/reading_questions.pdf']
                ];
                break;
            case 'speaking':
                $materials = [
                    ['type' => 'audio', 'title' => 'Пример диалога', 'url' => '/audio/dialogue.mp3'],
                    ['type' => 'pdf', 'title' => 'Темы для обсуждения', 'url' => '/materials/speaking_topics.pdf']
                ];
                break;
            default:
                $materials = [
                    ['type' => 'pdf', 'title' => 'Материалы урока', 'url' => '/materials/lesson.pdf']
                ];
        }

        return json_encode($materials);
    }

    /**
     * Генерируем дополнительные ресурсы
     */
    private function generateAdditionalResources($weekNumber, $lessonType)
    {
        $resources = [
            'links' => [
                ['title' => 'Дополнительные упражнения', 'url' => 'https://learnenglish.britishcouncil.org/'],
                ['title' => 'Видео по теме', 'url' => 'https://youtube.com/playlist?list=example']
            ],
            'recommendations' => [
                'Повторите материал предыдущей недели',
                'Практикуйте произношение ежедневно по 10 минут',
                'Составьте 5 предложений с новой лексикой'
            ]
        ];

        return json_encode($resources);
    }
}
