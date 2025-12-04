<?php
// database/seeders/AchievementSeeder.php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            // Прогресс в уроках
            [
                'name' => 'Первые шаги',
                'description' => 'Завершите первый урок',
                'icon' => '🎯',
                'type' => 'lessons',
                'requirement' => 1,
                'xp_reward' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Ученик',
                'description' => 'Завершите 10 уроков',
                'icon' => '📚',
                'type' => 'lessons',
                'requirement' => 10,
                'xp_reward' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Опытный студент',
                'description' => 'Завершите 50 уроков',
                'icon' => '🏆',
                'type' => 'lessons',
                'requirement' => 50,
                'xp_reward' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Мастер обучения',
                'description' => 'Завершите 100 уроков',
                'icon' => '👑',
                'type' => 'lessons',
                'requirement' => 100,
                'xp_reward' => 500,
                'is_active' => true,
            ],

            // Серии занятий
            [
                'name' => 'Неделя усердия',
                'description' => 'Занимайтесь 7 дней подряд',
                'icon' => '🔥',
                'type' => 'streak',
                'requirement' => 7,
                'xp_reward' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Месяц дисциплины',
                'description' => 'Занимайтесь 30 дней подряд',
                'icon' => '💪',
                'type' => 'streak',
                'requirement' => 30,
                'xp_reward' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Легендарная серия',
                'description' => 'Занимайтесь 90 дней подряд',
                'icon' => '⚡',
                'type' => 'streak',
                'requirement' => 90,
                'xp_reward' => 1000,
                'is_active' => true,
            ],

            // Грамматика
            [
                'name' => 'Грамматический гений',
                'description' => 'Пройдите все уроки грамматики',
                'icon' => '📝',
                'type' => 'grammar',
                'requirement' => 10,
                'xp_reward' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Мастер времен',
                'description' => 'Пройдите все уроки по временам глаголов',
                'icon' => '⏰',
                'type' => 'grammar',
                'requirement' => 5,
                'xp_reward' => 75,
                'is_active' => true,
            ],

            // Разговорная практика
            [
                'name' => 'Первый диалог',
                'description' => 'Завершите первый разговорный урок',
                'icon' => '💬',
                'type' => 'conversation',
                'requirement' => 1,
                'xp_reward' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Общительный',
                'description' => 'Завершите 20 разговорных уроков',
                'icon' => '🗣️',
                'type' => 'conversation',
                'requirement' => 20,
                'xp_reward' => 150,
                'is_active' => true,
            ],

            // Аудирование
            [
                'name' => 'Чуткое ухо',
                'description' => 'Пройдите 15 уроков по аудированию',
                'icon' => '👂',
                'type' => 'listening',
                'requirement' => 15,
                'xp_reward' => 120,
                'is_active' => true,
            ],
            [
                'name' => 'Мастер понимания',
                'description' => 'Пройдите 30 уроков по аудированию',
                'icon' => '🎧',
                'type' => 'listening',
                'requirement' => 30,
                'xp_reward' => 250,
                'is_active' => true,
            ],

            // Произношение
            [
                'name' => 'Чистое произношение',
                'description' => 'Пройдите 10 уроков по произношению',
                'icon' => '🎤',
                'type' => 'pronunciation',
                'requirement' => 10,
                'xp_reward' => 80,
                'is_active' => true,
            ],

            // Тесты и экзамены
            [
                'name' => 'Тестовый боец',
                'description' => 'Пройдите placement test',
                'icon' => '🎯',
                'type' => 'tests',
                'requirement' => 1,
                'xp_reward' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Отличник',
                'description' => 'Получите 90%+ в любом тесте',
                'icon' => '⭐',
                'type' => 'tests',
                'requirement' => 1,
                'xp_reward' => 100,
                'is_active' => true,
            ],

            // Уровни владения языком
            [
                'name' => 'Новичок',
                'description' => 'Достигните уровня A1',
                'icon' => '🌱',
                'type' => 'level',
                'requirement' => 1,
                'xp_reward' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Элементарный',
                'description' => 'Достигните уровня A2',
                'icon' => '📖',
                'type' => 'level',
                'requirement' => 2,
                'xp_reward' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Средний',
                'description' => 'Достигните уровня B1',
                'icon' => '🚀',
                'type' => 'level',
                'requirement' => 3,
                'xp_reward' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Продвинутый',
                'description' => 'Достигните уровня B2',
                'icon' => '💎',
                'type' => 'level',
                'requirement' => 4,
                'xp_reward' => 400,
                'is_active' => true,
            ],
            [
                'name' => 'Профессионал',
                'description' => 'Достигните уровня C1',
                'icon' => '👑',
                'type' => 'level',
                'requirement' => 5,
                'xp_reward' => 800,
                'is_active' => true,
            ],
            [
                'name' => 'Эксперт',
                'description' => 'Достигните уровня C2',
                'icon' => '🏆',
                'type' => 'level',
                'requirement' => 6,
                'xp_reward' => 1500,
                'is_active' => true,
            ],

            // Специальные ачивки
            [
                'name' => 'Ночная сова',
                'description' => 'Занимайтесь после полуночи',
                'icon' => '🦉',
                'type' => 'special',
                'requirement' => 1,
                'xp_reward' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Ранняя пташка',
                'description' => 'Занимайтесь до 7 утра',
                'icon' => '🌅',
                'type' => 'special',
                'requirement' => 1,
                'xp_reward' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Выходной воин',
                'description' => 'Занимайтесь в выходные дни',
                'icon' => '🎯',
                'type' => 'special',
                'requirement' => 5,
                'xp_reward' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Полный курс',
                'description' => 'Завершите любой курс полностью',
                'icon' => '🎓',
                'type' => 'courses',
                'requirement' => 1,
                'xp_reward' => 150,
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }

        $this->command->info('Achievements seeded successfully! Total: ' . count($achievements));
    }
}
