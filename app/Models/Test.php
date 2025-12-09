<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'lesson_id',
        'title',
        'description',
        'type',
        'week_number',
        'time_limit',
        'passing_score',
        'max_attempts',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function questions()
    {
        return $this->hasMany(TestQuestion::class)->orderBy('order_index');
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'placement' => '📊 Placement Test',
            'module_test' => '📘 Module Test',
            'weekly_test' => '📅 Weekly Test',
            'midterm' => '🎯 Midterm',
            'final' => '🏆 Final Test',
            'practice' => '💪 Practice Test'
        ];

        return $labels[$this->type] ?? $this->type;
    }

    public function getEstimatedTimeAttribute()
    {
        return $this->time_limit ? $this->time_limit . ' мин' : 'Без ограничения';
    }

    public function getTotalQuestionsAttribute()
    {
        return $this->questions()->count();
    }

    public function getTotalPointsAttribute()
    {
        return $this->questions()->sum('points');
    }

    public function isAccessibleForUser($user)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->type === 'placement') {
            return true;
        }

        $userProgress = UserLearningProgress::where('user_id', $user->id)
            ->where('course_id', $this->course_id)
            ->first();

        if (!$userProgress) {
            return false;
        }

        if ($this->week_number && $this->week_number > $userProgress->current_week) {
            return false;
        }

        return true;
    }
}
