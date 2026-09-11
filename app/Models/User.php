<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    // Определяем константы ролей
    const ROLE_STUDENT = 'student';
    const ROLE_TEACHER = 'teacher';
    const ROLE_ADMIN = 'admin';

    // Массив доступных ролей
    public static $roles = [
        'student' => 'Студент',
        'teacher' => 'Преподаватель',
        'admin' => 'Администратор'
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
        'level',
        'xp',
        'total_lessons_completed',
        'streak_days',
        'last_activity_at',
        'remember_token',
        'role' // добавляем роль
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity_at' => 'datetime',
    ];

    // Уровни и требования по XP
    private static $XP_LEVELS = [
        'beginner' => 0,
        'elementary' => 100,
        'intermediate' => 300,
        'upper_intermediate' => 600,
        'advanced' => 1000,
        'master' => 1500
    ];
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    // Метод для получения списка ролей
    public static function getRolesList()
    {
        return self::$roles;
    }
    // Добавить XP пользователю
    public function addXp(int $amount, string $activityType = 'other', int $itemId = 0): void
    {
        // Используем прямые SQL запросы для обновления XP и обновления last_activity_at
        DB::statement("
            UPDATE users
            SET xp = xp + ?,
                last_activity_at = NOW()
            WHERE id = ?
        ", [$amount, $this->id]);

        // Обновляем локальный объект
        $this->xp += $amount;
        $this->last_activity_at = now();

        // Проверяем повышение уровня
        $this->checkLevelUp();

        // Обновляем стрик
        $this->updateStreak();

        // Регистрируем активность за сегодня
        $this->recordDailyActivity($amount, $activityType);

        // Проверяем достижения
        $this->checkAchievements($activityType, $itemId);
    }

    // Проверить и повысить уровень при необходимости
    private function checkLevelUp(): void
    {
        $levels = self::$XP_LEVELS;
        $currentXp = $this->xp;

        // Определяем новый уровень на основе XP
        $newLevel = 'beginner';

        // Проходим по всем уровням и находим максимальный, для которого XP достаточен
        foreach ($levels as $level => $requiredXp) {
            if ($currentXp >= $requiredXp) {
                $newLevel = $level;
            } else {
                break;
            }
        }

        // Если уровень изменился, обновляем
        if ($newLevel !== $this->level) {
            // Обновляем уровень через SQL
            DB::statement("
                UPDATE users
                SET level = ?
                WHERE id = ?
            ", [$newLevel, $this->id]);

            // Обновляем локальный объект
            $this->level = $newLevel;

            // Проверяем достижение для уровня
            $this->checkLevelAchievement($newLevel);

            // Логируем повышение уровня
            \Log::info("User {$this->id} leveled up to {$newLevel} (XP: {$currentXp})");
        }
    }

    // Проверить достижения
    public function checkAchievements($activityType, $itemId = 0): void
    {
        // Получаем все активные достижения
        $achievements = Achievement::where('is_active', true)->get();

        foreach ($achievements as $achievement) {
            // Проверяем, не получил ли уже пользователь это достижение
            $alreadyHas = UserAchievement::where('user_id', $this->id)
                ->where('achievement_id', $achievement->id)
                ->exists();

            if ($alreadyHas) {
                continue;
            }

            $shouldAward = false;

            // Проверяем условия в зависимости от типа достижения
            switch ($achievement->type) {
                case 'xp_total':
                    if ($this->xp >= $achievement->requirement) {
                        $shouldAward = true;
                    }
                    break;

                case 'lessons_completed':
                    $completedLessons = UserLesson::where('user_id', $this->id)
                        ->where('completed', true)
                        ->count();

                    if ($completedLessons >= $achievement->requirement) {
                        $shouldAward = true;
                    }
                    break;

                case 'streak_days':
                    if ($this->streak_days >= $achievement->requirement) {
                        $shouldAward = true;
                    }
                    break;

                case 'tests_passed':
                    $passedTests = TestResult::where('user_id', $this->id)
                        ->where('passed', true)
                        ->count();

                    if ($passedTests >= $achievement->requirement) {
                        $shouldAward = true;
                    }
                    break;

                case 'course_completed':
                    $completedCourses = UserCourse::where('user_id', $this->id)
                        ->whereNotNull('completed_at')
                        ->count();

                    if ($completedCourses >= $achievement->requirement) {
                        $shouldAward = true;
                    }
                    break;

                case 'daily_login':
                    // Проверяем ежедневный вход (упрощенно)
                    if ($activityType === 'login') {
                        $shouldAward = true;
                    }
                    break;
            }

            // Начисляем достижение
            if ($shouldAward) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $this->id,
                    'achievement_id' => $achievement->id,
                    'achieved_at' => now()
                ]);

                // Добавляем XP за достижение
                if ($achievement->xp_reward > 0) {
                    $this->addXp($achievement->xp_reward, 'achievement', $achievement->id);
                }

                \Log::info("Achievement awarded: User {$this->id} got achievement {$achievement->id} ({$achievement->name})");
            }
        }
    }

    // Проверить достижение для уровня
    private function checkLevelAchievement(string $level): void
    {
        $levelAchievements = [
            'elementary' => 'Достиг уровня Elementary',
            'intermediate' => 'Достиг уровня Intermediate',
            'upper_intermediate' => 'Достиг уровня Upper-Intermediate',
            'advanced' => 'Достиг уровня Advanced',
            'master' => 'Достиг уровня Master'
        ];

        if (isset($levelAchievements[$level])) {
            // Создаем достижение для уровня, если его нет в базе
            $achievement = Achievement::firstOrCreate(
                [
                    'name' => $levelAchievements[$level],
                    'type' => 'level_up'
                ],
                [
                    'description' => "Поздравляем! Вы достигли уровня {$level}",
                    'icon' => 'graduation-cap',
                    'requirement' => 0,
                    'xp_reward' => 100,
                    'is_active' => true
                ]
            );

            // Проверяем, есть ли у пользователя это достижение
            $hasAchievement = UserAchievement::where('user_id', $this->id)
                ->where('achievement_id', $achievement->id)
                ->exists();

            if (!$hasAchievement) {
                UserAchievement::create([
                    'user_id' => $this->id,
                    'achievement_id' => $achievement->id,
                    'achieved_at' => now()
                ]);
            }
        }
    }

    // Обновить стрик активности
    public function updateStreak(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // Получаем дату последней активности из базы
        $lastActivity = DB::selectOne("
            SELECT DATE(last_activity_at) as last_activity_date, streak_days
            FROM users
            WHERE id = ?
        ", [$this->id]);

        $lastActivityDate = $lastActivity->last_activity_date ?? null;
        $currentStreak = $lastActivity->streak_days ?? 0;

        // Определяем новый стрик
        $newStreak = 1;

        if ($lastActivityDate === $yesterday) {
            // Если последняя активность была вчера - увеличиваем стрик
            $newStreak = $currentStreak + 1;
        } elseif ($lastActivityDate === $today) {
            // Если уже активничал сегодня - оставляем текущий стрик
            $newStreak = $currentStreak;
        }
        // Иначе (если больше дня не было активности) - стрик = 1

        // Обновляем стрик через SQL
        DB::statement("
            UPDATE users
            SET streak_days = ?
            WHERE id = ?
        ", [$newStreak, $this->id]);

        $this->streak_days = $newStreak;

        // Проверяем достижения для стрика
        $this->checkStreakAchievements($newStreak);
    }

    // Проверить достижения для стрика
    private function checkStreakAchievements(int $streak): void
    {
        $streakAchievements = [
            3 => 'Три дня подряд',
            7 => 'Неделя активности',
            14 => 'Две недели без перерыва',
            30 => 'Месяц упорства'
        ];

        foreach ($streakAchievements as $days => $name) {
            if ($streak >= $days) {
                $achievement = Achievement::firstOrCreate(
                    [
                        'name' => $name,
                        'type' => 'streak_days'
                    ],
                    [
                        'description' => "Учились {$days} дней подряд",
                        'icon' => 'fire',
                        'requirement' => $days,
                        'xp_reward' => $days * 10,
                        'is_active' => true
                    ]
                );

                // Проверяем, есть ли у пользователя это достижение
                $hasAchievement = UserAchievement::where('user_id', $this->id)
                    ->where('achievement_id', $achievement->id)
                    ->exists();

                if (!$hasAchievement) {
                    UserAchievement::create([
                        'user_id' => $this->id,
                        'achievement_id' => $achievement->id,
                        'achieved_at' => now()
                    ]);
                }
            }
        }
    }

    // Записать ежедневную активность
    private function recordDailyActivity(int $xpEarned = 0, string $activityType = 'other'): void
    {
        $today = now()->toDateString();

        // В зависимости от типа активности обновляем разные поля
        if ($activityType === 'lesson') {
            DB::statement("
                INSERT INTO daily_activities (user_id, activity_date, lessons_completed, xp_earned, created_at, updated_at)
                VALUES (?, ?, 1, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                lessons_completed = lessons_completed + 1,
                xp_earned = xp_earned + VALUES(xp_earned),
                updated_at = NOW()
            ", [$this->id, $today, $xpEarned]);

            // Проверяем достижение по количеству уроков
            $this->checkLessonAchievements();

        } elseif ($activityType === 'test') {
            DB::statement("
                INSERT INTO daily_activities (user_id, activity_date, tests_completed, xp_earned, created_at, updated_at)
                VALUES (?, ?, 1, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                tests_completed = tests_completed + 1,
                xp_earned = xp_earned + VALUES(xp_earned),
                updated_at = NOW()
            ", [$this->id, $today, $xpEarned]);

            // Проверяем достижение по количеству тестов
            $this->checkTestAchievements();

        } else {
            DB::statement("
                INSERT INTO daily_activities (user_id, activity_date, xp_earned, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                xp_earned = xp_earned + VALUES(xp_earned),
                updated_at = NOW()
            ", [$this->id, $today, $xpEarned]);
        }
    }

    // Проверить достижения по урокам
    private function checkLessonAchievements(): void
    {
        $lessonAchievements = [
            1 => 'Первый урок',
            5 => 'Пять уроков',
            10 => 'Десять уроков',
            25 => 'Двадцать пять уроков',
            50 => 'Пятьдесят уроков',
            100 => 'Сто уроков'
        ];

        $completedLessons = UserLesson::where('user_id', $this->id)
            ->where('completed', true)
            ->count();

        foreach ($lessonAchievements as $count => $name) {
            if ($completedLessons >= $count) {
                $achievement = Achievement::firstOrCreate(
                    [
                        'name' => $name,
                        'type' => 'lessons_completed'
                    ],
                    [
                        'description' => "Завершили {$count} уроков",
                        'icon' => 'book',
                        'requirement' => $count,
                        'xp_reward' => $count * 5,
                        'is_active' => true
                    ]
                );

                // Проверяем, есть ли у пользователя это достижение
                $hasAchievement = UserAchievement::where('user_id', $this->id)
                    ->where('achievement_id', $achievement->id)
                    ->exists();

                if (!$hasAchievement) {
                    UserAchievement::create([
                        'user_id' => $this->id,
                        'achievement_id' => $achievement->id,
                        'achieved_at' => now()
                    ]);
                }
            }
        }
    }

    // Проверить достижения по тестам
    private function checkTestAchievements(): void
    {
        $testAchievements = [
            1 => 'Первый тест',
            3 => 'Три теста',
            5 => 'Пять тестов',
            10 => 'Десять тестов'
        ];

        $passedTests = TestResult::where('user_id', $this->id)
            ->where('passed', true)
            ->count();

        foreach ($testAchievements as $count => $name) {
            if ($passedTests >= $count) {
                $achievement = Achievement::firstOrCreate(
                    [
                        'name' => $name,
                        'type' => 'tests_passed'
                    ],
                    [
                        'description' => "Сдали {$count} тестов",
                        'icon' => 'clipboard-check',
                        'requirement' => $count,
                        'xp_reward' => $count * 10,
                        'is_active' => true
                    ]
                );

                // Проверяем, есть ли у пользователя это достижение
                $hasAchievement = UserAchievement::where('user_id', $this->id)
                    ->where('achievement_id', $achievement->id)
                    ->exists();

                if (!$hasAchievement) {
                    UserAchievement::create([
                        'user_id' => $this->id,
                        'achievement_id' => $achievement->id,
                        'achieved_at' => now()
                    ]);
                }
            }
        }
    }

    // Получить URL аватара
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (file_exists(public_path('storage/avatars/' . $this->avatar))) {
                return asset('storage/avatars/' . $this->avatar);
            }
            if (file_exists(storage_path('app/public/avatars/' . $this->avatar))) {
                return asset('storage/avatars/' . $this->avatar);
            }
        }
        return null;
    }

    // Получить название уровня
    public function getLevelTitleAttribute()
    {
        $levels = [
            'beginner' => 'Начинающий',
            'elementary' => 'Элементарный',
            'intermediate' => 'Средний',
            'upper_intermediate' => 'Выше среднего',
            'advanced' => 'Продвинутый',
            'master' => 'Мастер'
        ];

        return $levels[$this->level] ?? 'Начинающий';
    }

    // Получить прогресс до следующего уровня
    public function getLevelProgressAttribute()
    {
        $levels = self::$XP_LEVELS;
        $currentXp = $this->xp;
        $currentLevel = $this->level;

        // Если это последний уровень
        if ($currentLevel === 'master') {
            return 100;
        }

        $levelKeys = array_keys($levels);
        $currentIndex = array_search($currentLevel, $levelKeys);

        if ($currentIndex === false || $currentIndex >= count($levelKeys) - 1) {
            return 100;
        }

        $nextLevelKey = $levelKeys[$currentIndex + 1];
        $nextLevelXp = $levels[$nextLevelKey];
        $currentLevelXp = $levels[$currentLevel];

        if ($nextLevelXp > $currentLevelXp) {
            $progress = (($currentXp - $currentLevelXp) / ($nextLevelXp - $currentLevelXp)) * 100;
            return min(max($progress, 0), 100);
        }

        return 0;
    }

    // Получить XP до следующего уровня
    public function getXpToNextLevelAttribute()
    {
        $levels = self::$XP_LEVELS;
        $currentXp = $this->xp;
        $currentLevel = $this->level;

        // Если это последний уровень
        if ($currentLevel === 'master') {
            return 0;
        }

        $levelKeys = array_keys($levels);
        $currentIndex = array_search($currentLevel, $levelKeys);

        if ($currentIndex === false || $currentIndex >= count($levelKeys) - 1) {
            return 0;
        }

        $nextLevelKey = $levelKeys[$currentIndex + 1];
        $nextLevelXp = $levels[$nextLevelKey];

        return max($nextLevelXp - $currentXp, 0);
    }

    // Отношения
    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function userLearningProgress()
    {
        return $this->hasMany(UserLearningProgress::class);
    }

    public function activeCourses()
    {
        return $this->userCourses()->whereNull('completed_at');
    }

    public function completedCourses()
    {
        return $this->userCourses()->whereNotNull('completed_at');
    }

    public function userLessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function completedLessons()
    {
        return $this->userLessons()->where('completed', true);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->using(UserAchievement::class)
            ->withPivot('achieved_at')
            ->withTimestamps();
    }

    public function placementTests()
    {
        return $this->hasMany(PlacementTest::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function dailyActivities()
    {
        return $this->hasMany(DailyActivity::class);
    }

    // Получить текущий стрик (псевдоним для streak_days)
    public function getCurrentStreakAttribute()
    {
        return $this->streak_days;
    }

    // Получить общее время обучения (заглушка, так как просили убрать)
    public function getTotalStudyTimeAttribute()
    {
        return 0;
    }

    public function getLatestTestAttribute()
    {
        return $this->placementTests()->orderBy('completed_at', 'desc')->first();
    }

    public function isTeacher()
    {
        return in_array($this->email, [
                'teacher@gmail.com',
                'admin@example.com',
                'tutor@englishschool.com'
            ]) || $this->role === 'teacher' || $this->role === 'admin';
    }

    // Получить статистику за последние 7 дней
    public function getWeeklyStats()
    {
        return DB::select("
            SELECT
                activity_date,
                SUM(lessons_completed) as lessons_completed,
                SUM(tests_completed) as tests_completed,
                SUM(xp_earned) as xp_earned
            FROM daily_activities
            WHERE user_id = ?
            AND activity_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY activity_date
            ORDER BY activity_date DESC
        ", [$this->id]);
    }

    // Статический метод для инициализации статистики пользователя
    public static function initializeUserStats($userId)
    {
        // Инициализируем total_lessons_completed
        DB::statement("
            UPDATE users u
            SET total_lessons_completed = (
                SELECT COUNT(*)
                FROM user_lessons ul
                WHERE ul.user_id = u.id
                AND ul.completed = 1
            )
            WHERE u.id = ?
        ", [$userId]);

        // Инициализируем streak_days и last_activity_at на основе последнего пройденного урока
        DB::statement("
            UPDATE users u
            SET streak_days = 1,
                last_activity_at = COALESCE(
                    (
                        SELECT MAX(completed_at)
                        FROM user_lessons ul
                        WHERE ul.user_id = u.id
                        AND ul.completed = 1
                        AND completed_at IS NOT NULL
                        LIMIT 1
                    ),
                    NOW()
                )
            WHERE u.id = ?
            AND EXISTS (
                SELECT 1
                FROM user_lessons ul
                WHERE ul.user_id = u.id
                AND ul.completed = 1
            )
        ", [$userId]);
    }

    // Метод для исправления уровня пользователя на основе его XP
    public function fixLevelBasedOnXp()
    {
        $levels = self::$XP_LEVELS;
        $currentXp = $this->xp;

        // Определяем правильный уровень на основе XP
        $correctLevel = 'beginner';

        foreach ($levels as $level => $requiredXp) {
            if ($currentXp >= $requiredXp) {
                $correctLevel = $level;
            } else {
                break;
            }
        }

        // Если текущий уровень не соответствует правильному, исправляем
        if ($this->level !== $correctLevel) {
            $this->level = $correctLevel;
            $this->save();

            \Log::info("Fixed level for user {$this->id}: {$this->level} -> {$correctLevel} (XP: {$currentXp})");
        }

        return $correctLevel;
    }

    // Метод для проверки достижения по XP
    public function checkXpAchievements(): void
    {
        $xpAchievements = [
            100 => 'Первая сотня XP',
            500 => 'Пятьсот XP',
            1000 => 'Тысяча XP',
            5000 => 'Пять тысяч XP',
            10000 => 'Десять тысяч XP'
        ];

        foreach ($xpAchievements as $xp => $name) {
            if ($this->xp >= $xp) {
                $achievement = Achievement::firstOrCreate(
                    [
                        'name' => $name,
                        'type' => 'xp_total'
                    ],
                    [
                        'description' => "Накопили {$xp} опыта",
                        'icon' => 'star',
                        'requirement' => $xp,
                        'xp_reward' => $xp / 10,
                        'is_active' => true
                    ]
                );

                // Проверяем, есть ли у пользователя это достижение
                $hasAchievement = UserAchievement::where('user_id', $this->id)
                    ->where('achievement_id', $achievement->id)
                    ->exists();

                if (!$hasAchievement) {
                    UserAchievement::create([
                        'user_id' => $this->id,
                        'achievement_id' => $achievement->id,
                        'achieved_at' => now()
                    ]);
                }
            }
        }
    }
}
