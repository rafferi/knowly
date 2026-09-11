<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'section_id',
        'parent_lesson_id',
        'title',
        'slug',
        'description',
        'content',
        'preview_content',
        'video_url',
        'thumbnail_url',
        'audio_url',
        'duration',
        'order',
        'order_index',
        'lesson_type',
        'difficulty_level',
        'status',
        'version',
        'is_published',
        'is_free',
        'learning_materials',
        'additional_resources',
        'prerequisites',
        'learning_outcomes',
        'keywords',
        'published_at',
        'video_url',
    'audio_url',
    'thumbnail_url',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_free' => 'boolean',
        'duration' => 'integer',
        'order' => 'integer',
        'order_index' => 'integer',
        'learning_materials' => 'array',
        'additional_resources' => 'array',
        'prerequisites' => 'array',
        'learning_outcomes' => 'array',
        'keywords' => 'array',
        'published_at' => 'datetime'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parentLesson()
    {
        return $this->belongsTo(Lesson::class, 'parent_lesson_id');
    }

    public function childLessons()
    {
        return $this->hasMany(Lesson::class, 'parent_lesson_id');
    }

    public function contents()
    {
        return $this->hasMany(LessonContent::class)->orderBy('order_index');
    }

    public function userLessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function notes()
    {
        return $this->hasMany(UserLessonNote::class);
    }

    public function getLessonTypeLabelAttribute()
    {
        $types = [
            'grammar' => '📝 Грамматика',
            'vocabulary' => '📚 Лексика',
            'listening' => '👂 Аудирование',
            'reading' => '📖 Чтение',
            'writing' => '✍️ Письмо',
            'speaking' => '💬 Разговорный',
            'test' => '📝 Тест',
            'review' => '🔄 Повторение',
            'homework' => '🏠 Домашняя работа',
            'practice' => '💪 Практика',
            'exam' => '🎯 Экзамен'
        ];

        return $types[$this->lesson_type] ?? $this->lesson_type;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'Черновик',
            'published' => 'Опубликован',
            'archived' => 'Архивирован'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('order_index');
    }

    public function getDurationFormattedAttribute()
    {
        if ($this->duration < 60) {
            return $this->duration . ' сек';
        } elseif ($this->duration < 3600) {
            return floor($this->duration / 60) . ' мин';
        } else {
            $hours = floor($this->duration / 3600);
            $minutes = floor(($this->duration % 3600) / 60);
            return $hours . ' ч ' . $minutes . ' мин';
        }
    }

    public function nextLesson()
    {
        return Lesson::where('course_id', $this->course_id)
            ->where('order', '>', $this->order)
            ->where('status', 'published')
            ->orderBy('order')
            ->first();
    }

    public function previousLesson()
    {
        return Lesson::where('course_id', $this->course_id)
            ->where('order', '<', $this->order)
            ->where('status', 'published')
            ->orderBy('order', 'desc')
            ->first();
    }
}
