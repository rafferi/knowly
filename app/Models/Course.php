<?php
// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'level',
        'price',
        'duration',
        'lessons_count',
        'group_size',
        'image',
        'is_active',
        'category_id',
        'features',
        'course_type',
        'popular'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'popular' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    // Scope для активных курсов
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope для популярных курсов
    public function scopePopular($query)
    {
        return $query->where('popular', true);
    }
}
