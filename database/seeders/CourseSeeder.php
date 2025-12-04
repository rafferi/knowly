<?php
// database/seeders/CourseSeeder.php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'title' => 'English Starter',
                'slug' => 'english-starter',
                'description' => 'Комплексный курс для начинающих с нуля',
                'short_description' => 'Для начинающих с нуля',
                'level' => 'A1-A2',
                'price' => 8900.00,
                'duration' => 3,
                'lessons_count' => 24,
                'group_size' => '4-6',
                'image' => null,
                'is_active' => true,
                'popular' => false,
                'course_type' => 'beginner',
                'category_id' => null,
                'features' => json_encode([
                    '8 уроков в месяц',
                    'Группа 4-6 человек',
                    'Базовая грамматика',
                    'Произношение и аудирование',
                    'Разговорная практика',
                    'Все учебные материалы'
                ])
            ],
            [
                'title' => 'Conversational Pro',
                'slug' => 'conversational-pro',
                'description' => 'Курс для развития разговорных навыков',
                'short_description' => 'Свободное общение',
                'level' => 'A2-B1',
                'price' => 12900.00,
                'duration' => 4,
                'lessons_count' => 48,
                'group_size' => '3-5',
                'image' => null,
                'is_active' => true,
                'popular' => true,
                'course_type' => 'conversational',
                'category_id' => null,
                'features' => json_encode([
                    '12 уроков в месяц',
                    'Группа 3-5 человек',
                    'Углубленная грамматика',
                    'Живые диалоги и обсуждения',
                    'Сленг и идиомы',
                    'Все учебные материалы',
                    'Участие в разговорном клубе'
                ])
            ],
            [
                'title' => 'Business English',
                'slug' => 'business-english',
                'description' => 'Деловой английский для работы и карьеры',
                'short_description' => 'Для работы и карьеры',
                'level' => 'B1-B2',
                'price' => 16900.00,
                'duration' => 5,
                'lessons_count' => 60,
                'group_size' => '2-4',
                'image' => null,
                'is_active' => true,
                'popular' => false,
                'course_type' => 'business',
                'category_id' => null,
                'features' => json_encode([
                    '12 уроков в месяц',
                    'Группа 2-4 человека',
                    'Деловая переписка',
                    'Презентации и переговоры',
                    'Телефонные разговоры',
                    'Все учебные материалы',
                    'Участие в разговорном клубе',
                    'Поддержка куратора'
                ])
            ],
            [
                'title' => 'IELTS Preparation',
                'slug' => 'ielts-preparation',
                'description' => 'Подготовка к международному экзамену IELTS',
                'short_description' => 'Подготовка к экзамену',
                'level' => 'B2-C1',
                'price' => 18900.00,
                'duration' => 3,
                'lessons_count' => 48,
                'group_size' => 'Индивидуально',
                'image' => null,
                'is_active' => true,
                'popular' => false,
                'course_type' => 'exam',
                'category_id' => null,
                'features' => json_encode([
                    '16 уроков в месяц',
                    'Индивидуальные занятия',
                    'Полная подготовка к IELTS',
                    'Пробные тесты каждую неделю',
                    'Стратегии сдачи экзамена',
                    'Все учебные материалы',
                    'Разбор ошибок',
                    'Персональный куратор'
                ])
            ],
            [
                'title' => 'IT English',
                'slug' => 'it-english',
                'description' => 'Английский для IT-специалистов',
                'short_description' => 'Для IT-специалистов',
                'level' => 'B1-C1',
                'price' => 14900.00,
                'duration' => 4,
                'lessons_count' => 40,
                'group_size' => '3-5',
                'image' => null,
                'is_active' => true,
                'popular' => false,
                'course_type' => 'specialized',
                'category_id' => null,
                'features' => json_encode([
                    '10 уроков в месяц',
                    'Группа 3-5 человек',
                    'Техническая лексика',
                    'Подготовка к собеседованиям',
                    'Документация и код-ревью',
                    'Все учебные материалы',
                    'Участие в IT разговорном клубе',
                    'Поддержка куратора'
                ])
            ],
            [
                'title' => 'Travel English',
                'slug' => 'travel-english',
                'description' => 'Английский для путешествий',
                'short_description' => 'Для путешествий',
                'level' => 'A1-B1',
                'price' => 9900.00,
                'duration' => 2,
                'lessons_count' => 16,
                'group_size' => '4-6',
                'image' => null,
                'is_active' => true,
                'popular' => false,
                'course_type' => 'conversational',
                'category_id' => null,
                'features' => json_encode([
                    '8 уроков в месяц',
                    'Группа 4-6 человек',
                    'Лексика для путешествий',
                    'Аэропорт, отель, ресторан',
                    'Ситуации экстренной помощи',
                    'Все учебные материалы',
                    'Разговорный клуб путешественников'
                ])
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
