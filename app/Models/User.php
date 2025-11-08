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
}
