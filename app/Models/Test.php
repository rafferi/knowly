<?php
// app/Models/Test.php

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
        'questions',
        'time_limit',
        'passing_score',
        'max_attempts',
        'is_active'
    ];

    protected $casts = [
        'questions' => 'array',
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
        return $this->time_limit . ' мин';
    }
}
