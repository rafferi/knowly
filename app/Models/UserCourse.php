<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'progress',
        'current_lesson',
        'started_at',
        'completed_at',
        'stats'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'stats' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getProgressPercentageAttribute()
    {
        return $this->progress;
    }

    public function getCompletedLessonsAttribute()
    {
        return $this->current_lesson;
    }

    public function getTotalLessonsAttribute()
    {
        return $this->course->lessons_count ?? 0;
    }
}
