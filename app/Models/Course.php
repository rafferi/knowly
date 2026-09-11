<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'level',
        'level_id',
        'target_level_id',
        'price',
        'duration',
        'lessons_count',
        'image',
        'thumbnail',
        'is_active',
        'popular',
        'category_id',
        'course_type',
        'features',
        'syllabus',
        'requirements',
        'outcomes',
        'language',
        'rating',
        'reviews_count',
        'certificate_template'
    ];

    protected $casts = [
        'features' => 'array',
        'syllabus' => 'array',
        'requirements' => 'array',
        'outcomes' => 'array',
        'is_active' => 'boolean',
        'popular' => 'boolean',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'lessons_count' => 'integer',
        'duration' => 'integer',
        'reviews_count' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function targetLevel()
    {
        return $this->belongsTo(Level::class, 'target_level_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order_index');
    }

    public function sections()
    {
        return $this->hasManyThrough(Section::class, Module::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activeEnrollments()
    {
        return $this->enrollments()->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('popular', true);
    }

    public function getAvgProgressAttribute()
    {
        return $this->userCourses()->avg('progress') ?? 0;
    }

    public function getTotalEnrollmentsAttribute()
    {
        return $this->enrollments()->count();
    }

    public function getActiveEnrollmentsCountAttribute()
    {
        return $this->enrollments()->where('status', 'active')->count();
    }

    public function getCompletedEnrollmentsCountAttribute()
    {
        return $this->enrollments()->where('status', 'completed')->count();
    }
    // Количество опубликованных уроков
    public function getPublishedLessonsCountAttribute(): int
    {
        return $this->lessons()->where('status', 'published')->count();
    }

// Количество активных тестов (без placement)
    public function getActiveTestsCountAttribute(): int
    {
        return $this->tests()->where('is_active', true)->where('type', '!=', 'placement')->count();
    }
}
