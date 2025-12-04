<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
        'level',
        'xp',
        'streak_days',
        'last_activity_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
        ];
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // Проверяем оба возможных пути
            if (file_exists(public_path('storage/avatars/' . $this->avatar))) {
                return asset('storage/avatars/' . $this->avatar);
            }
            if (file_exists(storage_path('app/public/avatars/' . $this->avatar))) {
                return asset('storage/avatars/' . $this->avatar);
            }
        }
        return null;
    }

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

    public function getLevelProgressAttribute()
    {
        $xpRequirements = [
            'beginner' => 0,
            'elementary' => 100,
            'intermediate' => 300,
            'upper_intermediate' => 600,
            'advanced' => 1000,
            'master' => 1500
        ];

        $currentLevel = array_search($this->level, array_keys($xpRequirements));
        $nextLevel = $currentLevel + 1;

        if ($nextLevel >= count($xpRequirements)) {
            return 100;
        }

        $currentXp = $this->xp;
        $nextLevelKey = array_keys($xpRequirements)[$nextLevel];
        $nextLevelXp = $xpRequirements[$nextLevelKey];
        $currentLevelXp = $xpRequirements[$this->level];

        $progress = (($currentXp - $currentLevelXp) / ($nextLevelXp - $currentLevelXp)) * 100;

        return min(max($progress, 0), 100);
    }


    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
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
            ->using(UserAchievement::class) // Добавляем использование кастомной pivot-модели
            ->withPivot('achieved_at')
            ->withTimestamps();
    }

    public function placementTests()
    {
        return $this->hasMany(PlacementTest::class);
    }


    public function getTotalLessonsCompletedAttribute()
    {
        return $this->completedLessons()->count();
    }

    public function getTotalStudyTimeAttribute()
    {
        return $this->userLessons()->sum('time_spent');
    }

    public function getCurrentStreakAttribute()
    {
        return $this->streak_days;
    }

    public function getLatestTestAttribute()
    {
        return $this->placementTests()->orderBy('completed_at', 'desc')->first();
    }
}
