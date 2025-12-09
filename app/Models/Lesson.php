<?php
// app/Models/Lesson.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'content',
        'order',
        'week_number',
        'lesson_type',
        'duration',
        'estimated_time',
        'video_url',
        'materials',
        'learning_materials',
        'additional_resources',
        'is_free'
    ];

    protected $casts = [
        'materials' => 'array',
        'learning_materials' => 'array',
        'additional_resources' => 'array',
        'is_free' => 'boolean',
        'duration' => 'integer',
        'estimated_time' => 'integer',
        'week_number' => 'integer',
        'order' => 'integer'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function userLessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function getLessonTypeLabelAttribute()
    {
        $types = [
            'grammar' => 'Грамматика',
            'vocabulary' => 'Лексика',
            'listening' => 'Аудирование',
            'reading' => 'Чтение',
            'writing' => '✍Письмо',
            'speaking' => 'Разговорная практика',
            'test' => 'Тест',
            'review' => 'Повторение',
            'practice' => 'Практика'
        ];

        return $types[$this->lesson_type] ?? $this->lesson_type;
    }
}
